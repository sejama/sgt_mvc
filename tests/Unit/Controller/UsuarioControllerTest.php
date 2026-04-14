<?php

declare(strict_types=1);

namespace App\Tests\Unit\Controller;

use App\Controller\UsuarioController;
use App\Entity\Usuario;
use App\Exception\AppException;
use App\Manager\UsuarioManager;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\User\UserInterface;
use Psr\Log\LoggerInterface;

class UsuarioControllerTest extends TestCase
{
    public function testObtenerUsuariosManejaErrorInesperadoYRedirigeAMain(): void
    {
        $controller = new TestableUsuarioController();
        $controller->testUser = (new Usuario())
            ->setUsername('admin')
            ->setPassword('hash')
            ->setRoles(['ROLE_ADMIN', 'ROLE_USER']);

        $usuarioManager = $this->createMock(UsuarioManager::class);
        $usuarioManager->expects(self::once())
            ->method('obtenerUsuarios')
            ->willThrowException(new \RuntimeException('boom'));

        $logger = $this->createMock(LoggerInterface::class);
        $logger->expects(self::once())
            ->method('error')
            ->with('boom');

        $response = $controller->obtenerUsuarios($usuarioManager, $logger);

        self::assertInstanceOf(RedirectResponse::class, $response);
        self::assertSame('/app_main', $response->getTargetUrl());
        self::assertSame(['error', 'Ha ocurrido un error inesperado. Por favor, intente nuevamente.'], $controller->lastFlash);
    }

    public function testCambiarPasswordManejaErrorInesperadoYMantieneFormulario(): void
    {
        $controller = new TestableUsuarioController();
        $controller->testUser = (new Usuario())
            ->setUsername('user')
            ->setPassword('hash')
            ->setRoles(['ROLE_USER']);

        $request = Request::create('/admin/usuario/cambiar_password', 'POST', [
            'password' => 'NuevaPass123!',
        ]);

        $usuarioManager = $this->createMock(UsuarioManager::class);
        $usuarioManager->expects(self::once())
            ->method('cambiarPassword')
            ->willThrowException(new \RuntimeException('fallo-cambio'));

        $logger = $this->createMock(LoggerInterface::class);
        $logger->expects(self::once())
            ->method('error')
            ->with('fallo-cambio');

        $response = $controller->cambiarPassword($request, $usuarioManager, $logger);

        self::assertInstanceOf(Response::class, $response);
        self::assertSame('usuario/cambiar_password.html.twig', $controller->lastTemplate);
        self::assertSame(['error', 'Ha ocurrido un error inesperado. Por favor, intente nuevamente.'], $controller->lastFlash);
    }

    public function testCambiarPasswordManejaAppExceptionYMantieneFormulario(): void
    {
        $controller = new TestableUsuarioController();
        $controller->testUser = (new Usuario())
            ->setUsername('user-app')
            ->setPassword('hash')
            ->setRoles(['ROLE_USER']);

        $request = Request::create('/admin/usuario/cambiar_password', 'POST', [
            'password' => 'PassConError123!',
        ]);

        $usuarioManager = $this->createMock(UsuarioManager::class);
        $usuarioManager->expects(self::once())
            ->method('cambiarPassword')
            ->willThrowException(new AppException('Error de negocio'));

        $logger = $this->createMock(LoggerInterface::class);
        $logger->expects(self::once())
            ->method('error')
            ->with('Error de negocio');

        $response = $controller->cambiarPassword($request, $usuarioManager, $logger);

        self::assertInstanceOf(Response::class, $response);
        self::assertSame('usuario/cambiar_password.html.twig', $controller->lastTemplate);
        self::assertSame(['error', 'Error de negocio'], $controller->lastFlash);
    }
}

class TestableUsuarioController extends UsuarioController
{
    public ?UserInterface $testUser = null;
    public ?string $lastTemplate = null;
    public array $lastParameters = [];
    public array $lastFlash = [];

    public function getUser(): ?UserInterface
    {
        return $this->testUser;
    }

    protected function render(string $view, array $parameters = [], Response $response = null): Response
    {
        $this->lastTemplate = $view;
        $this->lastParameters = $parameters;

        return $response ?? new Response('ok');
    }

    public function redirectToRoute(string $route, array $parameters = [], int $status = 302): RedirectResponse
    {
        return new RedirectResponse('/' . $route, $status);
    }

    public function addFlash(string $type, mixed $message): void
    {
        $this->lastFlash = [$type, (string) $message];
    }
}
