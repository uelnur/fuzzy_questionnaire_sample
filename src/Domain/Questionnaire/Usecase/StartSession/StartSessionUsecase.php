<?php

namespace App\Domain\Questionnaire\Usecase\StartSession;

use App\Domain\Questionnaire\ArrayShufflerInterface;
use App\Domain\Questionnaire\Entity\Question;
use App\Domain\Questionnaire\Entity\Session;
use App\Domain\Questionnaire\Entity\SessionAnswer;
use App\Domain\Questionnaire\Entity\SessionQuestion;
use App\Domain\Questionnaire\Error\EmptyQuestionsDomainError;
use App\Domain\Questionnaire\Repository\QuestionRepositoryInterface;
use App\Domain\Questionnaire\Repository\SessionRepositoryInterface;
use App\Domain\Questionnaire\SessionID;

readonly class StartSessionUsecase {
    public function __construct(
        private QuestionRepositoryInterface $questionRepository,
        private ArrayShufflerInterface      $shuffler,
        private SessionRepositoryInterface  $sessionRepository,
    ) {}

    public function start(): SessionID {
        $questions = $this->questionRepository->getAllQuestions();

        if (empty($questions)) {
            throw new EmptyQuestionsDomainError();
        }

        $session = new Session(new SessionID());
        $shuffledQuestions = $this->shuffler->shuffle($questions);

        foreach ($shuffledQuestions as $question) {
            assert($question instanceof Question);

            $session->addSessionQuestion($question);
            $shuffledAnswers = $this->shuffler->shuffle($question->getAnswers()->toArray());

            foreach ($shuffledAnswers as $answer) {
                $session->addSessionAnswer($answer);
            }
        }

        $this->sessionRepository->save($session);

        return $session->getId();
    }
}
