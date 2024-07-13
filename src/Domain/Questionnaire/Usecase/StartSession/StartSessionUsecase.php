<?php

namespace App\Domain\Questionnaire\Usecase\StartSession;

use App\Domain\Questionnaire\ArrayShufflerInterface;
use App\Domain\Questionnaire\Entity\Question;
use App\Domain\Questionnaire\Entity\Session;
use App\Domain\Questionnaire\Entity\SessionAnswer;
use App\Domain\Questionnaire\Entity\SessionQuestion;
use App\Domain\Questionnaire\Error\EmptyQuestionsDomainError;
use App\Domain\Questionnaire\Repository\QuestionRepositoryInterface;
use App\Domain\Questionnaire\SessionID;
use Doctrine\ORM\EntityManagerInterface;

readonly class StartSessionUsecase {
    public function __construct(
        private QuestionRepositoryInterface $questionRepository,
        private EntityManagerInterface  $em,
        private ArrayShufflerInterface  $shuffler,
    ) {}

    public function start(): SessionID {
        $questions = $this->questionRepository->getAllQuestions();

        if (empty($questions)) {
            throw new EmptyQuestionsDomainError();
        }

        $session = new Session(new SessionID(), count($questions));
        $shuffledQuestions = $this->shuffler->shuffle($questions);

        foreach ($shuffledQuestions as $p => $question) {
            assert($question instanceof Question);

            $sessionQuestion = new SessionQuestion($session, $question, $p, count($shuffledQuestions)-1 === $p);
            $this->em->persist($sessionQuestion);

            $shuffledAnswers = $this->shuffler->shuffle($question->getAnswers()->toArray());

            foreach ($shuffledAnswers as $k => $answer) {
                $sessionAnswer = new SessionAnswer($session, $question, $answer, $k);
                $this->em->persist($sessionAnswer);
            }
        }

        $this->em->persist($session);
        $this->em->flush();

        return $session->getId();
    }
}
