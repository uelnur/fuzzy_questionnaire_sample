<?php

namespace App\Domain\Questionnaire\Entity;

use App\Domain\Questionnaire\QuestionID;
use App\Domain\Questionnaire\SessionID;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\Criteria;
use Doctrine\Common\Collections\Order;
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
    private readonly \DateTimeImmutable $createdAt;

    #[Column(type: 'boolean')]
    private bool $finished = false;

    #[Column(type: 'datetime_immutable', nullable: true)]
    private ?\DateTimeImmutable $finishedAt = null;

    #[Column(type: 'integer')]
    private int $totalQuestions = 0;

    #[Column(type: 'integer')]
    private int $correctAnswers = 0;

    #[Column(type: 'integer')]
    private int $incorrectAnswers = 0;

    #[OneToMany(targetEntity: SessionQuestion::class, mappedBy: 'session')]
    #[OrderBy(['position' => 'ASC'])]
    private Collection $questions;

    #[OneToMany(targetEntity: SessionAnswer::class, mappedBy: 'session')]
    #[OrderBy(['position' => 'ASC'])]
    private Collection $answers;

    public function __construct(SessionID $id, int $totalQuestions) {
        $this->id             = $id;
        $this->totalQuestions = $totalQuestions;
        $this->createdAt      = new \DateTimeImmutable();
        $this->questions      = new ArrayCollection();
        $this->answers        = new ArrayCollection();
    }

    public function getId(): SessionID {
        return $this->id;
    }

    public function getCreatedAt(): \DateTimeImmutable {
        return $this->createdAt;
    }

    public function isFinished(): bool {
        return $this->finished;
    }

    public function getFinishedAt(): ?\DateTimeImmutable {
        return $this->finishedAt;
    }

    /**
     * @return \Doctrine\Common\Collections\Collection<int, \App\Domain\Questionnaire\Entity\SessionQuestion>
     */
    public function getQuestions(): Collection {
        $criteria = (new Criteria())->orderBy(['position' => Order::Ascending]);
        return $this->questions->matching($criteria);
    }

    public function getNextQuestion(): ?SessionQuestion {
        $criteria = (new Criteria())->orderBy(['position' => Order::Ascending])->where(Criteria::expr()->eq('answered', false));
        return $this->questions->matching($criteria)->first();
    }

    public function getQuestionByPosition(int $position): ?SessionQuestion {
        return $this->questions->findFirst(function ($k, SessionQuestion $question) use ($position) {
            return $question->getPosition() === $position;
        });
    }

    public function finish(): void {
        $this->finished   = true;
        $this->finishedAt = new \DateTimeImmutable();
    }

    /**
     * @param \App\Domain\Questionnaire\QuestionID $questionID
     * @return \Doctrine\Common\Collections\Collection<int, \App\Domain\Questionnaire\Entity\SessionAnswer>
     */
    public function getAnswers(QuestionID $questionID): Collection {
        $criteria = (new Criteria())->orderBy(['position' => Order::Ascending])->where(Criteria::expr()->eq('question', $questionID));
        return $this->answers->matching($criteria);
    }

    public function getAnswerByPosition(QuestionID $questionID, int $position): ?SessionAnswer {
        $criteria = (new Criteria())
            ->orderBy(['position' => Order::Ascending])
            ->where(Criteria::expr()->eq('question', $questionID))
            ->andWhere(Criteria::expr()->eq('position', $position))
        ;
        return $this->answers->matching($criteria)->first();
    }

    public function incrementAnswered(bool $correct): void {
        if ($correct) {
            $this->correctAnswers += 1;
        }
        else {
            $this->incorrectAnswers += 1;
        }
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

}
