<?php

namespace App\Domain\Questionnaire\Entity;

use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\Id;
use Doctrine\ORM\Mapping\JoinColumn;
use Doctrine\ORM\Mapping\ManyToOne;
use Doctrine\ORM\Mapping\Table;

#[Entity]
#[Table]
class SessionQuestion {
    #[Id]
    #[ManyToOne(targetEntity: Session::class, inversedBy: "questions")]
    #[JoinColumn(onDelete: 'CASCADE')]
    private readonly Session $session;

    #[Id]
    #[ManyToOne(targetEntity: Question::class)]
    #[JoinColumn(onDelete: 'CASCADE')]
    private readonly Question $question;

    #[Column(type: 'integer')]
    private int $position;

    #[Column(type: 'boolean')]
    private bool $answered;

    #[Column(type: 'boolean')]
    private bool $correct;

    public function __construct(
        Session  $session,
        Question $question,
        int      $position,
    ) {
        $this->session      = $session;
        $this->question     = $question;
        $this->position     = $position;
        $this->answered     = false;
        $this->correct      = false;
    }

    public function getSession(): Session {
        return $this->session;
    }

    public function getQuestion(): Question {
        return $this->question;
    }

    public function getPosition(): int {
        return $this->position;
    }

    public function isAnswered(): bool {
        return $this->answered;
    }

    public function isCorrect(): bool {
        return $this->correct;
    }

    public function setAnswered(bool $isCorrect): void {
        $this->answered = true;
        $this->correct  = $isCorrect;
    }

    public function hasQuestionCorrectAnswer(): bool {
        return $this->getQuestion()->hasCorrectAnswer();
    }

    public function getQuestionText(): string {
        return $this->question->getQuestionText();
    }

    public function equals(SessionQuestion $sessionQuestion): bool {
        return $this->getSession()->getId()->equals($sessionQuestion->getSession()->getId()) && $this->getQuestion()->getId()->equals($sessionQuestion->getQuestion()->getId());
    }
}
