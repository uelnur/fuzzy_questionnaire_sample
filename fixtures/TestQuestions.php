<?php

namespace DataFixtures;

use App\Domain\Questionnaire\AnswerID;
use App\Domain\Questionnaire\Entity\Answer;
use App\Domain\Questionnaire\Entity\Question;
use App\Domain\Questionnaire\QuestionID;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class TestQuestions extends Fixture {

    public function load(ObjectManager $manager): void {
        $questions = [
            [
                'text'    => '1 + 1 =',
                'options' => [
                    ['3', false],
                    ['2', true],
                    ['0', false],
                ],
            ],
            [
                'text'    => '2 + 2 =',
                'options' => [
                    ['4', true],
                    ['3 + 1', true],
                    ['10', false],
                ],
            ],
            [
                'text'    => '3 + 3 =',
                'options' => [
                    ['1 + 5', true],
                    ['1', false],
                    ['6', true],
                    ['2 + 4', true],
                ],
            ],
            [
                'text'    => '4 + 4 =',
                'options' => [
                    ['8', true],
                    ['4', false],
                    ['0', false],
                    ['0 + 8', true],
                ],
            ],
            [
                'text'    => '5 + 5 =',
                'options' => [
                    ['6', false],
                    ['18', false],
                    ['10', true],
                    ['9', false],
                    ['0', false],
                ],
            ],
            [
                'text'    => '6 + 6 =',
                'options' => [
                    ['3', false],
                    ['9', false],
                    ['0', false],
                    ['12', true],
                    ['5 + 7', true],
                ],
            ],
            [
                'text'    => '7 + 7 =',
                'options' => [
                    ['5', false],
                    ['14', true],
                ],
            ],
            [
                'text'    => '8 + 8 =',
                'options' => [
                    ['16', true],
                    ['12', false],
                    ['9', false],
                    ['5', false],
                ],
            ],
            [
                'text'    => '9 + 9 =',
                'options' => [
                    ['18', true],
                    ['9', false],
                    ['17 + 1', true],
                    ['2 + 16', true],
                ],
            ],
            [
                'text'    => '10 + 10 =',
                'options' => [
                    ['0', false],
                    ['2', false],
                    ['8', false],
                    ['20', true],
                ],
            ],
        ];

        foreach ($questions as $p => $q) {
            $question = new Question(new QuestionID(), $q['text'], $p);

            foreach ($q['options'] as $k => $a) {
                $answer = new Answer(new AnswerID(), $question, $a[0], $a[1], $k);
                $manager->persist($answer);
            }
            $manager->persist($question);
        }

        $manager->flush();
    }
}
