<?php

namespace App\Domain\Questionnaire\Usecase\ShowResult;

readonly class ShowResultResponse {
    public function __construct(
        /** @var array<int, string> $questions */
        public array $questions,

        /** @var array<int, bool> $questions */
        public array $correctQuestions,

        /** @var array<int, array<int, string>> $answers */
        public array $answers,

        /** @var array<int, array<int, bool>> $sessionAnswers */
        public array $correctAnswers,

        /** @var array<int, array<int, bool>> $sessionAnswers */
        public array $selectedAnswers,
    ) {}
}
