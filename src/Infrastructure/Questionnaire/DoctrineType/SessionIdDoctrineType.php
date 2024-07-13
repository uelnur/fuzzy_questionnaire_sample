<?php

namespace App\Infrastructure\Questionnaire\DoctrineType;

use App\Domain\Questionnaire\SessionID;
use Symfony\Bridge\Doctrine\Types\AbstractUidType;

class SessionIdDoctrineType extends AbstractUidType {
    private const NAME = 'session_id';

    public function getName(): string {
        return self::NAME;
    }

    protected function getUidClass(): string {
        return SessionID::class;
    }
}
