<?php

namespace App\Domain\Questionnaire\Usecase\AnswerQuestion;

use App\Domain\Questionnaire\Error\SessionLockedError;
use App\Domain\Questionnaire\Error\SessionNotFoundError;
use App\Domain\Questionnaire\Repository\SessionRepositoryInterface;
use App\Domain\Questionnaire\SessionLockerInterface;

readonly class AnswerQuestionUsecase {
    public function __construct(
        private SessionRepositoryInterface  $sessionRepo,
        private SessionLockerInterface  $locker,
    ) {}

    public function answer(AnswerQuestionRequest $request): void {
        if (!$this->locker->lock($request->sessionID)) {
            throw new SessionLockedError();
        }

        try {
            $session = $this->sessionRepo->getBySessionID($request->sessionID);

            if (!$session) {
                throw new SessionNotFoundError($request->sessionID);
            }
            $session->answer($request->questionNumber, $request->selectedAnswers);

            $this->sessionRepo->save($session);
            $this->locker->unlock($request->sessionID);
        }
        catch (\Exception $exception) {
            $this->locker->unlock($request->sessionID);
            throw $exception;
        }
    }
}
