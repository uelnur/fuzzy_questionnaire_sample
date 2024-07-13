<?php

namespace App\Domain\Questionnaire;

interface ArrayShufflerInterface {
    public function shuffle(array $array): array;
}
