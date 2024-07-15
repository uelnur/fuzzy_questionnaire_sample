<?php

namespace App\Domain\Questionnaire\Entity;

use DateTimeImmutable;
use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\Id;
use Doctrine\ORM\Mapping\JoinColumn;
use Doctrine\ORM\Mapping\ManyToOne;
use Doctrine\ORM\Mapping\Table;

#[Entity]
#[Table]
class SessionAnswer {
    #[Id]
    #[ManyToOne(targetEntity: Session::class, inversedBy: 'answers')]
    #[JoinColumn(onDelete: 'CASCADE')]
    private readonly Session $session;

    #[Id]
    #[ManyToOne(targetEntity: Question::class)]
    #[JoinColumn(onDelete: 'CASCADE')]
    private readonly Question $question;

    #[Id]
    #[ManyToOne(targetEntity: Answer::class)]
    #[JoinColumn(onDelete: 'CASCADE')]
    private readonly Answer $answer;

    #[Column(type: 'integer')]
    private readonly int $position;

    #[Column(type: 'boolean')]
    private bool $selected = false;

    #[Column(type: 'datetime_immutable', nullable: true)]
    private ?\DateTimeImmutable $selectedAt = null;

    public function __construct(
        Session  $session,
        Answer   $answer,
        int      $position,
    ) {
        $this->session  = $session;
        $this->question = $answer->getQuestion();
        $this->answer   = $answer;
        $this->position = $position;
    }

    public function select(): void {
        $this->selected   = true;
        $this->selectedAt = new DateTimeImmutable();
    }

    public function getSession(): Session {
        return $this->session;
    }

    public function getQuestion(): Question {
        return $this->question;
    }

    public function getAnswer(): Answer {
        return $this->answer;
    }

    public function getAnswerText(): string {
        return $this->answer->getAnswerText();
    }

    public function isAnswerCorrect(): bool {
        return $this->answer->isCorrect();
    }

    public function getPosition(): int {
        return $this->position;
    }

    public function isSelected(): bool {
        return $this->selected;
    }

    public function getSelectedAt(): ?DateTimeImmutable {
        return $this->selectedAt;
    }
}
