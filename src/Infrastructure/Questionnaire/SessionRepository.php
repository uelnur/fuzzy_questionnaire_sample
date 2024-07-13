<?php

namespace App\Infrastructure\Questionnaire;

use App\Domain\Questionnaire\Entity\Session;
use App\Domain\Questionnaire\Repository\SessionRepositoryInterface;
use App\Domain\Questionnaire\SessionID;
use Doctrine\ORM\EntityManagerInterface;

readonly class SessionRepository implements SessionRepositoryInterface {
    public function __construct(
        private EntityManagerInterface $em,
    ) {}

    public function getBySessionID(SessionID $sessionID): ?Session {
        return $this->em->getRepository(Session::class)->findOneBy(['id' => $sessionID]);
    }

    public function save(Session $session): void {
        $this->em->persist($session);
        $this->em->flush();
    }

    /**
     * @param bool|null $finished
     * @return Session[]
     */
    public function getAllSessions(?bool $finished): array {
        return $this->em->getRepository(Session::class)->findBy(['finished' => $finished]);
    }
}
