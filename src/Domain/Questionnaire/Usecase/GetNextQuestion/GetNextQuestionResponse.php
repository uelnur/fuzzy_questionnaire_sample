<?php

namespace App\Domain\Questionnaire\Usecase\GetNextQuestion;

readonly class GetNextQuestionResponse {
    public function __construct(
        public int    $questionNumber,

        public string $questionText,
        public bool   $lastQuestion,

        /** @var array<int, string> $answers */
        public array  $answers,
        public int    $questionsCount,
    ) {}
}
