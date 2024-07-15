<?php

namespace App\Tests\unit\Domain\Questionnaire\Repository;

use App\Domain\Questionnaire\Entity\Question;
use App\Domain\Questionnaire\Repository\QuestionRepositoryInterface;

class InMemoryQuestionRepository implements QuestionRepositoryInterface {
    /**
     * @var array<string, Question>
     */
    private array $data = [];

    public function getAllQuestions(): array {
        return array_values($this->data);
    }

    public function save(Question $question): void {
        $this->data[(string)$question->getId()] = $question;
    }

    public function getByQuestionID(int $questionID): ?Question {
        return $this->data[(string)$questionID] ?? null;
    }
}
