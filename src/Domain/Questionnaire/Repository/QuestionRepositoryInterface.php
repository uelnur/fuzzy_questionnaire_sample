<?php

namespace App\Domain\Questionnaire\Repository;

use App\Domain\Questionnaire\Entity\Question;

interface QuestionRepositoryInterface {
    public function getQuestionByID(int $questionID): ?Question;

    /**
     * @return array<Question>
     */
    public function getAllQuestions(): array;
}
