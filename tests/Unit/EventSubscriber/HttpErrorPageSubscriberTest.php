<?php

declare(strict_types=1);

namespace App\Tests\Unit\EventSubscriber;

use App\EventSubscriber\HttpErrorPageSubscriber;
use App\Exception\AppException;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Event\ExceptionEvent;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpKernel\HttpKernelInterface;
use Twig\Environment;

class HttpErrorPageSubscriberTest extends TestCase
{
    private Environment&MockObject $twig;
    private HttpKernelInterface&MockObject $kernel;

    protected function setUp(): void
    {
        $this->twig = $this->createMock(Environment::class);
        $this->kernel = $this->createMock(HttpKernelInterface::class);
    }

    public function testGetSubscribedEventsIncluyeKernelException(): void
    {
        $events = HttpErrorPageSubscriber::getSubscribedEvents();

        self::assertArrayHasKey('kernel.exception', $events);
        self::assertSame('onKernelException', $events['kernel.exception']);
    }

    public function testOnKernelExceptionRenderizaPagina404ParaHttpException(): void
    {
        $subscriber = new HttpErrorPageSubscriber($this->twig);
        $request = Request::create('/torneo/inexistente');
        $event = new ExceptionEvent(
            $this->kernel,
            $request,
            HttpKernelInterface::MAIN_REQUEST,
            new NotFoundHttpException('No encontrado')
        );

        $this->twig->expects(self::once())
            ->method('render')
            ->with('error/index.html.twig', [
                'status' => 404,
                'title' => 'Pagina no encontrada',
                'message' => 'Pagina no encontrada',
            ])
            ->willReturn('<h1>404</h1>');

        $subscriber->onKernelException($event);

        self::assertNotNull($event->getResponse());
        self::assertSame(404, $event->getResponse()?->getStatusCode());
        self::assertSame('<h1>404</h1>', $event->getResponse()?->getContent());
    }

    public function testOnKernelExceptionIgnoraRutasInternasProfiler(): void
    {
        $subscriber = new HttpErrorPageSubscriber($this->twig);
        $request = Request::create('/_profiler/abc123');
        $event = new ExceptionEvent(
            $this->kernel,
            $request,
            HttpKernelInterface::MAIN_REQUEST,
            new NotFoundHttpException('No encontrado')
        );

        $this->twig->expects(self::never())->method('render');

        $subscriber->onKernelException($event);

        self::assertNull($event->getResponse());
    }

    public function testOnKernelExceptionMapeaAppExceptionDeNoEncontradoA404(): void
    {
        $subscriber = new HttpErrorPageSubscriber($this->twig);
        $request = Request::create('/admin/recurso');
        $event = new ExceptionEvent(
            $this->kernel,
            $request,
            HttpKernelInterface::MAIN_REQUEST,
            new AppException('Recurso no encontrado en el sistema')
        );

        $this->twig->expects(self::once())
            ->method('render')
            ->willReturn('contenido 404 app-exception');

        $subscriber->onKernelException($event);

        self::assertNotNull($event->getResponse());
        self::assertSame(404, $event->getResponse()?->getStatusCode());
    }

    public function testOnKernelExceptionIgnoraSubrequest(): void
    {
        $subscriber = new HttpErrorPageSubscriber($this->twig);
        $request = Request::create('/admin/ruta');
        $event = new ExceptionEvent(
            $this->kernel,
            $request,
            HttpKernelInterface::SUB_REQUEST,
            new NotFoundHttpException('No encontrado')
        );

        $this->twig->expects(self::never())->method('render');

        $subscriber->onKernelException($event);

        self::assertNull($event->getResponse());
    }
}