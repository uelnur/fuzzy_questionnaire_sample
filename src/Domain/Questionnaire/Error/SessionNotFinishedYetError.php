<?php

namespace App\Domain\Questionnaire\Error;

use App\Domain\Questionnaire\SessionID;

class SessionNotFinishedYetError extends AbstractDomainError {
    public function __construct(public readonly SessionID $sessionID) {
        parent::__construct(sprintf('Session with id "%d" has not finished yet', (string)$sessionID));
    }
}