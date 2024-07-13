<?php

namespace App\Domain\Questionnaire\Usecase\GetSessions;

use App\Domain\Questionnaire\Repository\SessionRepositoryInterface;
use App\Domain\Questionnaire\Entity\Session;

readonly class GetSessionsUsecase {
    public function __construct(
        private SessionRepositoryInterface $sessionRepository,
    ) {}

    /**
     * @param bool|null $finished
     * @return SessionInfo[]
     */
    public function getSessions(?bool $finished = null): array {
        $sessions = $this->sessionRepository->getAllSessions($finished);

        $result = [];

        foreach ($sessions as $session) {
            $result[] = new SessionInfo(
                $session->getId(),
                $session->getCreatedAt(),
                $session->isFinished(),
                $session->getFinishedAt(),
                $session->getTotalQuestions(),
                $session->getCorrectAnswers(),
                $session->getIncorrectAnswers(),
            );
        }

        return $result;
    }
}
