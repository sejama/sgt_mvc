<?php

declare(strict_types=1);

namespace App\Tests\Unit\Manager;

use App\Entity\Equipo;
use App\Entity\Grupo;
use App\Entity\Partido;
use App\Enum\EstadoGrupo;
use App\Enum\EstadoPartido;
use App\Manager\TablaManager;
use App\Repository\CategoriaRepository;
use App\Repository\GrupoRepository;
use PHPUnit\Framework\TestCase;

class TablaManagerTest extends TestCase
{
    public function testCalcularPosicionesFinalizaGrupoYOrdenaTabla(): void
    {
        $grupoRepository = $this->createMock(GrupoRepository::class);
        $categoriaRepository = $this->createMock(CategoriaRepository::class);

        $manager = new TablaManager($categoriaRepository, $grupoRepository);

        $grupo = new Grupo();
        $grupo->setNombre('Grupo A');
        $grupo->setEstado(EstadoGrupo::PARTIDOS_CREADOS->value);

        $equipoA = $this->createMock(Equipo::class);
        $equipoA->method('getId')->willReturn(1);
        $equipoA->method('getNombre')->willReturn('Equipo A');

        $equipoB = $this->createMock(Equipo::class);
        $equipoB->method('getId')->willReturn(2);
        $equipoB->method('getNombre')->willReturn('Equipo B');

        $grupo->addEquipo($equipoA);
        $grupo->addEquipo($equipoB);

        $partido = new Partido();
        $partido->setEquipoLocal($equipoA);
        $partido->setEquipoVisitante($equipoB);
        $partido->setEstado(EstadoPartido::FINALIZADO->value);
        $partido->setLocalSet1(25);
        $partido->setVisitanteSet1(20);
        $partido->setLocalSet2(25);
        $partido->setVisitanteSet2(22);
        $partido->setLocalSet3(25);
        $partido->setVisitanteSet3(18);
        $partido->setLocalSet4(0);
        $partido->setVisitanteSet4(0);
        $partido->setLocalSet5(0);
        $partido->setVisitanteSet5(0);

        $grupo->addPartido($partido);

        $grupoRepository->expects($this->once())
            ->method('guardar')
            ->with($grupo);

        $posiciones = $manager->calcularPosiciones($grupo);

        $posicionesPorNombre = [];
        foreach ($posiciones as $fila) {
            $posicionesPorNombre[$fila['nombre']] = $fila;
        }

        $this->assertCount(2, $posiciones);
        $this->assertArrayHasKey('Equipo A', $posicionesPorNombre);
        $this->assertArrayHasKey('Equipo B', $posicionesPorNombre);
        $this->assertGreaterThanOrEqual(0, $posicionesPorNombre['Equipo A']['partidosJugados']);
        $this->assertGreaterThanOrEqual(0, $posicionesPorNombre['Equipo A']['puntos']);
        $this->assertSame(EstadoGrupo::FINALIZADO->value, $grupo->getEstado());
    }

    public function testCalcularPosicionesNoGuardaSiGrupoYaFinalizado(): void
    {
        $grupoRepository = $this->createMock(GrupoRepository::class);
        $categoriaRepository = $this->createMock(CategoriaRepository::class);

        $manager = new TablaManager($categoriaRepository, $grupoRepository);

        $grupo = new Grupo();
        $grupo->setNombre('Grupo B');
        $grupo->setEstado(EstadoGrupo::FINALIZADO->value);

        $equipoA = $this->createMock(Equipo::class);
        $equipoA->method('getId')->willReturn(10);
        $equipoA->method('getNombre')->willReturn('Equipo A');

        $equipoB = $this->createMock(Equipo::class);
        $equipoB->method('getId')->willReturn(20);
        $equipoB->method('getNombre')->willReturn('Equipo B');

        $grupo->addEquipo($equipoA);
        $grupo->addEquipo($equipoB);

        $partidoCancelado = new Partido();
        $partidoCancelado->setEquipoLocal($equipoA);
        $partidoCancelado->setEquipoVisitante($equipoB);
        $partidoCancelado->setEstado(EstadoPartido::CANCELADO->value);

        $grupo->addPartido($partidoCancelado);

        $grupoRepository->expects($this->never())
            ->method('guardar');

        $posiciones = $manager->calcularPosiciones($grupo);

        $this->assertCount(2, $posiciones);
        $this->assertSame(0, $posiciones[0]['puntos']);
        $this->assertSame(0, $posiciones[1]['puntos']);
    }
}
