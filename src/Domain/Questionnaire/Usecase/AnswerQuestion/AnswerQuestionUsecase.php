<?php

namespace App\Domain\Questionnaire\Usecase\AnswerQuestion;

use App\Domain\Questionnaire\Error\QuestionAlreadyAnsweredError;
use App\Domain\Questionnaire\Error\QuestionNotFoundError;
use App\Domain\Questionnaire\Error\SessionLockedError;
use App\Domain\Questionnaire\Error\SessionNotFoundError;
use App\Domain\Questionnaire\Repository\SessionRepositoryInterface;
use App\Domain\Questionnaire\SessionLockerInterface;
use Doctrine\ORM\EntityManagerInterface;

readonly class AnswerQuestionUsecase {
    public function __construct(
        private SessionRepositoryInterface  $sessionRepo,
        private EntityManagerInterface  $em,
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
            $sessionQuestion = $session->getQuestionByPosition($request->questionNumber);
            $nextSessionQuestion = $session->getNextQuestion();

            if (!$sessionQuestion) {
                throw new QuestionNotFoundError();
            }

            if ( !$nextSessionQuestion || !$sessionQuestion->getQuestion()->getId()->equals($nextSessionQuestion->getQuestion()->getId())) {
                throw new QuestionAlreadyAnsweredError($sessionQuestion->getPosition());
            }

            $correct = !(empty($request->selectedAnswers) && $nextSessionQuestion->getQuestion()->hasCorrectAnswer());

            foreach ($request->selectedAnswers as $positionNumber) {
                $sessionAnswer = $session->getAnswerByPosition($sessionQuestion->getQuestion()->getId(), $positionNumber);

                if ($sessionAnswer) {
                    $sessionAnswer->select();

                    if (!$sessionAnswer->getAnswer()->isCorrect()) {
                        $correct = false;
                    }

                    $this->em->persist($sessionAnswer);
                }
            }

            $sessionQuestion->setAnswered($correct);
            $session->incrementAnswered($correct);

            if ( $sessionQuestion->isLastQuestion() ) {
                $session->finish();
            }

            $this->sessionRepo->save($session);
            $this->locker->unlock($request->sessionID);
        }
        catch (\Exception $exception) {
            $this->locker->unlock($request->sessionID);
            throw $exception;
        }
    }
}
