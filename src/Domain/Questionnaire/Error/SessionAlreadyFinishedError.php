<?php

namespace App\Domain\Questionnaire\Error;

use App\Domain\Questionnaire\SessionID;

class SessionAlreadyFinishedError extends AbstractDomainError {
    public function __construct(public readonly SessionID $sessionID) {
        parent::__construct(sprintf('Session with id "%d" has already been finished', (string)$sessionID));
    }
}