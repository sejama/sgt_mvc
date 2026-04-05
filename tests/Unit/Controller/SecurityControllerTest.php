<?php

declare(strict_types=1);

namespace App\Tests\Unit\Controller;

use App\Controller\SecurityController;
use App\Manager\UsuarioManager;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class SecurityControllerTest extends TestCase
{
    public function testLoginRedirigeACrearUsuarioSiNoHayUsuarios(): void
    {
        $controller = new TestableSecurityController();

        $authUtils = $this->createMock(AuthenticationUtils::class);
        $usuarioManager = $this->createMock(UsuarioManager::class);

        $usuarioManager->expects($this->once())
            ->method('obtenerUsuarios')
            ->willReturn([]);

        $response = $controller->login($authUtils, $usuarioManager);

        self::assertInstanceOf(RedirectResponse::class, $response);
        self::assertSame('/admin_usuario_crear', $response->getTargetUrl());
    }

    public function testLoginRenderizaFormularioYMuestraErrorSiExiste(): void
    {
        $controller = new TestableSecurityController();

        $authUtils = $this->createMock(AuthenticationUtils::class);
        $usuarioManager = $this->createMock(UsuarioManager::class);

        $usuarioManager->expects($this->once())
            ->method('obtenerUsuarios')
            ->willReturn([new \stdClass()]);

        $error = new AuthenticationException('credenciales inválidas');

        $authUtils->expects($this->once())
            ->method('getLastAuthenticationError')
            ->willReturn($error);
        $authUtils->expects($this->once())
            ->method('getLastUsername')
            ->willReturn('usuario');

        $response = $controller->login($authUtils, $usuarioManager);

        self::assertInstanceOf(Response::class, $response);
        self::assertSame('security/login.html.twig', $controller->lastTemplate);
        self::assertSame('usuario', $controller->lastParameters['last_username']);
        self::assertSame($error, $controller->lastParameters['error']);
        self::assertSame(['error', $error->getMessageKey()], $controller->lastFlash);
    }

    public function testLogoutLanzaLogicException(): void
    {
        $controller = new TestableSecurityController();

        $this->expectException(\LogicException::class);

        $controller->logout();
    }
}

class TestableSecurityController extends SecurityController
{
    public ?string $lastTemplate = null;
    public array $lastParameters = [];
    public array $lastFlash = [];

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
