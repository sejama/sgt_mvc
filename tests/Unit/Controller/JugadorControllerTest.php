<?php

declare(strict_types=1);

namespace App\Tests\Unit\Controller;

use App\Controller\JugadorController;
use App\Entity\Equipo;
use App\Entity\Jugador;
use App\Entity\Usuario;
use App\Manager\EquipoManager;
use App\Manager\JugadorManager;
use PHPUnit\Framework\TestCase;
use Psr\Log\LoggerInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\User\UserInterface;

class JugadorControllerTest extends TestCase
{
    public function testIndexRendersJugadores(): void
    {
        $controller = new TestableJugadorController();
        $controller->testUser = (new Usuario())
            ->setUsername('admin')
            ->setPassword('hash')
            ->setRoles(['ROLE_ADMIN', 'ROLE_USER']);

        $equipoManager = $this->createMock(EquipoManager::class);
        $equipo = $this->createMock(Equipo::class);
        $equipoManager->expects($this->once())
            ->method('obtenerEquipo')
            ->with(11)
            ->willReturn($equipo);

        $jugadorManager = $this->createMock(JugadorManager::class);
        $jugadores = [$this->createMock(Jugador::class)];
        $jugadorManager->expects($this->once())
            ->method('obtenerJugadoresPorEquipo')
            ->with($equipo)
            ->willReturn($jugadores);

        $response = $controller->index('ruta-test', 7, 11, $equipoManager, $jugadorManager);

        self::assertInstanceOf(Response::class, $response);
        self::assertSame('jugador/index.html.twig', $controller->lastTemplate);
        self::assertSame($equipo, $controller->lastParameters['equipo']);
        self::assertSame($jugadores, $controller->lastParameters['jugadores']);
    }

    public function testCrearJugadorPorPostYRedirige(): void
    {
        $controller = new TestableJugadorController();
        $controller->testUser = (new Usuario())
            ->setUsername('admin')
            ->setPassword('hash')
            ->setRoles(['ROLE_ADMIN', 'ROLE_USER']);

        $request = Request::create('/admin/torneo/ruta-test/categoria/7/equipo/11/jugador/nuevo', 'POST', [
            'nombre' => 'Juan',
            'apellido' => 'Perez',
            'nacimiento' => '2000-02-01',
            'tipoPersona' => 'Jugador',
            'tipoDocumento' => 'DNI',
            'numeroDocumento' => '12345678',
            'email' => 'juan@example.com',
            'celular' => '2615551234',
        ]);

        $equipo = $this->createMock(Equipo::class);
        $equipo->method('getId')->willReturn(11);

        $equipoManager = $this->createMock(EquipoManager::class);
        $equipoManager->expects($this->once())
            ->method('obtenerEquipo')
            ->with(11)
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
                '2000-02-01',
                'Jugador',
                false,
                'juan@example.com',
                '2615551234'
            );

        $logger = $this->createMock(LoggerInterface::class);
        $logger->expects($this->once())->method('info');

        $response = $controller->crearJugador('ruta-test', 7, 11, $request, $equipoManager, $jugadorManager, $logger);

        self::assertInstanceOf(RedirectResponse::class, $response);
        self::assertSame(['success', 'Jugador editado correctamente'], $controller->lastFlash);
    }

    public function testEditarJugadorPorPostYRedirige(): void
    {
        $controller = new TestableJugadorController();
        $controller->testUser = (new Usuario())
            ->setUsername('admin')
            ->setPassword('hash')
            ->setRoles(['ROLE_ADMIN', 'ROLE_USER']);

        $request = Request::create('/admin/torneo/ruta-test/categoria/7/equipo/11/jugador/99/editar', 'POST', [
            'nombre' => 'Juan Editado',
            'apellido' => 'Perez Editado',
            'nacimiento' => '2000-02-01',
            'tipoPersona' => 'Jugador',
            'tipoDocumento' => 'DNI',
            'numeroDocumento' => '87654321',
            'email' => 'juan.editado@example.com',
            'celular' => '2615559999',
        ]);

        $equipo = $this->createMock(Equipo::class);
        $equipo->method('getId')->willReturn(11);

        $jugador = $this->createMock(Jugador::class);
        $jugador->method('isResponsable')->willReturn(true);
        $jugador->method('getId')->willReturn(99);

        $equipoManager = $this->createMock(EquipoManager::class);
        $equipoManager->method('obtenerEquipo')->with(11)->willReturn($equipo);

        $jugadorManager = $this->createMock(JugadorManager::class);
        $jugadorManager->method('obtenerJugador')->with(99)->willReturn($jugador);
        $jugadorManager->expects($this->once())
            ->method('editarJugador')
            ->with(
                $jugador,
                'Juan Editado',
                'Perez Editado',
                'DNI',
                '87654321',
                '2000-02-01',
                'Jugador',
                true,
                'juan.editado@example.com',
                '2615559999'
            );

        $logger = $this->createMock(LoggerInterface::class);
        $logger->expects($this->once())->method('info');

        $response = $controller->editarJugador('ruta-test', 7, 11, 99, $request, $equipoManager, $jugadorManager, $logger);

        self::assertInstanceOf(RedirectResponse::class, $response);
        self::assertSame(['success', 'Jugador editado correctamente'], $controller->lastFlash);
    }

    public function testEliminarJugadorPorGetYRedirige(): void
    {
        $controller = new TestableJugadorController();
        $controller->testUser = (new Usuario())
            ->setUsername('admin')
            ->setPassword('hash')
            ->setRoles(['ROLE_ADMIN', 'ROLE_USER']);

        $equipo = $this->createMock(Equipo::class);
        $equipo->method('getId')->willReturn(11);

        $jugador = $this->createMock(Jugador::class);
        $jugador->method('getId')->willReturn(99);
        $jugador->method('getNombre')->willReturn('Juan');
        $jugador->method('getApellido')->willReturn('Perez');

        $equipoManager = $this->createMock(EquipoManager::class);
        $equipoManager->method('obtenerEquipo')->with(11)->willReturn($equipo);

        $jugadorManager = $this->createMock(JugadorManager::class);
        $jugadorManager->method('obtenerJugador')->with(99)->willReturn($jugador);
        $jugadorManager->expects($this->once())
            ->method('eliminarJugador')
            ->with($jugador);

        $request = Request::create('/admin/torneo/ruta-test/categoria/7/equipo/11/jugador/99/eliminar', 'POST', [
            '_token' => 'test-token-delete_jugador_99',
        ]);

        $logger = $this->createMock(LoggerInterface::class);
        $logger->expects($this->once())->method('info');

        $response = $controller->eliminarJugador('ruta-test', 7, 11, 99, $request, $equipoManager, $jugadorManager, $logger);

        self::assertInstanceOf(RedirectResponse::class, $response);
        self::assertSame(['success', 'Jugador eliminado correctamente'], $controller->lastFlash);
    }
}

class TestableJugadorController extends JugadorController
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
