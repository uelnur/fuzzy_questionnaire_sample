<?php

namespace App\Tests\unit\Domain\Questionnaire\Entity;

use App\Domain\Questionnaire\AnswerID;
use App\Domain\Questionnaire\Entity\Answer;
use App\Domain\Questionnaire\Entity\Question;
use App\Domain\Questionnaire\Entity\Session;
use App\Domain\Questionnaire\Entity\SessionAnswer;
use App\Domain\Questionnaire\QuestionID;
use App\Domain\Questionnaire\SessionID;
use PHPUnit\Framework\TestCase;

class SessionAnswerTest extends TestCase {
    public function test(): void {
        $sessionID = new SessionID();
        $session = new Session($sessionID);

        $questionID = new QuestionID();
        $question = new Question($questionID, 'question', 0);

        $answerID = new AnswerID();
        $answer = new Answer($answerID, $question, 'answer', true, 0);

        $sessionAnswer = new SessionAnswer($session, $answer, 0);
        $this->assertEquals('answer', $sessionAnswer->getAnswerText());
        $this->assertEquals(0, $sessionAnswer->getPosition());
        $this->assertTrue($sessionAnswer->isAnswerCorrect());
        $this->assertFalse($sessionAnswer->isSelected());
        $sessionAnswer->select();
        $this->assertTrue($sessionAnswer->isSelected());
    }
}
