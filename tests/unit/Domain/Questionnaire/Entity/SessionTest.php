<?php

namespace App\Tests\unit\Domain\Questionnaire\Entity;

use App\Domain\Questionnaire\AnswerID;
use App\Domain\Questionnaire\Entity\Answer;
use App\Domain\Questionnaire\Entity\Question;
use App\Domain\Questionnaire\Entity\Session;
use App\Domain\Questionnaire\Entity\SessionQuestion;
use App\Domain\Questionnaire\QuestionID;
use App\Domain\Questionnaire\SessionID;
use PHPUnit\Framework\Attributes\Depends;
use PHPUnit\Framework\TestCase;

class SessionTest extends TestCase {
    public function testCreateQuestion1(): Question {
        $questionID = new QuestionID();
        $question = new Question($questionID, 'question', 0);
        $this->assertEquals('question', $question->getQuestionText());

        return $question;
    }

    #[Depends('testCreateQuestion1')]
    public function testCreateCorrectAnswer(Question $question): Answer {
        $answerID = new AnswerID();
        $answer = new Answer($answerID, $question, 'answer', true, 0);
        $this->assertEquals('answer', $answer->getAnswerText());

        return $answer;
    }
    #[Depends('testCreateQuestion1')]
    public function testCreateCorrectAnswer2(Question $question): Answer {
        $answerID = new AnswerID();
        $answer = new Answer($answerID, $question, 'answer2', true, 1);
        $this->assertEquals('answer2', $answer->getAnswerText());

        return $answer;
    }
    public function testCreateQuestion2(): Question {
        $questionID = new QuestionID();
        $question = new Question($questionID, 'question2', 1);
        $this->assertEquals('question2', $question->getQuestionText());

        return $question;
    }
    public function testCreateSession(): Session {
        $sessionID = new SessionID();
        $session = new Session($sessionID);

        $this->assertTrue($session->getId()->equals($sessionID));
        $this->assertFalse($session->isFinished());
        $this->assertEquals(0, $session->getQuestions()->count());
        $this->assertEquals(0, $session->getTotalQuestions());
        $this->assertEquals(0, $session->getCorrectAnswers());
        $this->assertEquals(0, $session->getIncorrectAnswers());
        $this->assertNull($session->getNextQuestion());
        $this->assertNull($session->getFinishedAt());

        return $session;
    }

    #[Depends('testCreateSession')]
    #[Depends('testCreateQuestion1')]
    public function testAddQuestion(Session $session, Question $question): SessionQuestion {
        $sessionQuestion = $session->addSessionQuestion($question);

        $this->assertTrue($session->isLastQuestion($sessionQuestion));

        $this->assertEquals(1, $session->getQuestions()->count());
        $this->assertEquals(1, $session->getTotalQuestions());
        $this->assertTrue($session->isCurrentQuestion($sessionQuestion));

        $nextQuestion = $session->getNextQuestion();
        $this->assertNotNull($nextQuestion);
        $this->assertTrue($nextQuestion->getQuestion()->equals($question));
        $this->assertTrue($nextQuestion->equals($sessionQuestion));

        return $sessionQuestion;
    }

    #[Depends('testCreateSession')]
    #[Depends('testCreateQuestion1')]
    public function testAddQuestionSame(Session $session, Question $question): void {
        $this->assertEquals(1, $session->getQuestions()->count());
        $this->assertEquals(1, $session->getTotalQuestions());

        $session->addSessionQuestion($question);

        $this->assertEquals(1, $session->getQuestions()->count());
        $this->assertEquals(1, $session->getTotalQuestions());
    }

    #[Depends('testCreateSession')]
    #[Depends('testCreateCorrectAnswer')]
    public function testAddAnswer(Session $session, Answer $answer): void {
        $sessionAnswer = $session->addSessionAnswer($answer);

        $this->assertEquals(0, $sessionAnswer->getPosition());
        $this->assertTrue($sessionAnswer->getQuestion()->equals($answer->getQuestion()));

        $answer = $session->getAnswerByPosition($answer->getQuestion()->getId(), 0);
        $this->assertNotNull($answer);
    }

    #[Depends('testCreateSession')]
    #[Depends('testCreateCorrectAnswer2')]
    public function testAddAnswer2(Session $session, Answer $answer): void {
        $sessionAnswer = $session->addSessionAnswer($answer);

        $this->assertEquals(1, $sessionAnswer->getPosition());
        $this->assertTrue($sessionAnswer->getQuestion()->equals($answer->getQuestion()));

        $answer = $session->getAnswerByPosition($answer->getQuestion()->getId(), 1);
        $this->assertNotNull($answer);
    }
}
