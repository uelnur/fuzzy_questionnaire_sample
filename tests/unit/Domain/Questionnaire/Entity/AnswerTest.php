<?php

namespace App\Tests\unit\Domain\Questionnaire\Entity;

use App\Domain\Questionnaire\AnswerID;
use App\Domain\Questionnaire\Entity\Answer;
use App\Domain\Questionnaire\Entity\Question;
use App\Domain\Questionnaire\QuestionID;
use PHPUnit\Framework\TestCase;

class AnswerTest extends TestCase {
    public function testAnswer(): void {
        $questionID = new QuestionID();
        $question = new Question(
            $questionID,
            'test',
            10,
        );

        $answerID = new AnswerID();
        $answer = new Answer(
            $answerID,
            $question,
            'answer',
            false,
            10
        );
        $this->assertTrue($answer->getId()->equals($answerID));
        $this->assertTrue($answer->getQuestion()->getId()->equals($questionID));
        $this->assertEquals('answer', $answer->getAnswerText());
        $this->assertEquals(10, $answer->getPosition());
        $this->assertFalse($answer->isCorrect());
        $answer->setIsCorrect(true);
        $this->assertTrue($answer->isCorrect());
    }
}
