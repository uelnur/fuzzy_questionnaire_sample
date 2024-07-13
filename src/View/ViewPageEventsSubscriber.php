<?php

namespace App\View;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Event\ControllerEvent;
use Symfony\Component\HttpKernel\Event\ExceptionEvent;
use Symfony\Component\HttpKernel\Event\ViewEvent;
use Symfony\Component\HttpKernel\KernelEvents;
use Twig\Environment;

class ViewPageEventsSubscriber implements EventSubscriberInterface {
    private ?ViewPageInterface $mainController = null;
    public function __construct(
        private readonly Environment $twig,
    ) {}

    public static function getSubscribedEvents(): array {
        return [
            KernelEvents::CONTROLLER => 'onKernelController',
            KernelEvents::EXCEPTION => 'onKernelException',
            KernelEvents::VIEW => 'onKernelView',
        ];
    }

    public function onKernelController(ControllerEvent $event): void {
        if ( !$event->isMainRequest() ) {
            return;
        }

        $controller = $event->getController();

        if (is_array($controller)) {
            $controller = $controller[0]??null;
        }

        if ( $controller instanceof ViewPageInterface ) {
            $this->mainController = $controller;
        }
    }

    public function onKernelException(ExceptionEvent $event): void {
        if ( !$event->isMainRequest() || !$this->mainController ) {
            return;
        }
        $exception = $event->getThrowable();

        $callbacks = $this->mainController->getExceptionCallbacks();

        foreach ($callbacks as $exceptionClass => $callback) {
            if ( $exception instanceof $exceptionClass ) {
                $callbackResult = $callback($exception);

                if ( !$callbackResult instanceof Response) {
                    $callbackResult = new Response($this->twig->render($this->mainController->getDefaultTemplate(), $callbackResult??[]));
                }

                $event->setResponse($callbackResult);
                return;
            }
        }
    }

    public function onKernelView(ViewEvent $event): void {
        if ( !$event->isMainRequest() || !$this->mainController ) {
            return;
        }

        if ( !$event->getControllerResult() instanceof Response) {
            $event->setResponse(new Response($this->twig->render($this->mainController->getDefaultTemplate(), $event->getControllerResult())));
        }
    }
}
