<?php

namespace App\Domain\Questionnaire\Entity;

use App\Domain\Questionnaire\QuestionID;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\GeneratedValue;
use Doctrine\ORM\Mapping\Id;
use Doctrine\ORM\Mapping\OneToMany;
use Doctrine\ORM\Mapping\OrderBy;
use Doctrine\ORM\Mapping\Table;

#[Entity]
#[Table]
class Question {
    #[Id]
    #[Column(type: 'question_id')]
    private QuestionID $id;

    #[Column(type: 'text')]
    private string $questionText;

    #[Column(type: 'integer')]
    private int $position;

    /** @var Collection<int, Answer> $answers */
    #[OneToMany(targetEntity: Answer::class, mappedBy: 'question', orphanRemoval: true)]
    #[OrderBy(['position' => 'ASC'])]
    private Collection $answers;

    public function __construct(
        QuestionID $id,
        string     $questionText,
        int        $position,
    ) {
        $this->id           = $id;
        $this->questionText = $questionText;
        $this->position     = $position;
        $this->answers      = new ArrayCollection();
    }

    public function getId(): QuestionID {
        return $this->id;
    }

    public function getQuestionText(): string {
        return $this->questionText;
    }

    public function getPosition(): int {
        return $this->position;
    }

    public function setPosition(int $position): void {
        $this->position = $position;
    }

    /**
     * @return Collection<int, Answer>
     */
    public function getAnswers(): Collection {
        return $this->answers;
    }

    public function hasCorrectAnswer(): bool {
        return (bool)$this->answers->findFirst(function ($k, Answer $answer) {
            return $answer->isCorrect();
        });
    }
}
