<?php

namespace App\Domain\Questionnaire;

interface SessionLockerInterface {
    public function lock(SessionID $sessionID): bool;
    public function unlock(SessionID $sessionID): void;
}
