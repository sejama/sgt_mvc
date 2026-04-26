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
    
    public function testObtenerUsuariosMuestraListaParaAdmin(): void
    {
        $controller = new TestableUsuarioController();
        $controller->testUser = (new Usuario())
            ->setUsername('admin')
            ->setPassword('hash')
            ->setRoles(['ROLE_ADMIN', 'ROLE_USER']);

        $usuario = (new Usuario())
            ->setUsername('user1')
            ->setPassword('hash')
            ->setRoles(['ROLE_USER']);

        $usuarioManager = $this->createMock(UsuarioManager::class);
        $usuarioManager->expects(self::once())
            ->method('obtenerUsuarios')
            ->willReturn([$usuario]);

        $logger = $this->createMock(LoggerInterface::class);
        $logger->expects(self::never())->method('error');

        $response = $controller->obtenerUsuarios($usuarioManager, $logger);

        self::assertInstanceOf(Response::class, $response);
        self::assertSame('usuario/index.html.twig', $controller->lastTemplate);
        self::assertCount(1, $controller->lastParameters['usuarios']);
    }

    public function testCrearUsuarioPrimerAdminPorPostRegistraYRedirige(): void
    {
        $controller = new TestableUsuarioController();
        $controller->testUser = null;

        $request = Request::create('/admin/usuario/nuevo', 'POST', [
            'username' => 'admin',
            'password' => 'Secreta123',
            'nombre' => 'Admin',
            'apellido' => 'Root',
            'email' => 'admin@example.com',
        ]);

        $usuarioManager = $this->createMock(UsuarioManager::class);
        $usuarioManager->expects(self::once())
            ->method('obtenerUsuarios')
            ->willReturn([]);
        $usuarioManager->expects(self::once())
            ->method('registrarUsuario')
            ->with('Admin', 'Root', 'admin@example.com', 'admin', 'Secreta123', ['ROLE_USER', 'ROLE_ADMIN']);

        $logger = $this->createMock(LoggerInterface::class);
        $logger->expects(self::once())->method('info');

        $response = $controller->crearUsuario($request, $usuarioManager, $logger);

        self::assertInstanceOf(RedirectResponse::class, $response);
        self::assertSame('/security_login', $response->getTargetUrl());
        self::assertSame(['success', 'Primer usuario administrador creado correctamente'], $controller->lastFlash);
    }

    public function testCrearUsuarioComoAdminPorPostRegistraYRedirige(): void
    {
        $controller = new TestableUsuarioController();
        $controller->testUser = (new Usuario())
            ->setUsername('admin')
            ->setPassword('hash')
            ->setRoles(['ROLE_ADMIN', 'ROLE_USER']);

        $request = Request::create('/admin/usuario/nuevo', 'POST', [
            'username' => 'usuario2',
            'password' => 'Clave123',
            'nombre' => 'Nombre',
            'apellido' => 'Apellido',
            'email' => 'usuario2@example.com',
            'roles' => ['ROLE_ADMIN'],
        ]);

        $usuarioManager = $this->createMock(UsuarioManager::class);
        $usuarioManager->expects(self::once())
            ->method('obtenerUsuarios')
            ->willReturn([new Usuario()]);
        $usuarioManager->expects(self::once())
            ->method('registrarUsuario')
            ->with('Nombre', 'Apellido', 'usuario2@example.com', 'usuario2', 'Clave123', ['ROLE_USER', 'ROLE_ADMIN']);

        $logger = $this->createMock(LoggerInterface::class);
        $logger->expects(self::once())->method('info');

        $response = $controller->crearUsuario($request, $usuarioManager, $logger);

        self::assertInstanceOf(RedirectResponse::class, $response);
        self::assertSame('/admin_usuario_index', $response->getTargetUrl());
        self::assertSame(['success', 'Usuario registrado correctamente'], $controller->lastFlash);
    }

    public function testCambiarPasswordPorPostActualizaYRedirige(): void
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
            ->with($controller->testUser, 'NuevaPass123!');

        $logger = $this->createMock(LoggerInterface::class);
        $logger->expects(self::once())->method('info');

        $response = $controller->cambiarPassword($request, $usuarioManager, $logger);

        self::assertInstanceOf(RedirectResponse::class, $response);
        self::assertSame('/app_main', $response->getTargetUrl());
        self::assertSame(['success', 'Contraseña cambiada correctamente'], $controller->lastFlash);
    }

    public function testEditarUsuarioPorPostActualizaYRedirige(): void
    {
        $controller = new TestableUsuarioController();
        $controller->testUser = (new Usuario())
            ->setUsername('admin')
            ->setPassword('hash')
            ->setRoles(['ROLE_ADMIN', 'ROLE_USER']);

        $request = Request::create('/admin/usuario/editar/9', 'POST', [
            'nombre' => 'Nombre Nuevo',
            'apellido' => 'Apellido Nuevo',
            'email' => 'nuevo@example.com',
            'username' => 'nuevo_user',
            'roles' => ['ROLE_ADMIN'],
        ]);

        $usuario = (new Usuario())
            ->setUsername('viejo')
            ->setPassword('hash')
            ->setRoles(['ROLE_USER']);

        $usuarioManager = $this->createMock(UsuarioManager::class);
        $usuarioManager->expects(self::once())
            ->method('buscarUsuario')
            ->with(9)
            ->willReturn($usuario);
        $usuarioManager->expects(self::once())
            ->method('editarUsuario')
            ->with($usuario, 'Nombre Nuevo', 'Apellido Nuevo', 'nuevo@example.com', 'nuevo_user', ['ROLE_USER', 'ROLE_ADMIN']);

        $logger = $this->createMock(LoggerInterface::class);
        $logger->expects(self::once())->method('info');

        $response = $controller->editarUsuario($request, $usuarioManager, $logger, 9);

        self::assertInstanceOf(RedirectResponse::class, $response);
        self::assertSame('/admin_usuario_index', $response->getTargetUrl());
        self::assertSame(['success', 'Usuario editado correctamente'], $controller->lastFlash);
    }

    public function testEliminarUsuarioPorPostEliminaYRedirige(): void
    {
        $controller = new TestableUsuarioController();
        $controller->testUser = (new Usuario())
            ->setUsername('admin')
            ->setPassword('hash')
            ->setRoles(['ROLE_ADMIN', 'ROLE_USER']);

        $request = Request::create('/admin/usuario/eliminar/9', 'POST', [
            '_token' => 'test-token-delete_usuario_9',
        ]);

        $usuario = (new Usuario())
            ->setUsername('user')
            ->setPassword('hash')
            ->setRoles(['ROLE_USER']);

        $usuarioManager = $this->createMock(UsuarioManager::class);
        $usuarioManager->expects(self::once())
            ->method('buscarUsuario')
            ->with(9)
            ->willReturn($usuario);
        $usuarioManager->expects(self::once())
            ->method('eliminarUsuario')
            ->with($usuario);

        $logger = $this->createMock(LoggerInterface::class);
        $logger->expects(self::once())->method('info');

        $response = $controller->eliminarUsuario($request, $usuarioManager, $logger, 9);

        self::assertInstanceOf(RedirectResponse::class, $response);
        self::assertSame('/admin_usuario_index', $response->getTargetUrl());
        self::assertSame(['success', 'Usuario eliminado correctamente'], $controller->lastFlash);
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

    protected function isCsrfTokenValid(string $id, ?string $token): bool
    {
        return true;
    }
}
