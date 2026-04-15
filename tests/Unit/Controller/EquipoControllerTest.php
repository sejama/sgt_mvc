<?php

declare(strict_types=1);

namespace App\Tests\Unit\Controller;

use App\Controller\EquipoController;
use App\Entity\Categoria;
use App\Entity\Equipo;
use App\Entity\Torneo;
use App\Entity\Usuario;
use App\Exception\AppException;
use App\Manager\CategoriaManager;
use App\Manager\EquipoManager;
use App\Manager\JugadorManager;
use App\Manager\TorneoManager;
use PHPUnit\Framework\TestCase;
use Psr\Log\LoggerInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\User\UserInterface;

class EquipoControllerTest extends TestCase
{
    public function testIndexEquipoRenderizaTorneoCategoriaYEquipos(): void
    {
        $controller = new TestableEquipoController();
        $controller->testUser = (new Usuario())
            ->setUsername('admin')
            ->setPassword('hash')
            ->setRoles(['ROLE_ADMIN', 'ROLE_USER']);

        $torneoManager = $this->createMock(TorneoManager::class);
        $torneo = $this->createMock(Torneo::class);
        $torneoManager->expects($this->once())
            ->method('obtenerTorneo')
            ->with('ruta-test')
            ->willReturn($torneo);

        $categoriaManager = $this->createMock(CategoriaManager::class);
        $categoria = $this->createMock(Categoria::class);
        $categoriaManager->expects($this->once())
            ->method('obtenerCategoria')
            ->with(7)
            ->willReturn($categoria);

        $equipoManager = $this->createMock(EquipoManager::class);
        $equipos = [$this->createMock(Equipo::class)];
        $equipoManager->expects($this->once())
            ->method('obtenerEquiposPorCategoria')
            ->with($categoria)
            ->willReturn($equipos);

        $response = $controller->indexEquipo('ruta-test', 7, $torneoManager, $equipoManager, $categoriaManager);

        self::assertInstanceOf(Response::class, $response);
        self::assertSame('equipo/index.html.twig', $controller->lastTemplate);
        self::assertSame($torneo, $controller->lastParameters['torneo']);
        self::assertSame($categoria, $controller->lastParameters['categoria']);
        self::assertSame($equipos, $controller->lastParameters['equipos']);
    }

    public function testCrearEquipoPorPostYGuarda(): void
    {
        $controller = new TestableEquipoController();
        $controller->testUser = (new Usuario())
            ->setUsername('admin')
            ->setPassword('hash')
            ->setRoles(['ROLE_ADMIN', 'ROLE_USER']);

        $request = Request::create('/admin/torneo/ruta-test/categoria/7/equipo/nuevo', 'POST', [
            'nombre' => 'Equipo Nuevo',
            'nombreCorto' => 'EQN',
            'pais' => 'Argentina',
            'provincia' => 'Cba',
            'localidad' => 'Centro',
            'delegado' => [[
                'nombre' => 'Juan',
                'apellido' => 'Perez',
                'tipoDocumento' => 'DNI',
                'numeroDocumento' => '12345678',
                'email' => 'juan@example.com',
                'celular' => '2615551234',
            ]],
        ]);

        $categoriaManager = $this->createMock(CategoriaManager::class);
        $categoria = $this->createMock(Categoria::class);
        $categoriaManager->method('obtenerCategoria')->willReturn($categoria);

        $equipo = $this->createMock(Equipo::class);
        $equipo->method('getId')->willReturn(99);

        $equipoManager = $this->createMock(EquipoManager::class);
        $equipoManager->expects($this->once())
            ->method('crearEquipo')
            ->with($categoria, 'Equipo Nuevo', 'EQN', 'Argentina', 'Cba', 'Centro')
            ->willReturn($equipo);

        $jugadorManager = $this->createMock(JugadorManager::class);
        $jugadorManager->expects($this->once())
            ->method('crearJugador')
            ->with(
                $equipo,
                'Juan',
                'Perez',
                'DNI',
                '12345678',
                null,
                'Entrenador',
                true,
                'juan@example.com',
                '2615551234'
            );

        $logger = $this->createMock(LoggerInterface::class);
        $logger->expects($this->once())->method('info');

        $response = $controller->crearEquipo(
            'ruta-test',
            7,
            $request,
            $equipoManager,
            $jugadorManager,
            $categoriaManager,
            $logger
        );

        self::assertInstanceOf(RedirectResponse::class, $response);
        self::assertSame(['success', 'Equipo creado con éxito.'], $controller->lastFlash);
    }

    public function testCrearEquipoManejaAppException(): void
    {
        $controller = new TestableEquipoController();
        $controller->testUser = (new Usuario())
            ->setUsername('admin')
            ->setPassword('hash')
            ->setRoles(['ROLE_ADMIN', 'ROLE_USER']);

        $request = Request::create('/admin/torneo/ruta-test/categoria/7/equipo/nuevo', 'POST', [
            'nombre' => 'Equipo Nuevo',
            'nombreCorto' => 'EQN',
            'pais' => 'Argentina',
            'provincia' => 'Cba',
            'localidad' => 'Centro',
            'delegado' => [[
                'nombre' => 'Juan',
                'apellido' => 'Perez',
                'tipoDocumento' => 'DNI',
                'numeroDocumento' => '12345678',
                'email' => 'juan@example.com',
                'celular' => '2615551234',
            ]],
        ]);

        $categoriaManager = $this->createMock(CategoriaManager::class);
        $categoria = $this->createMock(Categoria::class);
        $categoriaManager->method('obtenerCategoria')->willReturn($categoria);

        $equipoManager = $this->createMock(EquipoManager::class);
        $equipoManager->method('crearEquipo')
            ->willThrowException(new AppException('Equipo duplicado'));

        $jugadorManager = $this->createMock(JugadorManager::class);
        $jugadorManager->expects($this->never())->method('crearJugador');

        $logger = $this->createMock(LoggerInterface::class);
        $logger->expects($this->once())->method('error');

        $response = $controller->crearEquipo(
            'ruta-test',
            7,
            $request,
            $equipoManager,
            $jugadorManager,
            $categoriaManager,
            $logger
        );

        self::assertInstanceOf(Response::class, $response);
        self::assertSame('equipo/nuevo.html.twig', $controller->lastTemplate);
        self::assertSame(['error', 'Equipo duplicado'], $controller->lastFlash);
    }

    public function testEditarEquipoPorPostYActualiza(): void
    {
        $controller = new TestableEquipoController();
        $controller->testUser = (new Usuario())
            ->setUsername('admin')
            ->setPassword('hash')
            ->setRoles(['ROLE_ADMIN', 'ROLE_USER']);

        $request = Request::create('/admin/torneo/ruta-test/categoria/7/equipo/99/editar', 'POST', [
            'nombre' => 'Equipo Editado',
            'nombreCorto' => 'EQE',
            'pais' => 'Argentina',
            'provincia' => 'Cba',
            'localidad' => 'Centro',
        ]);

        $equipo = $this->createMock(Equipo::class);
        $equipo->method('getId')->willReturn(99);

        $equipoManager = $this->createMock(EquipoManager::class);
        $equipoManager->expects($this->once())
            ->method('obtenerEquipo')
            ->with(99)
            ->willReturn($equipo);
        $equipoManager->expects($this->once())
            ->method('editarEquipo')
            ->with($equipo, 'Equipo Editado', 'EQE', 'Argentina', 'Cba', 'Centro');

        $logger = $this->createMock(LoggerInterface::class);
        $logger->expects($this->once())->method('info');

        $response = $controller->editarEquipo('ruta-test', 7, 99, $request, $equipoManager, $logger);

        self::assertInstanceOf(RedirectResponse::class, $response);
        self::assertSame(['success', 'Equipo editado con éxito.'], $controller->lastFlash);
    }

    public function testEliminarEquipoPorGetYRedirige(): void
    {
        $controller = new TestableEquipoController();
        $controller->testUser = (new Usuario())
            ->setUsername('admin')
            ->setPassword('hash')
            ->setRoles(['ROLE_ADMIN', 'ROLE_USER']);

        $equipo = $this->createMock(Equipo::class);
        $equipo->method('getId')->willReturn(99);

        $equipoManager = $this->createMock(EquipoManager::class);
        $equipoManager->expects($this->once())
            ->method('obtenerEquipo')
            ->with(99)
            ->willReturn($equipo);
        $equipoManager->expects($this->once())
            ->method('eliminarEquipo')
            ->with($equipo);

        $logger = $this->createMock(LoggerInterface::class);
        $logger->expects($this->once())->method('info');

        $response = $controller->eliminarEquipo('ruta-test', 7, 99, $equipoManager, $logger);

        self::assertInstanceOf(RedirectResponse::class, $response);
        self::assertSame(['success', 'Equipo eliminado con éxito.'], $controller->lastFlash);
    }

    public function testCambiarEstadoEquipoPorGetYRedirige(): void
    {
        $controller = new TestableEquipoController();
        $controller->testUser = (new Usuario())
            ->setUsername('admin')
            ->setPassword('hash')
            ->setRoles(['ROLE_ADMIN', 'ROLE_USER']);

        $equipo = $this->createMock(Equipo::class);
        $equipo->method('getId')->willReturn(55);

        $equipoManager = $this->createMock(EquipoManager::class);
        $equipoManager->expects($this->once())
            ->method('obtenerEquipo')
            ->with(55)
            ->willReturn($equipo);
        $equipoManager->expects($this->once())
            ->method('bajarEquipo')
            ->with($equipo);

        $logger = $this->createMock(LoggerInterface::class);
        $logger->expects($this->once())->method('info');

        $response = $controller->cambiarEstado('ruta-test', 7, 55, $equipoManager, $logger);

        self::assertInstanceOf(RedirectResponse::class, $response);
        self::assertSame(['success', 'Equipo dado de baja con éxito.'], $controller->lastFlash);
    }
}

class TestableEquipoController extends EquipoController
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
