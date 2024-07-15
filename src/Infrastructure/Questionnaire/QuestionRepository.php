<?php

namespace App\Infrastructure\Questionnaire;

use App\Domain\Questionnaire\Entity\Question;
use App\Domain\Questionnaire\Repository\QuestionRepositoryInterface;
use Doctrine\ORM\EntityManagerInterface;

readonly class QuestionRepository implements QuestionRepositoryInterface {
    public function __construct(
        private EntityManagerInterface $em,
    ) {}

    /** @return array<Question> */
    public function getAllQuestions(): array {
        return $this->em->getRepository(Question::class)->findAll();
    }

    public function getByQuestionID(int $questionID): ?Question {
        return $this->em->getRepository(Question::class)->findOneBy([
            'id' => $questionID
        ]);
    }
}
