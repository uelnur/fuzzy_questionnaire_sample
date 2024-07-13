<?php

namespace App\View\Pages\SessionPage;

use App\Domain\Questionnaire\Error\SessionAlreadyFinishedError;
use App\Domain\Questionnaire\Error\SessionNotFoundError;
use App\Domain\Questionnaire\SessionID;
use App\Domain\Questionnaire\Usecase\AnswerQuestion\AnswerQuestionRequest;
use App\Domain\Questionnaire\Usecase\AnswerQuestion\AnswerQuestionUsecase;
use App\Domain\Questionnaire\Usecase\GetNextQuestion\GetNextQuestionUsecase;
use App\View\AbstractViewPage;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class SessionPageView extends AbstractViewPage {
    protected string $defaultTemplate = 'questionnaire/question.html.twig';

    public function __construct(
        private readonly GetNextQuestionUsecase $getNextQuestionUsecase,
        private readonly AnswerQuestionUsecase $answerQuestionUsecase,
    ) {}

    #[Route('/session/{sessionID}', name: 'session_page')]
    public function __invoke(SessionID $sessionID, Request $request): array|Response {
        $question = $this->getNextQuestionUsecase->getNextQuestion($sessionID);

        $form = $this->createForm(AnswerQuestionForm::class, ['selectedAnswers' => []], [
            'answers' => $question->answers,
        ]);
        $form->handleRequest($request);

        if ( $form->isSubmitted() && $form->isValid() ) {
            $answerRequest = new AnswerQuestionRequest($sessionID, $question->questionNumber, $form->getData()['selectedAnswers']);
            $this->answerQuestionUsecase->answer($answerRequest);

            if ( $question->lastQuestion ) {
                return $this->redirectToRoute('session_result_page', [
                    'sessionID' => $sessionID,
                ]);
            }

            return $this->redirectToRoute('session_page', [
                'sessionID' => $sessionID,
            ]);
        }

        return [
            'form' => $form->createView(),
            'question' => $question->questionText,
            'questionsCount' => $question->questionsCount,
            'questionNumber' => $question->questionNumber,
        ];
    }

    public function onSessionNotFoundError(SessionNotFoundError $error): void {
        $this->defaultTemplate = 'questionnaire/errors/session_not_found.html.twig';
    }

    public function onSessionAlreadyFinishedError(SessionAlreadyFinishedError $error): array {
        $this->defaultTemplate = 'questionnaire/errors/session_already_finished.html.twig';

        return [
            'sessionID' => $error->sessionID,
        ];
    }

    public function getExceptionCallbacks(): array {
        return [
            SessionNotFoundError::class => [$this, 'onSessionNotFoundError'],
            SessionAlreadyFinishedError::class => [$this, 'onSessionAlreadyFinishedError'],
        ];
    }

}
