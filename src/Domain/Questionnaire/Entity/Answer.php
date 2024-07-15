<?php

namespace App\Domain\Questionnaire\Entity;

use App\Domain\Questionnaire\AnswerID;
use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\Id;
use Doctrine\ORM\Mapping\JoinColumn;
use Doctrine\ORM\Mapping\ManyToOne;
use Doctrine\ORM\Mapping\Table;

#[Entity]
#[Table]
class Answer {
    #[Id]
    #[Column(type: 'answer_id')]
    private AnswerID $id;

    #[ManyToOne(targetEntity: Question::class, inversedBy: 'answers')]
    #[JoinColumn(onDelete: 'CASCADE')]
    private readonly Question $question;

    #[Column(type: 'text')]
    private string $answerText;

    #[Column(type: 'boolean')]
    private bool $isCorrect;

    #[Column(type: 'integer')]
    private int $position;

    public function __construct(
        AnswerID $id,
        Question $question,
        string   $answerText,
        bool     $isCorrect,
        int      $position,
    ) {
        $this->id         = $id;
        $this->question   = $question;
        $this->answerText = $answerText;
        $this->isCorrect  = $isCorrect;
        $this->position   = $position;
    }

    public function getId(): AnswerID {
        return $this->id;
    }

    public function getAnswerText(): string {
        return $this->answerText;
    }

    public function setAnswerText(string $answerText): void {
        $this->answerText = $answerText;
    }

    public function isCorrect(): bool {
        return $this->isCorrect;
    }

    public function setIsCorrect(bool $isCorrect): void {
        $this->isCorrect = $isCorrect;
    }

    public function getQuestion(): Question {
        return $this->question;
    }

    public function getPosition(): int {
        return $this->position;
    }

    public function equals(Answer $answer): bool {
        return $this->getId()->equals($answer->getId()) && $this->question->equals($answer->getQuestion());
    }
}
