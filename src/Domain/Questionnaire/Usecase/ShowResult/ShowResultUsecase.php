<?php

namespace App\Domain\Questionnaire\Usecase\ShowResult;

use App\Domain\Questionnaire\Error\SessionNotFinishedYetError;
use App\Domain\Questionnaire\Error\SessionNotFoundError;
use App\Domain\Questionnaire\Repository\SessionRepositoryInterface;
use App\Domain\Questionnaire\SessionID;

readonly class ShowResultUsecase {
    public function __construct(
        private SessionRepositoryInterface $sessionRepository,
    ) {}

    public function showResult(SessionID $sessionID): ShowResultResponse {
        $session = $this->sessionRepository->getBySessionID($sessionID);

        if ( !$session ) {
            throw new SessionNotFoundError($sessionID);
        }

        if ( !$session->isFinished() ){
            throw new SessionNotFinishedYetError($sessionID);
        }

        $sessionQuestions = $session->getQuestions();
        $questions = [];
        $answers = [];
        $correctQuestions = [];
        $selectedAnswers = [];
        $correctAnswers = [];

        foreach ($sessionQuestions as $sessionQuestion) {
            $questionPosition = $sessionQuestion->getPosition();

            $questions[$questionPosition] = $sessionQuestion->getQuestionText();
            $correctQuestions[$questionPosition] = $sessionQuestion->isCorrect();

            $sessionAnswers = $session->getAnswers($sessionQuestion->getQuestion());

            foreach ($sessionAnswers as $sessionAnswer) {
                $answerPosition = $sessionAnswer->getPosition();
                $answers[$questionPosition][$answerPosition] = $sessionAnswer->getAnswerText();
                $selectedAnswers[$questionPosition][$answerPosition] = $sessionAnswer->isSelected();
                $correctAnswers[$questionPosition][$answerPosition] = $sessionAnswer->isAnswerCorrect();
            }
        }

        return new ShowResultResponse($questions, $correctQuestions, $answers, $correctAnswers, $selectedAnswers);
    }
}
