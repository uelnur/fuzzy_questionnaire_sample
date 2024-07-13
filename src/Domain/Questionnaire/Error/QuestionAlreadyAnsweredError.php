<?php

namespace App\Domain\Questionnaire\Error;

class QuestionAlreadyAnsweredError extends AbstractDomainError {
    public function __construct(public readonly int $positionNumber) {
        parent::__construct(sprintf('Question with number "%d" has already been answered', $positionNumber));
    }
}