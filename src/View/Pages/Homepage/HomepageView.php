<?php

namespace App\View\Pages\Homepage;

use App\Domain\Questionnaire\Usecase\GetSessions\GetSessionsUsecase;
use App\Domain\Questionnaire\Usecase\StartSession\StartSessionUsecase;
use App\View\AbstractViewPage;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class HomepageView extends AbstractViewPage {
    protected string $defaultTemplate = 'questionnaire/homepage.html.twig';
    public function __construct(
        private readonly StartSessionUsecase $startSessionUsecase,
        private readonly GetSessionsUsecase $getSessionsUsecase,
    ) {}

    #[Route('/', name: 'homepage')]
    public function __invoke(Request $request): Response|array {
        $form = $this->createForm(StartSessionForm::class);
        $form->handleRequest($request);

        if ( $form->isSubmitted() && $form->isValid() ) {
            $sessionID = $this->startSessionUsecase->start();

            return $this->redirectToRoute('session_page', [
                'sessionID' => $sessionID,
            ]);
        }

        $finishedSessions = $this->getSessionsUsecase->getSessions(true);
        $activeSessions = $this->getSessionsUsecase->getSessions(false);

        return [
            'form' => $form->createView(),
            'finishedSessions' => $finishedSessions,
            'activeSessions' => $activeSessions,
        ];
    }
}
