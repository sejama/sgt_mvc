<?php

declare(strict_types=1);

namespace App\Tests\Unit\Controller;

use App\Controller\SedeController;
use App\Entity\Sede;
use App\Entity\Torneo;
use App\Entity\Usuario;
use App\Exception\AppException;
use App\Manager\SedeManager;
use App\Manager\TorneoManager;
use Doctrine\ORM\EntityManagerInterface;
use PHPUnit\Framework\TestCase;
use Psr\Log\LoggerInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Symfony\Component\Security\Core\User\UserInterface;

class SedeControllerTest extends TestCase
{
    public function testCrearSedeManejaAppExceptionYMantieneFormulario(): void
    {
        $controller = new TestableSedeController();
        $controller->testUser = (new Usuario())
            ->setUsername('admin')
            ->setPassword('hash')
            ->setRoles(['ROLE_ADMIN', 'ROLE_USER']);

        $request = Request::create('/admin/torneo/ruta-test/sede/nuevo', 'POST', [
            'sedeNombre' => 'Sede Nueva',
            'sedeDireccion' => 'Calle Test 123',
        ]);

        $torneoManager = $this->createMock(TorneoManager::class);
        $torneo = $this->createMock(Torneo::class);
        $torneoManager->expects($this->once())
            ->method('obtenerTorneo')
            ->with('ruta-test')
            ->willReturn($torneo);

        $sedeManager = $this->createMock(SedeManager::class);
        $sedeManager->expects($this->once())
            ->method('crearSede')
            ->with($torneo, 'Sede Nueva', 'Calle Test 123')
            ->willThrowException(new AppException('Sede duplicada'));

        $entityManager = $this->createMock(EntityManagerInterface::class);
        $entityManager->expects($this->never())->method('flush');

        $logger = $this->createMock(LoggerInterface::class);
        $logger->expects($this->once())->method('error');

        $response = $controller->crearSede('ruta-test', $torneoManager, $sedeManager, $request, $entityManager, $logger);

        self::assertInstanceOf(Response::class, $response);
        self::assertSame('sede/nuevo.html.twig', $controller->lastTemplate);
        self::assertSame(['error', 'Sede duplicada'], $controller->lastFlash);
    }

    public function testEditarSedeManejaAppExceptionYMantieneFormulario(): void
    {
        $controller = new TestableSedeController();
        $controller->testUser = (new Usuario())
            ->setUsername('admin')
            ->setPassword('hash')
            ->setRoles(['ROLE_ADMIN', 'ROLE_USER']);

        $request = Request::create('/admin/torneo/ruta-test/sede/9/editar', 'POST', [
            'sedeNombre' => 'Sede Editada',
            'sedeDireccion' => 'Calle Nueva 9',
        ]);

        $torneoManager = $this->createMock(TorneoManager::class);
        $torneo = $this->createMock(Torneo::class);
        $torneoManager->expects($this->once())
            ->method('obtenerTorneo')
            ->with('ruta-test')
            ->willReturn($torneo);

        $sede = $this->createMock(Sede::class);
        $sedeManager = $this->createMock(SedeManager::class);
        $sedeManager->expects($this->once())
            ->method('obtenerSede')
            ->with(9)
            ->willReturn($sede);
        $sedeManager->expects($this->once())
            ->method('editarSede')
            ->with($torneo, $sede, 'Sede Editada', 'Calle Nueva 9')
            ->willThrowException(new AppException('Sede duplicada'));

        $logger = $this->createMock(LoggerInterface::class);
        $logger->expects($this->once())->method('error');

        $response = $controller->editarSede('ruta-test', 9, $torneoManager, $sedeManager, $request, $logger);

        self::assertInstanceOf(Response::class, $response);
        self::assertSame('sede/editar.html.twig', $controller->lastTemplate);
        self::assertSame(['error', 'Sede duplicada'], $controller->lastFlash);
    }

    public function testCrearSedePorPostYRedirige(): void
    {
        $controller = new TestableSedeController();
        $controller->testUser = (new Usuario())
            ->setUsername('admin')
            ->setPassword('hash')
            ->setRoles(['ROLE_ADMIN', 'ROLE_USER']);

        $request = Request::create('/admin/torneo/ruta-test/sede/nuevo', 'POST', [
            'sedeNombre' => 'Sede Nueva',
            'sedeDireccion' => 'Calle Test 123',
        ]);

        $torneoManager = $this->createMock(TorneoManager::class);
        $torneo = $this->createMock(Torneo::class);
        $torneoManager->expects($this->once())
            ->method('obtenerTorneo')
            ->with('ruta-test')
            ->willReturn($torneo);

        $sedeManager = $this->createMock(SedeManager::class);
        $sedeManager->expects($this->once())
            ->method('crearSede')
            ->with($torneo, 'Sede Nueva', 'Calle Test 123');

        $entityManager = $this->createMock(EntityManagerInterface::class);
        $entityManager->expects($this->once())->method('flush');

        $logger = $this->createMock(LoggerInterface::class);
        $logger->expects($this->once())->method('info');

        $response = $controller->crearSede('ruta-test', $torneoManager, $sedeManager, $request, $entityManager, $logger);

        self::assertInstanceOf(RedirectResponse::class, $response);
        self::assertSame(['success', 'Sede creada con éxito.'], $controller->lastFlash);
        self::assertSame('/admin_torneo_index', $response->headers->get('Location'));
    }

    public function testCrearSedeSinUsuarioRedirigeALogin(): void
    {
        $controller = new TestableSedeController();
        $controller->testUser = null;

        $request = Request::create('/admin/torneo/ruta-test/sede/nuevo', 'GET');

        $torneoManager = $this->createMock(TorneoManager::class);
        $torneo = $this->createMock(Torneo::class);
        $torneoManager->method('obtenerTorneo')->willReturn($torneo);

        $sedeManager = $this->createMock(SedeManager::class);
        $entityManager = $this->createMock(EntityManagerInterface::class);
        $logger = $this->createMock(LoggerInterface::class);

        $response = $controller->crearSede('ruta-test', $torneoManager, $sedeManager, $request, $entityManager, $logger);

        self::assertInstanceOf(RedirectResponse::class, $response);
        self::assertSame('/security_login', $response->headers->get('Location'));
    }

    public function testEditarSedePorPostYRedirige(): void
    {
        $controller = new TestableSedeController();
        $controller->testUser = (new Usuario())
            ->setUsername('admin')
            ->setPassword('hash')
            ->setRoles(['ROLE_ADMIN', 'ROLE_USER']);

        $request = Request::create('/admin/torneo/ruta-test/sede/9/editar', 'POST', [
            'sedeNombre' => 'Sede Editada',
            'sedeDireccion' => 'Calle Nueva 9',
        ]);

        $torneoManager = $this->createMock(TorneoManager::class);
        $torneo = $this->createMock(Torneo::class);
        $torneoManager->expects($this->once())
            ->method('obtenerTorneo')
            ->with('ruta-test')
            ->willReturn($torneo);

        $sede = $this->createMock(Sede::class);
        $sede->method('getId')->willReturn(9);

        $sedeManager = $this->createMock(SedeManager::class);
        $sedeManager->expects($this->once())
            ->method('obtenerSede')
            ->with(9)
            ->willReturn($sede);
        $sedeManager->expects($this->once())
            ->method('editarSede')
            ->with($torneo, $sede, 'Sede Editada', 'Calle Nueva 9');

        $logger = $this->createMock(LoggerInterface::class);
        $logger->expects($this->once())->method('info');

        $response = $controller->editarSede('ruta-test', 9, $torneoManager, $sedeManager, $request, $logger);

        self::assertInstanceOf(RedirectResponse::class, $response);
        self::assertSame(['success', 'Sede editada con éxito.'], $controller->lastFlash);
        self::assertSame('/admin_torneo_index', $response->headers->get('Location'));
    }

    public function testEditarSedeSinUsuarioRedirigeALogin(): void
    {
        $controller = new TestableSedeController();
        $controller->testUser = null;

        $request = Request::create('/admin/torneo/ruta-test/sede/9/editar', 'GET');

        $torneoManager = $this->createMock(TorneoManager::class);
        $torneo = $this->createMock(Torneo::class);
        $torneoManager->method('obtenerTorneo')->willReturn($torneo);

        $sedeManager = $this->createMock(SedeManager::class);
        $logger = $this->createMock(LoggerInterface::class);

        $response = $controller->editarSede('ruta-test', 9, $torneoManager, $sedeManager, $request, $logger);

        self::assertInstanceOf(RedirectResponse::class, $response);
        self::assertSame('/security_login', $response->headers->get('Location'));
    }

    public function testEliminarSedeManejaAppException(): void
    {
        $controller = new TestableSedeController();
        $controller->testUser = (new Usuario())
            ->setUsername('admin')
            ->setPassword('hash')
            ->setRoles(['ROLE_ADMIN', 'ROLE_USER']);

        $torneoManager = $this->createMock(TorneoManager::class);
        $torneo = $this->createMock(Torneo::class);
        $torneoManager->method('obtenerTorneo')->willReturn($torneo);

        $sede = $this->createMock(Sede::class);
        $sede->method('getId')->willReturn(9);

        $sedeManager = $this->createMock(SedeManager::class);
        $sedeManager->expects($this->once())
            ->method('obtenerSede')
            ->with(9)
            ->willReturn($sede);
        $sedeManager->expects($this->once())
            ->method('eliminarSede')
            ->with($sede)
            ->willThrowException(new AppException('Sede duplicada'));

        $request = Request::create('/admin/torneo/ruta-test/sede/9/eliminar', 'POST', [
            '_token' => 'test-token-delete_sede_9',
        ]);

        $logger = $this->createMock(LoggerInterface::class);
        $logger->expects($this->once())->method('error');

        $response = $controller->eliminarSede('ruta-test', 9, $request, $torneoManager, $sedeManager, $logger);

        self::assertInstanceOf(RedirectResponse::class, $response);
        self::assertSame(['error', 'Sede duplicada'], $controller->lastFlash);
        self::assertSame('/security_login', $response->headers->get('Location'));
    }

    public function testEliminarSedeConCsrfInvalidoLanzaExcepcion(): void
    {
        $controller = new TestableSedeControllerCsrfInvalido();
        $controller->testUser = (new Usuario())
            ->setUsername('admin')
            ->setPassword('hash')
            ->setRoles(['ROLE_ADMIN', 'ROLE_USER']);

        $torneoManager = $this->createMock(TorneoManager::class);
        $torneo = $this->createMock(Torneo::class);
        $torneoManager->method('obtenerTorneo')->willReturn($torneo);

        $sedeManager = $this->createMock(SedeManager::class);
        $logger = $this->createMock(LoggerInterface::class);

        $request = Request::create('/admin/torneo/ruta-test/sede/9/eliminar', 'POST', [
            '_token' => 'token-invalido',
        ]);

        $this->expectException(AccessDeniedException::class);
        $this->expectExceptionMessage('Token CSRF inválido.');

        $controller->eliminarSede('ruta-test', 9, $request, $torneoManager, $sedeManager, $logger);
    }

    public function testEditarSedeManejaExcepcionGenericaYMantieneFormulario(): void
    {
        $controller = new TestableSedeController();
        $controller->testUser = (new Usuario())
            ->setUsername('admin')
            ->setPassword('hash')
            ->setRoles(['ROLE_ADMIN', 'ROLE_USER']);

        $request = Request::create('/admin/torneo/ruta-test/sede/9/editar', 'POST', [
            'sedeNombre' => 'Sede Editada',
            'sedeDireccion' => 'Calle Nueva 9',
        ]);

        $torneoManager = $this->createMock(TorneoManager::class);
        $torneo = $this->createMock(Torneo::class);
        $torneoManager->expects($this->once())
            ->method('obtenerTorneo')
            ->with('ruta-test')
            ->willReturn($torneo);

        $sede = $this->createMock(Sede::class);
        $sedeManager = $this->createMock(SedeManager::class);
        $sedeManager->expects($this->once())
            ->method('obtenerSede')
            ->with(9)
            ->willReturn($sede);
        $sedeManager->expects($this->once())
            ->method('editarSede')
            ->with($torneo, $sede, 'Sede Editada', 'Calle Nueva 9')
            ->willThrowException(new \RuntimeException('boom-sede'));

        $logger = $this->createMock(LoggerInterface::class);
        $logger->expects($this->once())->method('error')->with('boom-sede');

        $response = $controller->editarSede('ruta-test', 9, $torneoManager, $sedeManager, $request, $logger);

        self::assertInstanceOf(Response::class, $response);
        self::assertSame('sede/editar.html.twig', $controller->lastTemplate);
        self::assertSame(['error', 'Ha ocurrido un error inesperado. Por favor, intente nuevamente.'], $controller->lastFlash);
    }
}

class TestableSedeController extends SedeController
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

class TestableSedeControllerCsrfInvalido extends TestableSedeController
{
    protected function isCsrfTokenValid(string $id, ?string $token): bool
    {
        return false;
    }
}
