<?php

namespace App\Tests\unit\Domain\Questionnaire\Entity;

use App\Domain\Questionnaire\AnswerID;
use App\Domain\Questionnaire\Entity\Answer;
use App\Domain\Questionnaire\Entity\Question;
use App\Domain\Questionnaire\QuestionID;
use PHPUnit\Framework\TestCase;

class QuestionTest extends TestCase {
    public function testQuestion(): void {
        $questionID = new QuestionID();
        $question = new Question(
            $questionID,
            'test',
            10,
        );
        $this->assertTrue($question->getId()->equals($questionID));
        $this->assertEquals('test', $question->getQuestionText());
        $this->assertEquals(10, $question->getPosition());
        $this->assertFalse($question->hasCorrectAnswer());
        $this->assertEquals(0, $question->getAnswers()->count());

        $question->setPosition(5);
        $this->assertEquals(5, $question->getPosition());

        $answerID = new AnswerID();
        $answer = new Answer(
            $answerID,
            $question,
            'answer',
            false,
            0
        );
        $question->addAnswer($answer);
        $this->assertFalse($question->hasCorrectAnswer());
        $this->assertEquals(1, $question->getAnswers()->count());

        $question->addAnswer($answer);
        $this->assertEquals(1, $question->getAnswers()->count());

        $answerID2 = new AnswerID();
        $answer2 = new Answer(
            $answerID2,
            $question,
            'answer2',
            false,
            0
        );
        $question->addAnswer($answer2);
        $this->assertEquals(2, $question->getAnswers()->count());
        $this->assertFalse($question->hasCorrectAnswer());

        $question->addAnswer($answer2);
        $this->assertEquals(2, $question->getAnswers()->count());

        $answer2->setIsCorrect(true);
        $this->assertTrue($question->hasCorrectAnswer());
    }
}
