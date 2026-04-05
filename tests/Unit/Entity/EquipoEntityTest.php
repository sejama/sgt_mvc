<?php

declare(strict_types=1);

namespace App\Tests\Unit\Entity;

use App\Entity\Categoria;
use App\Entity\Equipo;
use App\Entity\Grupo;
use App\Entity\Jugador;
use App\Entity\Partido;
use PHPUnit\Framework\TestCase;

class EquipoEntityTest extends TestCase
{
    public function testEquipoGettersSettersYRelaciones(): void
    {
        $equipo = new Equipo();

        $categoria = new Categoria();
        $grupo = new Grupo();
        $jugador = new Jugador();
        $partidoLocal = new Partido();
        $partidoVisitante = new Partido();

        $equipo
            ->setNombre('Equipo Test')
            ->setNombreCorto('ET')
            ->setPais('Argentina')
            ->setProvincia('Mendoza')
            ->setLocalidad('Capital')
            ->setCategoria($categoria)
            ->setGrupo($grupo)
            ->setEstado('activo')
            ->setNumero(7);

        self::assertSame('Equipo Test', $equipo->getNombre());
        self::assertSame('ET', $equipo->getNombreCorto());
        self::assertSame('Argentina', $equipo->getPais());
        self::assertSame('Mendoza', $equipo->getProvincia());
        self::assertSame('Capital', $equipo->getLocalidad());
        self::assertSame($categoria, $equipo->getCategoria());
        self::assertSame($grupo, $equipo->getGrupo());
        self::assertSame('activo', $equipo->getEstado());
        self::assertSame(7, $equipo->getNumero());

        $equipo->addJugadore($jugador);
        self::assertCount(1, $equipo->getJugadores());
        self::assertSame($equipo, $jugador->getEquipo());

        $equipo->removeJugadore($jugador);
        self::assertCount(0, $equipo->getJugadores());
        self::assertNull($jugador->getEquipo());

        $equipo->addPartidosLocal($partidoLocal);
        self::assertCount(1, $equipo->getPartidosLocal());
        self::assertSame($equipo, $partidoLocal->getEquipoLocal());

        $equipo->removePartidosLocal($partidoLocal);
        self::assertCount(0, $equipo->getPartidosLocal());
        self::assertNull($partidoLocal->getEquipoLocal());

        $equipo->addPartidosVisitante($partidoVisitante);
        self::assertCount(1, $equipo->getPartidosVisitante());
        self::assertSame($equipo, $partidoVisitante->getEquipoVisitante());

        $equipo->removePartidosVisitante($partidoVisitante);
        self::assertCount(0, $equipo->getPartidosVisitante());
        self::assertNull($partidoVisitante->getEquipoVisitante());

        $equipo->setCreatedAt();
        $equipo->setUpdatedAt();
        self::assertInstanceOf(\DateTimeImmutable::class, $equipo->getCreatedAt());
        self::assertInstanceOf(\DateTimeImmutable::class, $equipo->getUpdatedAt());

        $equipo
            ->setPais(null)
            ->setProvincia(null)
            ->setLocalidad(null)
            ->setCategoria(null)
            ->setGrupo(null);

        self::assertNull($equipo->getPais());
        self::assertNull($equipo->getProvincia());
        self::assertNull($equipo->getLocalidad());
        self::assertNull($equipo->getCategoria());
        self::assertNull($equipo->getGrupo());
    }
}
