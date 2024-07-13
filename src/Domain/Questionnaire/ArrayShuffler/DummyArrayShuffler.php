<?php

namespace App\Domain\Questionnaire\ArrayShuffler;

use App\Domain\Questionnaire\ArrayShufflerInterface;

class DummyArrayShuffler implements ArrayShufflerInterface {
    public function shuffle(array $array): array {
        return $array;
    }
}
