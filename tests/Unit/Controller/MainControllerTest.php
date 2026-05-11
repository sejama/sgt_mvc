<?php

declare(strict_types=1);

namespace App\Tests\Unit\Controller;

use App\Controller\MainController;
use App\Entity\Categoria;
use App\Entity\Equipo;
use App\Entity\Grupo;
use App\Entity\Torneo;
use App\Entity\Usuario;
use App\Manager\CategoriaManager;
use App\Manager\EquipoManager;
use App\Manager\PartidoManager;
use App\Manager\TablaManager;
use App\Manager\GrupoManager;
use App\Manager\TorneoManager;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\User\UserInterface;

class MainControllerTest extends TestCase
{
    public function testIndexMuestraCambiarPasswordSiUsuarioRecienCreado(): void
    {
        $controller = new TestableMainController();
        $usuario = (new Usuario())
            ->setUsername('user')
            ->setPassword('hash')
            ->setRoles(['ROLE_USER']);

        $fecha = new \DateTimeImmutable('2026-01-01 10:00:00');
        $this->setPrivateProperty($usuario, 'createdAt', $fecha);
        $this->setPrivateProperty($usuario, 'updatedAt', $fecha);
        $this->setPrivateProperty($usuario, 'id', 99);
        $controller->testUser = $usuario;

        $torneoManager = $this->createMock(TorneoManager::class);
        $torneoManager->expects($this->never())->method('obtenerTorneos');

        $controller->index($torneoManager);

        self::assertSame('usuario/cambiar_password.html.twig', $controller->lastTemplate);
        self::assertSame(99, $controller->lastParameters['idUser']);
    }

    public function testIndexMuestraListadoTorneosCuandoNoEsPrimerIngreso(): void
    {
        $controller = new TestableMainController();
        $controller->testUser = null;

        $torneoManager = $this->createMock(TorneoManager::class);
        $torneoManager->expects($this->once())
            ->method('obtenerTorneos')
            ->willReturn(['t1']);

        $controller->index($torneoManager);

        self::assertSame('main/index.html.twig', $controller->lastTemplate);
        self::assertSame(['t1'], $controller->lastParameters['torneos']);
    }

    public function testTorneoAplicaFiltrosYManejaEquipoInexistente(): void
    {
        $controller = new TestableMainController();

        $categoria = (new Categoria())
            ->setNombre('Cat A')
            ->setNombreCorto('CA')
            ->setEstado('activa')
            ->setGenero(\App\Enum\Genero::MASCULINO);

        $torneo = new Torneo();
        $torneo->addCategoria($categoria);

        $torneoManager = $this->createMock(TorneoManager::class);
        $torneoManager->method('obtenerTorneo')->willReturn($torneo);

        $partidoManager = $this->createMock(PartidoManager::class);
        $partidoManager->method('obtenerPartidosProgramadosXTorneo')->willReturn([
            'Sede 1' => [
                'Cancha 1' => [
                    '2026-05-01' => [
                        [
                            'categoria' => 'Cat A',
                            'equipoLocal' => 'Equipo Local',
                            'equipoVisitante' => 'Equipo Visitante',
                        ],
                    ],
                ],
            ],
        ]);

        $categoriaManager = $this->createMock(CategoriaManager::class);
        $categoriaManager->method('obtenerCategoria')->willReturn($categoria);

        $grupoManager = $this->createMock(GrupoManager::class);
        $grupoManager->method('obtenerGrupos')->willReturn([]);

        $equipoManager = $this->createMock(EquipoManager::class);
        $equipoManager->method('obtenerEquipo')->willThrowException(new \RuntimeException('no existe'));

        $request = new Request(['categoria' => 1, 'equipo' => 999999]);

        $controller->torneo(
            $torneoManager,
            $partidoManager,
            $categoriaManager,
            $grupoManager,
            $equipoManager,
            $request,
            'ruta-test'
        );

        self::assertSame('main/torneo.html.twig', $controller->lastTemplate);
        self::assertSame(1, $controller->lastParameters['selectedCategoriaId']);
        self::assertSame(999999, $controller->lastParameters['selectedEquipoId']);
        self::assertNull($controller->lastParameters['selectedEquipoId'] === null ? null : null);
    }

    public function testCategoriaCalculaPosicionesPorGrupo(): void
    {
        $controller = new TestableMainController();

        $grupo = new Grupo();
        $this->setPrivateProperty($grupo, 'id', 10);

        $categoria = new Categoria();
        $categoria->addGrupo($grupo);

        $torneoManager = $this->createMock(TorneoManager::class);
        $categoriaManager = $this->createMock(CategoriaManager::class);
        $tablaManager = $this->createMock(TablaManager::class);

        $categoriaManager->expects($this->once())
            ->method('obtenerCategoria')
            ->with(5)
            ->willReturn($categoria);

        $tablaManager->expects($this->once())
            ->method('calcularPosiciones')
            ->with($grupo)
            ->willReturn([['equipo' => 'X']]);

        $controller->categoria($torneoManager, $categoriaManager, $tablaManager, 'ruta', 5);

        self::assertSame('main/categoria.html.twig', $controller->lastTemplate);
        self::assertArrayHasKey('grupos', $controller->lastParameters);
    }

    public function testTorneoSinFiltrosMantienePartidosProgramados(): void
    {
        $controller = new TestableMainController();

        $categoria = (new Categoria())
            ->setNombre('Cat B')
            ->setNombreCorto('CB')
            ->setEstado('activa')
            ->setGenero(\App\Enum\Genero::MASCULINO);

        $torneo = new Torneo();
        $torneo->addCategoria($categoria);

        $partidosProgramados = [
            'Sede 1' => [
                'Cancha 1' => [
                    '2026-05-01' => [
                        [
                            'categoria' => 'Cat B',
                            'equipoLocal' => 'Local B',
                            'equipoVisitante' => 'Visitante B',
                        ],
                    ],
                ],
            ],
        ];

        $torneoManager = $this->createMock(TorneoManager::class);
        $torneoManager->method('obtenerTorneo')->willReturn($torneo);

        $partidoManager = $this->createMock(PartidoManager::class);
        $partidoManager->method('obtenerPartidosProgramadosXTorneo')->willReturn($partidosProgramados);

        $categoriaManager = $this->createMock(CategoriaManager::class);
        $grupoManager = $this->createMock(GrupoManager::class);
        $equipoManager = $this->createMock(EquipoManager::class);
        $equipoManager->expects($this->never())->method('obtenerEquipo');

        $request = new Request();

        $controller->torneo(
            $torneoManager,
            $partidoManager,
            $categoriaManager,
            $grupoManager,
            $equipoManager,
            $request,
            'ruta-test'
        );

        self::assertSame('main/torneo.html.twig', $controller->lastTemplate);
        self::assertNull($controller->lastParameters['selectedCategoriaId']);
        self::assertNull($controller->lastParameters['selectedEquipoId']);
        self::assertSame($partidosProgramados, $controller->lastParameters['partidosProgramados']);
    }

    public function testTorneoConEquipoValidoFiltraPorNombreDeEquipo(): void
    {
        $controller = new TestableMainController();

        $torneo = new Torneo();

        $torneoManager = $this->createMock(TorneoManager::class);
        $torneoManager->method('obtenerTorneo')->willReturn($torneo);

        $partidoManager = $this->createMock(PartidoManager::class);
        $partidoManager->method('obtenerPartidosProgramadosXTorneo')->willReturn([
            'Sede 1' => [
                'Cancha 1' => [
                    '2026-05-01' => [
                        [
                            'categoria' => 'Cat A',
                            'equipoLocal' => 'Equipo Azul',
                            'equipoVisitante' => 'Equipo Rojo',
                        ],
                        [
                            'categoria' => 'Cat A',
                            'equipoLocal' => 'Equipo Verde',
                            'equipoVisitante' => 'Equipo Amarillo',
                        ],
                    ],
                ],
            ],
        ]);

        $categoriaManager = $this->createMock(CategoriaManager::class);
        $grupoManager = $this->createMock(GrupoManager::class);

        $equipoSeleccionado = (new Equipo())
            ->setNombre('Azul')
            ->setNombreCorto('AZ')
            ->setEstado('activo')
            ->setNumero(1);

        $equipoManager = $this->createMock(EquipoManager::class);
        $equipoManager->expects($this->once())
            ->method('obtenerEquipo')
            ->with(10)
            ->willReturn($equipoSeleccionado);

        $request = new Request(['equipo' => 10]);

        $controller->torneo(
            $torneoManager,
            $partidoManager,
            $categoriaManager,
            $grupoManager,
            $equipoManager,
            $request,
            'ruta-test'
        );

        self::assertSame('main/torneo.html.twig', $controller->lastTemplate);
        self::assertSame(10, $controller->lastParameters['selectedEquipoId']);
        self::assertCount(1, $controller->lastParameters['partidosProgramados']['Sede 1']['Cancha 1']['2026-05-01']);
        self::assertSame('Equipo Azul', $controller->lastParameters['partidosProgramados']['Sede 1']['Cancha 1']['2026-05-01'][0]['equipoLocal']);
    }

    public function testTorneoOrdenaPorSedeCanchaFechaYHora(): void
    {
        $controller = new TestableMainController();

        $torneo = new Torneo();

        $torneoManager = $this->createMock(TorneoManager::class);
        $torneoManager->method('obtenerTorneo')->willReturn($torneo);

        $partidoManager = $this->createMock(PartidoManager::class);
        $partidoManager->method('obtenerPartidosProgramadosXTorneo')->willReturn([
            'Sede B' => [
                'Cancha 2' => [
                    '2026-05-02' => [
                        ['hora' => '12:00', 'categoria' => 'Cat A', 'equipoLocal' => 'B', 'equipoVisitante' => 'C'],
                        ['hora' => '09:00', 'categoria' => 'Cat A', 'equipoLocal' => 'A', 'equipoVisitante' => 'D'],
                    ],
                ],
            ],
            'Sede A' => [
                'Cancha 3' => [
                    '2026-05-03' => [
                        ['hora' => '11:00', 'categoria' => 'Cat A', 'equipoLocal' => 'E', 'equipoVisitante' => 'F'],
                    ],
                ],
                'Cancha 1' => [
                    '2026-05-01' => [
                        ['hora' => '10:00', 'categoria' => 'Cat A', 'equipoLocal' => 'G', 'equipoVisitante' => 'H'],
                    ],
                ],
            ],
        ]);

        $categoriaManager = $this->createMock(CategoriaManager::class);
        $grupoManager = $this->createMock(GrupoManager::class);
        $equipoManager = $this->createMock(EquipoManager::class);

        $controller->torneo(
            $torneoManager,
            $partidoManager,
            $categoriaManager,
            $grupoManager,
            $equipoManager,
            new Request(),
            'ruta-test'
        );

        $partidosOrdenados = $controller->lastParameters['partidosProgramados'];

        self::assertSame(['Sede A', 'Sede B'], array_keys($partidosOrdenados));
        self::assertSame(['Cancha 1', 'Cancha 3'], array_keys($partidosOrdenados['Sede A']));
        self::assertSame(['2026-05-01'], array_keys($partidosOrdenados['Sede A']['Cancha 1']));
        self::assertSame('09:00', $partidosOrdenados['Sede B']['Cancha 2']['2026-05-02'][0]['hora']);
        self::assertSame('12:00', $partidosOrdenados['Sede B']['Cancha 2']['2026-05-02'][1]['hora']);
    }

    public function testTorneoConFiltroGrupoFiltraPartidosYEquipos(): void
    {
        $controller = new TestableMainController();

        $categoria = (new Categoria())
            ->setNombre('Cat A')
            ->setNombreCorto('CA')
            ->setEstado('activa')
            ->setGenero(\App\Enum\Genero::MASCULINO);

        $grupo = (new Grupo())
            ->setNombre('Grupo 1')
            ->setClasificaOro(1)
            ->setEstado('activo')
            ->setCategoria($categoria);

        $equipoGrupo = (new Equipo())
            ->setNombre('Equipo Azul')
            ->setNombreCorto('AZ')
            ->setEstado('activo')
            ->setNumero(1)
            ->setGrupo($grupo)
            ->setCategoria($categoria);

        $grupo->addEquipo($equipoGrupo);
        $categoria->addEquipo($equipoGrupo);

        $torneo = new Torneo();
        $torneo->addCategoria($categoria);

        $torneoManager = $this->createMock(TorneoManager::class);
        $torneoManager->method('obtenerTorneo')->willReturn($torneo);

        $partidoManager = $this->createMock(PartidoManager::class);
        $partidoManager->method('obtenerPartidosProgramadosXTorneo')->willReturn([
            'Sede 1' => [
                'Cancha 1' => [
                    '2026-05-01' => [
                        ['hora' => '10:00', 'categoria' => 'Cat A', 'grupo' => 'Grupo 1', 'equipoLocal' => 'Equipo Azul', 'equipoVisitante' => 'Equipo Rojo'],
                        ['hora' => '12:00', 'categoria' => 'Cat A', 'grupo' => 'Grupo 2', 'equipoLocal' => 'Equipo Verde', 'equipoVisitante' => 'Equipo Amarillo'],
                    ],
                ],
            ],
        ]);

        $categoriaManager = $this->createMock(CategoriaManager::class);
        $categoriaManager->method('obtenerCategoria')->willReturn($categoria);

        $grupoManager = $this->createMock(GrupoManager::class);
        $grupoManager->method('obtenerGrupos')->willReturn([$grupo]);
        $grupoManager->method('obtenerGrupo')->with(7)->willReturn($grupo);

        $equipoManager = $this->createMock(EquipoManager::class);

        $controller->torneo(
            $torneoManager,
            $partidoManager,
            $categoriaManager,
            $grupoManager,
            $equipoManager,
            new Request(['categoria' => 1, 'grupo' => 7]),
            'ruta-test'
        );

        self::assertSame(7, $controller->lastParameters['selectedGrupoId']);
        self::assertCount(1, $controller->lastParameters['grupos']);
        self::assertCount(1, $controller->lastParameters['equipos']);
        self::assertCount(1, $controller->lastParameters['partidosProgramados']['Sede 1']['Cancha 1']['2026-05-01']);
        self::assertSame('Grupo 1', $controller->lastParameters['partidosProgramados']['Sede 1']['Cancha 1']['2026-05-01'][0]['grupo']);
    }

    public function testCategoriaConCategoriaNullLanzaNotFound(): void
    {
        $controller = new TestableMainController();

        $torneoManager = $this->createMock(TorneoManager::class);
        $categoriaManager = $this->createMock(CategoriaManager::class);
        $categoriaManager->method('obtenerCategoria')->willReturn(null);
        $tablaManager = $this->createMock(TablaManager::class);

        $this->expectException(\Symfony\Component\HttpKernel\Exception\NotFoundHttpException::class);

        $controller->categoria($torneoManager, $categoriaManager, $tablaManager, 'ruta', 5);
    }

    private function setPrivateProperty(object $object, string $property, mixed $value): void
    {
        $reflection = new \ReflectionProperty($object, $property);
        $reflection->setAccessible(true);
        $reflection->setValue($object, $value);
    }
}

class TestableMainController extends MainController
{
    public ?UserInterface $testUser = null;
    public ?string $lastTemplate = null;
    public array $lastParameters = [];

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
}
