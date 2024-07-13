<?php

namespace App\Infrastructure\Questionnaire\DoctrineType;

use App\Domain\Questionnaire\QuestionID;
use Symfony\Bridge\Doctrine\Types\AbstractUidType;

class QuestionIdDoctrineType extends AbstractUidType {
    private const NAME = 'question_id';

    public function getName(): string {
        return self::NAME;
    }

    protected function getUidClass(): string {
        return QuestionID::class;
    }
}
