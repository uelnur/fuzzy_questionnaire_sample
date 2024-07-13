<?php

namespace App\Domain\Questionnaire\Usecase\AnswerQuestion;

use App\Domain\Questionnaire\SessionID;

readonly class AnswerQuestionRequest {
    public function __construct(
        public SessionID $sessionID,
        public int       $questionNumber,

        /** @var array<int> $selectedAnswers */
        public array     $selectedAnswers,
    ) {}
}
