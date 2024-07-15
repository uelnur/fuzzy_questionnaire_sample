<?php

namespace App\Tests\unit\Domain\Questionnaire\Repository;

use App\Domain\Questionnaire\Entity\Session;
use App\Domain\Questionnaire\Repository\SessionRepositoryInterface;
use App\Domain\Questionnaire\SessionID;

class InMemorySessionRepository implements SessionRepositoryInterface {
    /**
     * @var array<string, Session>
     */
    private array $data = [];

    public function getBySessionID(SessionID $sessionID): ?Session {
        return $this->data[(string)$sessionID] ?? null;
    }

    public function getAllSessions(?bool $finished): array {
        return array_filter($this->data, function(Session $session) use($finished) {
            return $finished === null || $finished === $session->isFinished();
        });
    }

    public function save(Session $session): void {
        $this->data[(string)$session->getId()] = $session;
    }
}
