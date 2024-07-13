<?php

namespace App\Domain\Questionnaire\Error;

use Throwable;

class SomethingWentWrongDomainError extends AbstractDomainError {
    public function __construct(string $message = '', Throwable $previous = null) {
        parent::__construct($message, 0, $previous);
    }
}
