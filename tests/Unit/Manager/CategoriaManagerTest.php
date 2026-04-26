<?php

namespace App\Tests\Unit\Manager;

use App\Entity\Categoria;
use App\Entity\Torneo;
use App\Manager\CategoriaManager;
use App\Manager\TablaManager;
use App\Manager\ValidadorManager;
use App\Repository\CategoriaRepository;
use App\Repository\PartidoRepository;
use Doctrine\Common\Collections\ArrayCollection;
use PHPUnit\Framework\TestCase;

use function PHPUnit\Framework\exactly;

class CategoriaManagerTest extends TestCase
{
    public function testObtenerCategorias(): void
    {
        $categoriaRepository = $this->createMock(CategoriaRepository::class);
        $partidoRepository = $this->createMock(PartidoRepository::class);
        $validadorManager = $this->createMock(ValidadorManager::class);
        $tablaManager = $this->createMock(TablaManager::class);

        $categoriaManager = new CategoriaManager(
            $categoriaRepository,
            $partidoRepository,
            $validadorManager,
            $tablaManager
        );

        $this->assertIsArray($categoriaManager->obtenerCategorias());
        $this->assertEquals([], $categoriaManager->obtenerCategorias());
    }

    public function testObtenerCategoriaOk(): void
    {
        $categoriaRepository = $this->createMock(CategoriaRepository::class);
        $partidoRepository = $this->createMock(PartidoRepository::class);
        $validadorManager = $this->createMock(ValidadorManager::class);
        $tablaManager = $this->createMock(TablaManager::class);

        $categoriaManager = new CategoriaManager(
            $categoriaRepository,
            $partidoRepository,
            $validadorManager,
            $tablaManager
        );

        $categoria = $this->createMock(Categoria::class);
        $categoriaRepository->method('find')->willReturn($categoria);

        $this->assertEquals($categoria, $categoriaManager->obtenerCategoria(1));
    }

    public function testObtenerCategoriaNoEncontrada(): void
    {
        $categoriaRepository = $this->createMock(CategoriaRepository::class);
        $partidoRepository = $this->createMock(PartidoRepository::class);
        $validadorManager = $this->createMock(ValidadorManager::class);
        $tablaManager = $this->createMock(TablaManager::class);

        $categoriaManager = new CategoriaManager(
            $categoriaRepository,
            $partidoRepository,
            $validadorManager,
            $tablaManager
        );

        $this->assertNull($categoriaManager->obtenerCategoria(1));
    }

    public function testObtenerCategoriasPorTorneo(): void
    {
        $categoriaRepository = $this->createMock(CategoriaRepository::class);
        $partidoRepository = $this->createMock(PartidoRepository::class);
        $validadorManager = $this->createMock(ValidadorManager::class);
        $tablaManager = $this->createMock(TablaManager::class);

        $categoriaManager = new CategoriaManager(
            $categoriaRepository,
            $partidoRepository,
            $validadorManager,
            $tablaManager
        );

        $torneo = new Torneo();
        $categoriaRepository->method('findBy')
            ->with(['torneo' => $torneo])
            ->willReturn([$this->createMock(Categoria::class)]);

        $this->assertIsArray($categoriaManager->obtenerCategoriasPorTorneo($torneo));
    }

    public function testCrearCategoriaOk(): void
    {
        $categoriaRepository = $this->createMock(CategoriaRepository::class);
        $partidoRepository = $this->createMock(PartidoRepository::class);
        $validadorManager = $this->createMock(ValidadorManager::class);
        $tablaManager = $this->createMock(TablaManager::class);

        $categoriaManager = new CategoriaManager(
            $categoriaRepository,
            $partidoRepository,
            $validadorManager,
            $tablaManager
        );

        $torneo = new Torneo();

        $categoriaRepository->expects($this->exactly(2))
            ->method('findOneBy')
            ->willReturn(null);

        $validadorManager->expects($this->once())
            ->method('validarCategoria');

        $categoriaRepository->expects($this->once())
            ->method('guardar');

        $categoriaManager->crearCategoria($torneo, 'Masculino', 'nombre', 'nc');
    }

    public function testCrearCategoriaYaExisteNombreGenero(): void
    {
        $categoriaRepository = $this->createMock(CategoriaRepository::class);
        $partidoRepository = $this->createMock(PartidoRepository::class);
        $validadorManager = $this->createMock(ValidadorManager::class);
        $tablaManager = $this->createMock(TablaManager::class);

        $categoriaManager = new CategoriaManager(
            $categoriaRepository,
            $partidoRepository,
            $validadorManager,
            $tablaManager
        );

        $torneo = new Torneo();

        $categoriaRepository->method('findOneBy')
            ->with(['torneo' => $torneo, 'genero' => 'Masculino', 'nombre' => 'nombre'])
            ->willReturn($this->createMock(Categoria::class));

        $this->expectExceptionMessage('Ya existe una categoría con ese nombre y genero');
        $categoriaManager->crearCategoria($torneo, 'Masculino', 'nombre', 'nc');
    }

    public function testCrearCategoriaYaExisteNombreCorto(): void
    {
        $categoriaRepository = $this->createMock(CategoriaRepository::class);
        $partidoRepository = $this->createMock(PartidoRepository::class);
        $validadorManager = $this->createMock(ValidadorManager::class);
        $tablaManager = $this->createMock(TablaManager::class);

        $categoriaManager = new CategoriaManager(
            $categoriaRepository,
            $partidoRepository,
            $validadorManager,
            $tablaManager
        );

        $torneo = new Torneo();

        $categoriaRepository->expects(exactly(2))
            ->method('findOneBy')
            ->willReturn(null, $this->createMock(Categoria::class));

        $this->expectExceptionMessage('Ya existe una categoría con ese nombre corto');
        $categoriaManager->crearCategoria($torneo, 'Masculino', 'nombre', 'nc');
    }

    public function testEditarDisputaSanitizaHtmlPeligroso(): void
    {
        $categoriaRepository = $this->createMock(CategoriaRepository::class);
        $partidoRepository = $this->createMock(PartidoRepository::class);
        $validadorManager = $this->createMock(ValidadorManager::class);
        $tablaManager = $this->createMock(TablaManager::class);

        $categoriaManager = new CategoriaManager(
            $categoriaRepository,
            $partidoRepository,
            $validadorManager,
            $tablaManager
        );

        $categoria = new Categoria();

        $categoriaRepository->expects($this->once())
            ->method('guardar')
            ->with($categoria, true);

        $categoriaManager->editarDisputa(
            $categoria,
            '<div class="ql-align-center" style="color: rgb(255, 0, 0); position: absolute" onmouseover="x()">Regla</div><iframe src="https://evil.local"></iframe>'
        );

        $this->assertStringNotContainsString('onmouseover', (string) $categoria->getDisputa());
        $this->assertStringNotContainsString('<iframe', (string) $categoria->getDisputa());
        $this->assertStringContainsString('ql-align-center', (string) $categoria->getDisputa());
        $this->assertStringContainsString('color: rgb(255, 0, 0)', (string) $categoria->getDisputa());
        $this->assertStringNotContainsString('position: absolute', (string) $categoria->getDisputa());

    }

    public function testEliminarCategoriaLanzaAppExceptionSiNoExiste(): void
    {
        $categoriaRepository = $this->createMock(CategoriaRepository::class);
        $partidoRepository = $this->createMock(PartidoRepository::class);
        $validadorManager = $this->createMock(ValidadorManager::class);
        $tablaManager = $this->createMock(TablaManager::class);

        $categoriaRepository->expects($this->once())
            ->method('find')
            ->with(99)
            ->willReturn(null);

        $categoriaManager = new CategoriaManager(
            $categoriaRepository,
            $partidoRepository,
            $validadorManager,
            $tablaManager
        );

        $this->expectExceptionMessage('No se encontró la categoría');

        $categoriaManager->eliminarCategoria(99);
    }

    public function testArmarPlayOffFinalizaGruposYAsignaEquipos(): void
    {
        $categoriaRepository = $this->createMock(CategoriaRepository::class);
        $partidoRepository = $this->createMock(PartidoRepository::class);
        $validadorManager = $this->createMock(ValidadorManager::class);
        $tablaManager = $this->createMock(TablaManager::class);

        $categoria = $this->createMock(Categoria::class);
        $torneo = new Torneo();
        $categoria->method('getTorneo')->willReturn($torneo);
        $categoria->expects($this->once())
            ->method('setEstado')
            ->with(\App\Enum\EstadoCategoria::ZONAS_CERRADAS->value);

        $grupo = $this->createMock(\App\Entity\Grupo::class);
        $grupo->method('getEstado')->willReturn(\App\Enum\EstadoGrupo::FINALIZADO->value);
        $grupo->method('getNombre')->willReturn('Grupo A');
        $categoria->method('getGrupos')->willReturn(new ArrayCollection([$grupo]));

        $equipo1 = $this->createMock(\App\Entity\Equipo::class);
        $equipo2 = $this->createMock(\App\Entity\Equipo::class);
        $partido = new \App\Entity\Partido();
        $partido->setEquipoLocal(null);
        $partido->setEquipoVisitante(null);
        $partido->setPartidoConfig((new \App\Entity\PartidoConfig())
            ->setGrupoEquipo1($grupo)
            ->setGrupoEquipo2($grupo)
            ->setPosicionEquipo1(1)
            ->setPosicionEquipo2(2));
        $categoria->method('getPartidos')->willReturn(new ArrayCollection([$partido]));

        $tablaManager->expects($this->once())
            ->method('calcularPosiciones')
            ->with($grupo)
            ->willReturn([
                ['equipo' => $equipo1],
                ['equipo' => $equipo2],
            ]);

        $partidoRepository->expects($this->once())
            ->method('guardar')
            ->with($partido);

        $categoriaRepository->expects($this->once())
            ->method('guardar')
            ->with($categoria, true);

        $categoriaManager = new CategoriaManager(
            $categoriaRepository,
            $partidoRepository,
            $validadorManager,
            $tablaManager
        );

        $categoriaManager->armarPlayOff($categoria);

        $this->assertSame($equipo1, $partido->getEquipoLocal());
        $this->assertSame($equipo2, $partido->getEquipoVisitante());
    }

    public function testArmarPlayOffLanzaAppExceptionSiGrupoNoFinalizado(): void
    {
        $categoriaRepository = $this->createMock(CategoriaRepository::class);
        $partidoRepository = $this->createMock(PartidoRepository::class);
        $validadorManager = $this->createMock(ValidadorManager::class);
        $tablaManager = $this->createMock(TablaManager::class);

        $categoria = $this->createMock(Categoria::class);
        $grupo = $this->createMock(\App\Entity\Grupo::class);
        $grupo->method('getEstado')->willReturn('Borrador');
        $categoria->method('getGrupos')->willReturn(new ArrayCollection([$grupo]));

        $categoriaManager = new CategoriaManager(
            $categoriaRepository,
            $partidoRepository,
            $validadorManager,
            $tablaManager
        );

        $this->expectExceptionMessage('No se puede armar el play off si no se han finalizado todos los grupos');

        $categoriaManager->armarPlayOff($categoria);
    }
}
