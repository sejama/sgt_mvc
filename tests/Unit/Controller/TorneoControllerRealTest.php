<?php

declare(strict_types=1);

namespace App\Tests\Unit\Controller;

use App\Controller\TorneoController;
use App\Entity\Torneo;
use App\Entity\Usuario;
use App\Manager\CategoriaManager;
use App\Manager\SedeManager;
use App\Manager\TorneoManager;
use Doctrine\ORM\EntityManagerInterface;
use PHPUnit\Framework\TestCase;
use Psr\Log\LoggerInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;

class TorneoControllerRealTest extends TestCase
{
    private function setUsuarioId(Usuario $usuario, int $id): void
    {
        $reflection = new \ReflectionProperty($usuario, 'id');
        $reflection->setAccessible(true);
        $reflection->setValue($usuario, $id);
    }

    public function testIndexSinUsuarioRedirigeALogin(): void
    {
        $controller = new TestableTorneoController();
        $controller->testUser = null;

        $torneoManager = $this->createMock(TorneoManager::class);

        $response = $controller->index($torneoManager);

        self::assertInstanceOf(RedirectResponse::class, $response);
        self::assertSame('/security_login', $response->getTargetUrl());
    }

    public function testIndexSeparaTorneosActivosYFinalizados(): void
    {
        $controller = new TestableTorneoController();

        $usuario = (new Usuario())
            ->setUsername('admin')
            ->setPassword('hash')
            ->setRoles(['ROLE_ADMIN', 'ROLE_USER']);
        $this->setUsuarioId($usuario, 10);
        $controller->testUser = $usuario;

        $torneoFinalizado = (new Torneo())
            ->setNombre('Torneo Finalizado')
            ->setRuta('fin')
            ->setFechaFinTorneo(new \DateTimeImmutable('-2 days'));

        $torneoActivo = (new Torneo())
            ->setNombre('Torneo Activo')
            ->setRuta('act');

        $torneoManager = $this->createMock(TorneoManager::class);
        $torneoManager->expects(self::once())
            ->method('obtenerTorneosXCreador')
            ->with(10)
            ->willReturn([$torneoFinalizado, $torneoActivo]);

        $response = $controller->index($torneoManager);

        self::assertInstanceOf(Response::class, $response);
        self::assertSame('torneo/index.html.twig', $controller->lastTemplate);
        self::assertCount(1, $controller->lastParameters['torneosActivos']);
        self::assertCount(1, $controller->lastParameters['torneosFinalizados']);
    }

    public function testNuevoTorneoSinUsuarioRedirigeALogin(): void
    {
        $controller = new TestableTorneoController();
        $controller->testUser = null;

        $request = Request::create('/admin/torneo/nuevo', 'GET');
        $entityManager = $this->createMock(EntityManagerInterface::class);
        $torneoManager = $this->createMock(TorneoManager::class);
        $categoriaManager = $this->createMock(CategoriaManager::class);
        $sedeManager = $this->createMock(SedeManager::class);
        $logger = $this->createMock(LoggerInterface::class);

        $response = $controller->nuevoTorneo(
            $request,
            $entityManager,
            $torneoManager,
            $categoriaManager,
            $sedeManager,
            $logger
        );

        self::assertInstanceOf(RedirectResponse::class, $response);
        self::assertSame('/security_login', $response->getTargetUrl());
    }

    public function testEliminarTorneoConCsrfInvalidoLanzaExcepcion(): void
    {
        $controller = new TestableTorneoControllerCsrfInvalido();

        $usuario = (new Usuario())
            ->setUsername('admin')
            ->setPassword('hash')
            ->setRoles(['ROLE_ADMIN', 'ROLE_USER']);
        $this->setUsuarioId($usuario, 10);
        $controller->testUser = $usuario;

        $request = Request::create('/admin/torneo/ruta-test/eliminar', 'POST', [
            '_token' => 'token-invalido',
        ]);

        $torneoManager = $this->createMock(TorneoManager::class);
        $categoriaManager = $this->createMock(CategoriaManager::class);
        $sedeManager = $this->createMock(SedeManager::class);
        $entityManager = $this->createMock(EntityManagerInterface::class);
        $logger = $this->createMock(LoggerInterface::class);

        $this->expectException(AccessDeniedException::class);
        $this->expectExceptionMessage('Token CSRF inválido.');

        $controller->eliminarTorneo(
            'ruta-test',
            $request,
            $torneoManager,
            $categoriaManager,
            $sedeManager,
            $entityManager,
            $logger
        );
    }

    public function testEditarReglamentoPostVacioRenderizaConError(): void
    {
        $controller = new TestableTorneoController();

        $usuario = (new Usuario())
            ->setUsername('admin')
            ->setPassword('hash')
            ->setRoles(['ROLE_ADMIN', 'ROLE_USER']);
        $this->setUsuarioId($usuario, 10);
        $controller->testUser = $usuario;

        $torneo = (new Torneo())
            ->setNombre('Torneo')
            ->setRuta('ruta-test')
            ->setCreador($usuario);

        $torneoManager = $this->createMock(TorneoManager::class);
        $torneoManager->expects(self::once())
            ->method('obtenerTorneo')
            ->with('ruta-test')
            ->willReturn($torneo);
        $torneoManager->expects(self::never())->method('editarReglamento');

        $request = Request::create('/admin/torneo/ruta-test/editar/reglamento', 'POST', [
            'reglamento' => '    ',
        ]);

        $logger = $this->createMock(LoggerInterface::class);

        $response = $controller->editarReglamento('ruta-test', $torneoManager, $request, $logger);

        self::assertInstanceOf(Response::class, $response);
        self::assertSame('torneo/reglamento/editar.html.twig', $controller->lastTemplate);
        self::assertSame(['error', 'El Reglamento no puede estar vacío'], $controller->lastFlash);
    }

    public function testEditarReglamentoPostMuyLargoRenderizaConError(): void
    {
        $controller = new TestableTorneoController();

        $usuario = (new Usuario())
            ->setUsername('admin')
            ->setPassword('hash')
            ->setRoles(['ROLE_ADMIN', 'ROLE_USER']);
        $this->setUsuarioId($usuario, 10);
        $controller->testUser = $usuario;

        $torneo = (new Torneo())
            ->setNombre('Torneo')
            ->setRuta('ruta-test')
            ->setCreador($usuario);

        $torneoManager = $this->createMock(TorneoManager::class);
        $torneoManager->expects(self::once())
            ->method('obtenerTorneo')
            ->with('ruta-test')
            ->willReturn($torneo);
        $torneoManager->expects(self::never())->method('editarReglamento');

        $request = Request::create('/admin/torneo/ruta-test/editar/reglamento', 'POST', [
            'reglamento' => str_repeat('a', 5001),
        ]);

        $logger = $this->createMock(LoggerInterface::class);

        $response = $controller->editarReglamento('ruta-test', $torneoManager, $request, $logger);

        self::assertInstanceOf(Response::class, $response);
        self::assertSame('torneo/reglamento/editar.html.twig', $controller->lastTemplate);
        self::assertSame(['error', 'El Reglamento no puede exceder 5000 caracteres'], $controller->lastFlash);
    }

    public function testEditarReglamentoConUsuarioNoCreadorRedirigeALogin(): void
    {
        $controller = new TestableTorneoController();

        $creador = (new Usuario())
            ->setUsername('creador')
            ->setPassword('hash')
            ->setRoles(['ROLE_ADMIN', 'ROLE_USER']);
        $this->setUsuarioId($creador, 1);

        $otroUsuario = (new Usuario())
            ->setUsername('otro')
            ->setPassword('hash')
            ->setRoles(['ROLE_ADMIN', 'ROLE_USER']);
        $this->setUsuarioId($otroUsuario, 2);
        $controller->testUser = $otroUsuario;

        $torneo = (new Torneo())
            ->setNombre('Torneo')
            ->setRuta('ruta-test')
            ->setCreador($creador);

        $torneoManager = $this->createMock(TorneoManager::class);
        $torneoManager->expects(self::once())
            ->method('obtenerTorneo')
            ->with('ruta-test')
            ->willReturn($torneo);

        $request = Request::create('/admin/torneo/ruta-test/editar/reglamento', 'GET');
        $logger = $this->createMock(LoggerInterface::class);

        $response = $controller->editarReglamento('ruta-test', $torneoManager, $request, $logger);

        self::assertInstanceOf(RedirectResponse::class, $response);
        self::assertSame('/security_login', $response->getTargetUrl());
    }
}

class TestableTorneoController extends TorneoController
{
    public ?Usuario $testUser = null;
    public ?string $lastTemplate = null;
    public array $lastParameters = [];
    public array $lastFlash = [];

    public function getUser(): ?Usuario
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

class TestableTorneoControllerCsrfInvalido extends TestableTorneoController
{
    protected function isCsrfTokenValid(string $id, ?string $token): bool
    {
        return false;
    }
}
