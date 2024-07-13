<?php

namespace App\Infrastructure\Questionnaire\DoctrineType;

use App\Domain\Questionnaire\AnswerID;
use Symfony\Bridge\Doctrine\Types\AbstractUidType;

class AnswerIdDoctrineType extends AbstractUidType {
    private const NAME = 'answer_id';

    public function getName(): string {
        return self::NAME;
    }

    protected function getUidClass(): string {
        return AnswerID::class;
    }
}
