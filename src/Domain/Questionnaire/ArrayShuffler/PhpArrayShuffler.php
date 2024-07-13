<?php

namespace App\Domain\Questionnaire\ArrayShuffler;

use App\Domain\Questionnaire\ArrayShufflerInterface;

class PhpArrayShuffler implements ArrayShufflerInterface {
    public function shuffle(array $array): array {
        shuffle($array);
        return $array;
    }
}
