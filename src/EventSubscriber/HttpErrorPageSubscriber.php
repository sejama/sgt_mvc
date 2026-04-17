<?php

declare(strict_types=1);

namespace App\EventSubscriber;

use App\Exception\AppException;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Event\ExceptionEvent;
use Symfony\Component\HttpKernel\Exception\HttpExceptionInterface;
use Symfony\Component\HttpKernel\KernelEvents;
use Twig\Environment;

class HttpErrorPageSubscriber implements EventSubscriberInterface
{
    public function __construct(private Environment $twig)
    {
    }

    public static function getSubscribedEvents(): array
    {
        return [
            KernelEvents::EXCEPTION => 'onKernelException',
        ];
    }

    public function onKernelException(ExceptionEvent $event): void
    {
        if (!$event->isMainRequest()) {
            return;
        }

        $request = $event->getRequest();
        $path = $request->getPathInfo();

        if (
            str_starts_with($path, '/_profiler')
            || str_starts_with($path, '/_wdt')
            || str_starts_with($path, '/_error')
        ) {
            return;
        }

        $throwable = $event->getThrowable();
        $status = null;

        if ($throwable instanceof HttpExceptionInterface) {
            $status = $throwable->getStatusCode();
        } elseif ($throwable instanceof AppException) {
            $message = mb_strtolower($throwable->getMessage());
            if (str_contains($message, 'no encontrado') || str_contains($message, 'no se encontr')) {
                $status = 404;
            }
        }

        if ($status === null) {
            return;
        }

        if (!in_array($status, [401, 403, 404], true)) {
            return;
        }

        $title = $status === 404 ? 'Pagina no encontrada' : 'No autorizado';
        $message = $status === 404 ? 'Pagina no encontrada' : 'No autorizado';

        $content = $this->twig->render('error/index.html.twig', [
            'status' => $status,
            'title' => $title,
            'message' => $message,
        ]);

        $event->setResponse(new Response($content, $status));
    }
}
