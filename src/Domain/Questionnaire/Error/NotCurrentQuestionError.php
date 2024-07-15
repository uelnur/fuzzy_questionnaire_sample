<?php

namespace App\Domain\Questionnaire\Error;

class NotCurrentQuestionError extends AbstractDomainError {
    public function __construct(public readonly int $positionNumber) {
        parent::__construct(sprintf('Question with number "%d" is not current active question', $positionNumber));
    }
}