<?php

namespace App\Domain\Questionnaire\Error;

use App\Domain\Questionnaire\SessionID;

class SessionNotFoundError extends AbstractDomainError {
    public function __construct(public readonly SessionID $sessionID) {
        parent::__construct(sprintf('Session with id "%d" not found', (string)$sessionID));
    }
}