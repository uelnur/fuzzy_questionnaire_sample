<?php

namespace App\View\Pages\SessionResultPage;

use App\Domain\Questionnaire\Error\SessionNotFinishedYetError;
use App\Domain\Questionnaire\Error\SessionNotFoundError;
use App\Domain\Questionnaire\SessionID;
use App\Domain\Questionnaire\Usecase\ShowResult\ShowResultUsecase;
use App\View\AbstractViewPage;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;

class SessionResultPageView extends AbstractViewPage {
    protected string $defaultTemplate = 'questionnaire/result.html.twig';

    public function __construct(
        private readonly ShowResultUsecase $showResultUsecase,
    ) {}

    #[Route('/session/{sessionID}/result', name: 'session_result_page')]
    public function __invoke(SessionID $sessionID, Request $request): array {
        $result = $this->showResultUsecase->showResult($sessionID);

        return [
            'result' => $result,
        ];
    }

    public function onSessionNotFoundError(SessionNotFoundError $error): void {
        $this->defaultTemplate = 'questionnaire/errors/session_not_found.html.twig';
    }
    public function onSessionNotFinishedYetError(SessionNotFinishedYetError $error): array {
        $this->defaultTemplate = 'questionnaire/errors/session_not_finished_yet.html.twig';

        return [
            'sessionID' => $error->sessionID,
        ];
    }

    public function getExceptionCallbacks(): array {
        return [
            SessionNotFoundError::class => [$this, 'onSessionNotFoundError'],
            SessionNotFinishedYetError::class => [$this, 'onSessionNotFinishedYetError'],
        ];
    }

    public function getDefaultTemplate(): string {
        return $this->defaultTemplate;
    }
}
