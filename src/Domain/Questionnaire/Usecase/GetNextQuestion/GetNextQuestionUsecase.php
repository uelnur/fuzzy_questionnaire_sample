<?php

namespace App\Domain\Questionnaire\Usecase\GetNextQuestion;

use App\Domain\Questionnaire\Error\SessionAlreadyFinishedError;
use App\Domain\Questionnaire\Error\SessionNotFoundError;
use App\Domain\Questionnaire\Repository\SessionRepositoryInterface;
use App\Domain\Questionnaire\SessionID;

readonly class GetNextQuestionUsecase {
    public function __construct(
        private SessionRepositoryInterface $sessionRepo,
    ) {}

    public function getNextQuestion(SessionID $sessionID): GetNextQuestionResponse {
        $session = $this->sessionRepo->getBySessionID($sessionID);

        if (!$session) {
            throw new SessionNotFoundError($sessionID);
        }

        if ($session->isFinished()) {
            throw new SessionAlreadyFinishedError($sessionID);
        }

        $sessionQuestion = $session->getNextQuestion();

        if (!$sessionQuestion) {
            throw new SessionAlreadyFinishedError($sessionID);
        }

        $sessionAnswers = $session->getAnswers($sessionQuestion->getQuestion());

        $options = [];

        foreach ($sessionAnswers as $k => $answer) {
            $options[$k] = $answer->getAnswer()->getAnswerText();
        }

        return new GetNextQuestionResponse(
            $sessionQuestion->getPosition(),
            $sessionQuestion->getQuestionText(),
            $session->isLastQuestion($sessionQuestion),
            $options,
            $session->getQuestions()->count(),
        );
    }
}
