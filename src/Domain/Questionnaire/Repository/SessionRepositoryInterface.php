<?php

namespace App\Domain\Questionnaire\Repository;

use App\Domain\Questionnaire\Entity\Session;
use App\Domain\Questionnaire\SessionID;
use Doctrine\Common\Collections\Collection;

interface SessionRepositoryInterface {
    public function getBySessionID(SessionID $sessionID): ?Session;

    /**
     * @param bool|null $finished
     * @return Session[]
     */
    public function getAllSessions(?bool $finished): array;

    public function save(Session $session): void;

}
