<?php

declare(strict_types=1);

namespace App\Tests\Unit\Controller;

use App\Controller\GrupoController;
use App\Entity\Categoria;
use App\Entity\Grupo;
use App\Entity\Torneo;
use App\Entity\Usuario;
use App\Exception\AppException;
use App\Manager\CategoriaManager;
use App\Manager\GrupoManager;
use App\Manager\PartidoManager;
use App\Manager\TablaManager;
use App\Manager\TorneoManager;
use Doctrine\Common\Collections\ArrayCollection;
use PHPUnit\Framework\TestCase;
use Psr\Log\LoggerInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\User\UserInterface;

class GrupoControllerTest extends TestCase
{
    public function testGruposIndexRenderizaCategoriaYPartidos(): void
    {
        $controller = new TestableGrupoController();
        $controller->testUser = (new Usuario())
            ->setUsername('admin')
            ->setPassword('hash')
            ->setRoles(['ROLE_ADMIN', 'ROLE_USER']);

        $torneo = $this->createMock(Torneo::class);
        $torneoManager = $this->createMock(TorneoManager::class);
        $torneoManager->expects($this->once())
            ->method('obtenerTorneo')
            ->with('ruta-test')
            ->willReturn($torneo);

        $grupo = $this->createMock(Grupo::class);
        $grupo->method('getId')->willReturn(5);

        $categoria = $this->createMock(Categoria::class);
        $categoria->method('getGrupos')->willReturn(new ArrayCollection([$grupo]));
        $categoria->method('getNombre')->willReturn('Categoria A');

        $categoriaManager = $this->createMock(CategoriaManager::class);
        $categoriaManager->expects($this->once())
            ->method('obtenerCategoria')
            ->with(8)
            ->willReturn($categoria);

        $tablaManager = $this->createMock(TablaManager::class);
        $tablaManager->expects($this->once())
            ->method('calcularPosiciones')
            ->with($grupo)
            ->willReturn(['puntos' => 3]);

        $partidoManager = $this->createMock(PartidoManager::class);
        $partidoManager->expects($this->once())
            ->method('obtenerPartidosXCategoriaClasificatorio')
            ->with($categoria)
            ->willReturn([]);
        $partidoManager->expects($this->once())
            ->method('obtenerPartidosXCategoriaEliminatoriaPostClasificatorio')
            ->with($categoria)
            ->willReturn(['oro' => [], 'plata' => [], 'bronce' => []]);

        $response = $controller->gruposIndex('ruta-test', 8, $torneoManager, $categoriaManager, $tablaManager, $partidoManager);

        self::assertInstanceOf(Response::class, $response);
        self::assertSame('grupo/index.html.twig', $controller->lastTemplate);
        self::assertSame($torneo, $controller->lastParameters['torneo']);
        self::assertSame($categoria, $controller->lastParameters['categoria']);
        self::assertArrayHasKey(5, $controller->lastParameters['grupos']);
    }

    public function testCrearGrupoPorPostYRedirige(): void
    {
        $controller = new TestableGrupoController();
        $controller->testUser = (new Usuario())
            ->setUsername('admin')
            ->setPassword('hash')
            ->setRoles(['ROLE_ADMIN', 'ROLE_USER']);

        $request = Request::create('/admin/torneo/ruta-test/categoria/8/grupo/crear', 'POST', [
            'cantidadGrupos' => '1',
            'grupos' => [[
                'nombre' => 'Grupo A',
                'cantidadEquipo' => '2',
                'clasificaOro' => '1',
                'clasificaPlata' => '0',
                'clasificaBronce' => '0',
            ]],
        ]);

        $torneo = $this->createMock(Torneo::class);
        $torneoManager = $this->createMock(TorneoManager::class);
        $torneoManager->method('obtenerTorneo')->willReturn($torneo);

        $categoria = $this->createMock(Categoria::class);
        $categoria->method('getId')->willReturn(8);
        $categoria->method('getNombre')->willReturn('Categoria A');

        $categoriaManager = $this->createMock(CategoriaManager::class);
        $categoriaManager->method('obtenerCategoria')->willReturn($categoria);

        $grupoManager = $this->createMock(GrupoManager::class);
        $grupoManager->expects($this->once())
            ->method('crearGrupos')
            ->with([
                [
                    'nombre' => 'Grupo A',
                    'categoria' => 8,
                    'cantidad' => 2,
                    'clasificaOro' => 1,
                    'clasificaPlata' => null,
                    'clasificaBronce' => null,
                ],
            ]);

        $logger = $this->createMock(LoggerInterface::class);
        $logger->expects($this->once())->method('info');

        $response = $controller->crearGrupo($request, $grupoManager, $torneoManager, $categoriaManager, 'ruta-test', 8, $logger);

        self::assertInstanceOf(RedirectResponse::class, $response);
        self::assertSame(['success', 'Grupo creado con éxito.'], $controller->lastFlash);
        self::assertSame('/admin_equipo_index', $response->headers->get('Location'));
    }

    public function testCrearGrupoConCantidadInconsistenteRedirigeAFormulario(): void
    {
        $controller = new TestableGrupoController();
        $controller->testUser = (new Usuario())
            ->setUsername('admin')
            ->setPassword('hash')
            ->setRoles(['ROLE_ADMIN', 'ROLE_USER']);

        $request = Request::create('/admin/torneo/ruta-test/categoria/8/grupo/crear', 'POST', [
            'cantidadGrupos' => '2',
            'grupos' => [[
                'nombre' => 'Grupo A',
                'cantidadEquipo' => '2',
                'clasificaOro' => '1',
                'clasificaPlata' => '0',
                'clasificaBronce' => '0',
            ]],
        ]);

        $torneo = $this->createMock(Torneo::class);
        $torneoManager = $this->createMock(TorneoManager::class);
        $torneoManager->method('obtenerTorneo')->willReturn($torneo);

        $categoria = $this->createMock(Categoria::class);
        $categoria->method('getNombre')->willReturn('Categoria A');

        $categoriaManager = $this->createMock(CategoriaManager::class);
        $categoriaManager->method('obtenerCategoria')->willReturn($categoria);

        $grupoManager = $this->createMock(GrupoManager::class);
        $grupoManager->expects($this->never())->method('crearGrupos');

        $logger = $this->createMock(LoggerInterface::class);
        $logger->expects($this->never())->method('info');

        $response = $controller->crearGrupo($request, $grupoManager, $torneoManager, $categoriaManager, 'ruta-test', 8, $logger);

        self::assertInstanceOf(RedirectResponse::class, $response);
        self::assertSame(['danger', 'La cantidad de grupos no coincide con la cantidad ingresada.'], $controller->lastFlash);
        self::assertSame('/admin_grupo_crear', $response->headers->get('Location'));
    }

    public function testArmarPlayOffManejaAppException(): void
    {
        $controller = new TestableGrupoController();
        $controller->testUser = (new Usuario())
            ->setUsername('admin')
            ->setPassword('hash')
            ->setRoles(['ROLE_ADMIN', 'ROLE_USER']);

        $torneo = $this->createMock(Torneo::class);
        $torneoManager = $this->createMock(TorneoManager::class);
        $torneoManager->method('obtenerTorneo')->willReturn($torneo);

        $grupo = $this->createMock(Grupo::class);
        $grupo->method('getId')->willReturn(5);

        $categoria = $this->createMock(Categoria::class);
        $categoria->method('getGrupos')->willReturn(new ArrayCollection([$grupo]));
        $categoria->method('getNombre')->willReturn('Categoria A');

        $categoriaManager = $this->createMock(CategoriaManager::class);
        $categoriaManager->method('obtenerCategoria')->willReturn($categoria);
        $categoriaManager->expects($this->once())
            ->method('armarPlayOff')
            ->with($categoria)
            ->willThrowException(new AppException('No se pudo armar'));

        $tablaManager = $this->createMock(TablaManager::class);
        $tablaManager->expects($this->once())
            ->method('calcularPosiciones')
            ->with($grupo)
            ->willReturn(['puntos' => 3]);

        $logger = $this->createMock(LoggerInterface::class);
        $logger->expects($this->once())->method('error');

        $response = $controller->armarPlayOff('ruta-test', 8, $torneoManager, $categoriaManager, $tablaManager, $logger);

        self::assertInstanceOf(RedirectResponse::class, $response);
        self::assertSame(['danger', 'una app exception'], $controller->lastFlash);
        self::assertSame('/admin_grupo_index', $response->headers->get('Location'));
    }

    public function testIntercambiarEquiposGetRenderizaFormulario(): void
    {
        $controller = new TestableGrupoController();
        $controller->testUser = (new Usuario())
            ->setUsername('admin')
            ->setPassword('hash')
            ->setRoles(['ROLE_ADMIN', 'ROLE_USER']);

        $torneo = $this->createMock(Torneo::class);
        $torneoManager = $this->createMock(TorneoManager::class);
        $torneoManager->expects($this->once())
            ->method('obtenerTorneo')
            ->with('ruta-test')
            ->willReturn($torneo);

        $categoria = $this->createMock(Categoria::class);
        $categoriaManager = $this->createMock(CategoriaManager::class);
        $categoriaManager->expects($this->once())
            ->method('obtenerCategoria')
            ->with(8)
            ->willReturn($categoria);

        $grupoManager = $this->createMock(GrupoManager::class);
        $grupoManager->expects($this->once())
            ->method('obtenerEquiposDeCategoriaConGrupo')
            ->with($categoria)
            ->willReturn([]);

        $logger = $this->createMock(LoggerInterface::class);

        $request = Request::create('/admin/torneo/ruta-test/categoria/8/grupo/intercambiar-equipos', 'GET');

        $response = $controller->intercambiarEquipos('ruta-test', 8, $request, $torneoManager, $categoriaManager, $grupoManager, $logger);

        self::assertInstanceOf(Response::class, $response);
        self::assertSame('grupo/intercambiar.html.twig', $controller->lastTemplate);
        self::assertSame($torneo, $controller->lastParameters['torneo']);
        self::assertSame($categoria, $controller->lastParameters['categoria']);
        self::assertSame([], $controller->lastParameters['equipos']);
    }

    public function testIntercambiarEquiposPostExitosoRedirigeConFlashSuccess(): void
    {
        $controller = new TestableGrupoController();
        $controller->testUser = (new Usuario())
            ->setUsername('admin')
            ->setPassword('hash')
            ->setRoles(['ROLE_ADMIN', 'ROLE_USER']);

        $request = Request::create('/admin/torneo/ruta-test/categoria/8/grupo/intercambiar-equipos', 'POST', [
            'equipoOrigenId' => '101',
            'equipoDestinoId' => '102',
        ]);

        $torneoManager = $this->createMock(TorneoManager::class);
        $torneoManager->method('obtenerTorneo')->willReturn($this->createMock(Torneo::class));

        $categoria = $this->createMock(Categoria::class);
        $categoria->method('getId')->willReturn(8);

        $categoriaManager = $this->createMock(CategoriaManager::class);
        $categoriaManager->method('obtenerCategoria')->willReturn($categoria);

        $grupoManager = $this->createMock(GrupoManager::class);
        $grupoManager->expects($this->once())
            ->method('intercambiarEquiposEntreGrupos')
            ->with($categoria, 101, 102);

        $logger = $this->createMock(LoggerInterface::class);
        $logger->expects($this->once())->method('info');

        $response = $controller->intercambiarEquipos('ruta-test', 8, $request, $torneoManager, $categoriaManager, $grupoManager, $logger);

        self::assertInstanceOf(RedirectResponse::class, $response);
        self::assertSame(['success', 'Equipos intercambiados correctamente.'], $controller->lastFlash);
        self::assertSame('/admin_grupo_intercambiar_equipos', $response->headers->get('Location'));
    }

    public function testIntercambiarEquiposPostConAppExceptionRedirigeConFlashError(): void
    {
        $controller = new TestableGrupoController();
        $controller->testUser = (new Usuario())
            ->setUsername('admin')
            ->setPassword('hash')
            ->setRoles(['ROLE_ADMIN', 'ROLE_USER']);

        $request = Request::create('/admin/torneo/ruta-test/categoria/8/grupo/intercambiar-equipos', 'POST', [
            'equipoOrigenId' => '101',
            'equipoDestinoId' => '102',
        ]);

        $torneoManager = $this->createMock(TorneoManager::class);
        $torneoManager->method('obtenerTorneo')->willReturn($this->createMock(Torneo::class));

        $categoria = $this->createMock(Categoria::class);
        $categoriaManager = $this->createMock(CategoriaManager::class);
        $categoriaManager->method('obtenerCategoria')->willReturn($categoria);

        $grupoManager = $this->createMock(GrupoManager::class);
        $grupoManager->expects($this->once())
            ->method('intercambiarEquiposEntreGrupos')
            ->with($categoria, 101, 102)
            ->willThrowException(new AppException('No permitido'));

        $logger = $this->createMock(LoggerInterface::class);
        $logger->expects($this->once())->method('error');

        $response = $controller->intercambiarEquipos('ruta-test', 8, $request, $torneoManager, $categoriaManager, $grupoManager, $logger);

        self::assertInstanceOf(RedirectResponse::class, $response);
        self::assertSame(['error', 'No permitido'], $controller->lastFlash);
        self::assertSame('/admin_grupo_intercambiar_equipos', $response->headers->get('Location'));
    }
}

class TestableGrupoController extends GrupoController
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
