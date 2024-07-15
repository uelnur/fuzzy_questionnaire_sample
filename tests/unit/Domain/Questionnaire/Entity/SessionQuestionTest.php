<?php

namespace App\Tests\unit\Domain\Questionnaire\Entity;

use App\Domain\Questionnaire\Entity\Question;
use App\Domain\Questionnaire\Entity\Session;
use App\Domain\Questionnaire\Entity\SessionQuestion;
use App\Domain\Questionnaire\QuestionID;
use App\Domain\Questionnaire\SessionID;
use PHPUnit\Framework\TestCase;

class SessionQuestionTest extends TestCase {
    public function test(): void {
        $sessionID = new SessionID();
        $session = new Session($sessionID);

        $questionID = new QuestionID();
        $question = new Question($questionID, 'question', 0);

        $sessionQuestion = new SessionQuestion($session, $question, 0, true);

        $this->assertTrue($sessionQuestion->getSession()->getId()->equals($sessionID));
        $this->assertTrue($sessionQuestion->getQuestion()->getId()->equals($questionID));
        $this->assertEquals('question', $sessionQuestion->getQuestionText());
        $this->assertEquals(0, $sessionQuestion->getPosition());
        $this->assertFalse($sessionQuestion->isAnswered());
        $this->assertFalse($sessionQuestion->isCorrect());

        $sessionQuestion->setAnswered(true);
        $this->assertTrue($sessionQuestion->isAnswered());
        $this->assertTrue($sessionQuestion->isCorrect());

        $sessionQuestion->setAnswered(false);
        $this->assertTrue($sessionQuestion->isAnswered());
        $this->assertFalse($sessionQuestion->isCorrect());

        $sessionQuestion2 = new SessionQuestion($session, $question, 0, true);
        $this->assertTrue($sessionQuestion->equals($sessionQuestion2));


        $questionID2 = new QuestionID();
        $question2 = new Question($questionID2, 'question2', 0);
        $sessionQuestion3 = new SessionQuestion($session, $question2, 0, true);
        $this->assertFalse($sessionQuestion->equals($sessionQuestion3));
    }
}
