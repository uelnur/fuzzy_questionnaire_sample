<?php

namespace App\Infrastructure\Questionnaire;

use App\Domain\Questionnaire\SessionID;
use App\Domain\Questionnaire\SessionLockerInterface;
use Symfony\Component\Lock\LockFactory;
use Symfony\Component\Lock\LockInterface;

class SessionLocker implements SessionLockerInterface {
    private readonly LockFactory $factory;

    /** @var array<LockInterface> $locks */
    private array $locks = [];

    public function __construct(LockFactory $factory) {
        $this->factory = $factory;
    }

    private function getLock(SessionID $sessionID): LockInterface {
        $lockKey = (string)$sessionID;

        $lock = $this->locks[$lockKey] ?? null;

        if (!$lock) {
            $lock                  = $this->factory->createLock($lockKey);
            $this->locks[$lockKey] = $lock;
        }

        return $lock;
    }

    public function lock(SessionID $sessionID): bool {
        $lock = $this->getLock($sessionID);
        return $lock->acquire();
    }

    public function unlock(SessionID $sessionID): void {
        $lock = $this->getLock($sessionID);
        $lock->release();
    }
}
