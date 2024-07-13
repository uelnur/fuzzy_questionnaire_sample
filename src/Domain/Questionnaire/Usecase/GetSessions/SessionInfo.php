<?php

namespace App\Domain\Questionnaire\Usecase\GetSessions;

use App\Domain\Questionnaire\SessionID;

readonly class SessionInfo {
    public function __construct(
        public SessionID           $sessionID,
        public \DateTimeImmutable  $createdAt,
        public bool                $finished,
        public ?\DateTimeImmutable $finishedAt,
        public int                 $totalQuestions,
        public int                 $correctAnswers,
        public int                 $incorrectAnswers,
    ) {}
}
