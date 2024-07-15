<?php

namespace App\Domain\Questionnaire\Entity;

use App\Domain\Questionnaire\Error\NotCurrentQuestionError;
use App\Domain\Questionnaire\Error\QuestionNotFoundError;
use App\Domain\Questionnaire\QuestionID;
use App\Domain\Questionnaire\SessionID;
use DateTimeImmutable;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\Criteria;
use Doctrine\Common\Collections\Order;
use Doctrine\Common\Collections\ReadableCollection;
use Doctrine\Common\Collections\Selectable;
use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\Id;
use Doctrine\ORM\Mapping\OneToMany;
use Doctrine\ORM\Mapping\OrderBy;
use Doctrine\ORM\Mapping\Table;

#[Entity]
#[Table]
class Session {
    #[Id]
    #[Column(type: 'session_id')]
    private SessionID $id;

    #[Column(type: 'datetime_immutable')]
    private readonly DateTimeImmutable $createdAt;

    #[Column(type: 'boolean')]
    private bool $finished = false;

    #[Column(type: 'datetime_immutable', nullable: true)]
    private ?DateTimeImmutable $finishedAt = null;

    #[Column(type: 'integer')]
    private int $totalQuestions = 0;

    #[Column(type: 'integer')]
    private int $correctAnswers = 0;

    #[Column(type: 'integer')]
    private int $incorrectAnswers = 0;

    /**
     * @var Collection<int, SessionQuestion>&Selectable<int, SessionQuestion> $questions
     */
    #[OneToMany(targetEntity: SessionQuestion::class, mappedBy: 'session', cascade: ['persist', 'remove'])]
    #[OrderBy(['position' => 'ASC'])]
    private Collection&Selectable $questions;

    /**
     * @var Collection<int, SessionAnswer>&Selectable<int, SessionAnswer> $answers
     */
    #[OneToMany(targetEntity: SessionAnswer::class, mappedBy: 'session', cascade: ['persist', 'remove'])]
    #[OrderBy(['position' => 'ASC'])]
    private Collection&Selectable $answers;

    public function __construct(SessionID $id) {
        $this->id             = $id;
        $this->createdAt      = new DateTimeImmutable();
        $this->questions      = new ArrayCollection();
        $this->answers        = new ArrayCollection();
    }

    public function getId(): SessionID {
        return $this->id;
    }

    public function getCreatedAt(): DateTimeImmutable {
        return $this->createdAt;
    }

    public function isFinished(): bool {
        return $this->finished;
    }

    public function getFinishedAt(): ?DateTimeImmutable {
        return $this->finishedAt;
    }

    public function getTotalQuestions(): int {
        return $this->totalQuestions;
    }

    public function getCorrectAnswers(): int {
        return $this->correctAnswers;
    }

    public function getIncorrectAnswers(): int {
        return $this->incorrectAnswers;
    }

    /**
     * @return ReadableCollection<int, SessionQuestion>
     */
    public function getQuestions(): ReadableCollection {
        $criteria = (new Criteria())->orderBy(['position' => Order::Ascending]);
        return $this->questions->matching($criteria);
    }

    public function isLastQuestion(SessionQuestion $sessionQuestion): bool {
        return $this->totalQuestions-1 === $sessionQuestion->getPosition();
    }

    public function getNextQuestion(): ?SessionQuestion {
        $criteria = (new Criteria())->orderBy(['position' => Order::Ascending])->where(Criteria::expr()->eq('answered', false));
        return $this->questions->matching($criteria)->first() ?: null;
    }

    public function getQuestionByPosition(int $position): ?SessionQuestion {
        return $this->questions->findFirst(function (int $k, SessionQuestion $question) use ($position) {
            return $question->getPosition() === $position;
        });
    }
    public function getSessionQuestionByQuestion(Question $question): ?SessionQuestion {
        return $this->questions->findFirst(function (int $k, SessionQuestion $sessionQuestion) use ($question) {
            return $sessionQuestion->getQuestion()->equals($question);
        });
    }

    /**
     * @return ReadableCollection<int, SessionAnswer>
     */
    public function getAnswers(Question $question): ReadableCollection {
        $criteria = (new Criteria())->orderBy(['position' => Order::Ascending])->where(Criteria::expr()->eq('question', $question));
        return $this->answers->matching($criteria);
    }

    public function addSessionQuestion(Question $question): SessionQuestion {
        if ($sessionQuestion = $this->getSessionQuestionByQuestion($question)) {
            return $sessionQuestion;
        }

        $sessionQuestion = new SessionQuestion($this, $question, $this->questions->count());
        $this->questions->add($sessionQuestion);
        $this->totalQuestions++;

        return $sessionQuestion;
    }

    public function addSessionAnswer(Answer $answer): SessionAnswer {
        if ($sessionAnswer = $this->answers->findFirst(function (int $k, SessionAnswer $sessionAnswer) use ($answer) {
            return $sessionAnswer->getAnswer()->equals($answer);
        })) {
            return $sessionAnswer;
        }
        $sessionAnswer = new SessionAnswer($this, $answer, $this->getAnswers($answer->getQuestion())->count());
        $this->answers->add($sessionAnswer);

        return $sessionAnswer;
    }

    public function answer(int $questionNumber, array $selectedAnswers): void {
        $sessionQuestion = $this->getQuestionByPosition($questionNumber);

        if (!$sessionQuestion) {
            throw new QuestionNotFoundError();
        }

        if (!$this->isCurrentQuestion($sessionQuestion)) {
            throw new NotCurrentQuestionError($sessionQuestion->getPosition());
        }

        $correct = $this->selectAnswers($sessionQuestion, $selectedAnswers);
        $sessionQuestion->setAnswered($correct);
        $this->incrementAnswered($correct);

        if ($this->isLastQuestion($sessionQuestion)) {
            $this->finish();
        }
    }

    /**
     * @param SessionQuestion $sessionQuestion
     * @param array<int> $selectedAnswers
     * @return bool
     */
    public function selectAnswers(SessionQuestion $sessionQuestion, array $selectedAnswers): bool {
        if (empty($selectedAnswers) ) {
            return !$sessionQuestion->hasQuestionCorrectAnswer();
        }

        $correct = true;

        foreach ($selectedAnswers as $positionNumber) {
            $sessionAnswer = $this->getAnswerByPosition($sessionQuestion->getQuestion()->getId(), $positionNumber);

            if ($sessionAnswer) {
                $sessionAnswer->select();

                if (!$sessionAnswer->isAnswerCorrect()) {
                    $correct = false;
                }
            }
        }

        return $correct;
    }

    public function isCurrentQuestion(SessionQuestion $sessionQuestion): bool {
        $nextSessionQuestion = $this->getNextQuestion();

        return $nextSessionQuestion && $sessionQuestion->equals($nextSessionQuestion);
    }

    public function getAnswerByPosition(QuestionID $questionID, int $position): ?SessionAnswer {
        return $this->answers->findFirst(function (int $k, SessionAnswer $sessionAnswer) use ($questionID, $position){
            return $sessionAnswer->getQuestion()->getId()->equals($questionID) && $sessionAnswer->getPosition() === $position;
        });
    }

    private function finish(): void {
        $this->finished   = true;
        $this->finishedAt = new DateTimeImmutable();
    }

    private function incrementAnswered(bool $correct): void {
        if ($correct) {
            $this->correctAnswers += 1;
        }
        else {
            $this->incorrectAnswers += 1;
        }
    }

}
