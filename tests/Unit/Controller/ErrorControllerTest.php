<?php

declare(strict_types=1);

namespace App\Tests\Unit\Controller;

use App\Controller\ErrorController;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class ErrorControllerTest extends TestCase
{
    public function testIndexUsaValoresPorDefecto(): void
    {
        $controller = new TestableErrorController();
        $request = Request::create('/error', 'GET');

        $response = $controller->index($request);

        self::assertInstanceOf(Response::class, $response);
        self::assertSame(400, $response->getStatusCode());
        self::assertSame('error/index.html.twig', $controller->lastTemplate);
        self::assertSame(400, $controller->lastParameters['status']);
        self::assertSame('Error', $controller->lastParameters['title']);
        self::assertSame('Ha ocurrido un error inesperado.', $controller->lastParameters['message']);
    }

    public function testForbiddenUsaValoresPorDefecto(): void
    {
        $controller = new TestableErrorController();
        $request = Request::create('/no-autorizado', 'GET');

        $response = $controller->forbidden($request);

        self::assertInstanceOf(Response::class, $response);
        self::assertSame(403, $response->getStatusCode());
        self::assertSame('error/forbidden.html.twig', $controller->lastTemplate);
        self::assertSame('No autorizado', $controller->lastParameters['title']);
        self::assertSame('No tienes permisos para acceder a este recurso.', $controller->lastParameters['message']);
    }
}

class TestableErrorController extends ErrorController
{
    public ?string $lastTemplate = null;
    public array $lastParameters = [];

    protected function render(string $view, array $parameters = [], Response $response = null): Response
    {
        $this->lastTemplate = $view;
        $this->lastParameters = $parameters;
        return $response ?? new Response('ok');
    }
}
