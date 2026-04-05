<?php

declare(strict_types=1);

namespace App\Tests\Unit\Entity;

use App\Entity\Categoria;
use App\Entity\Equipo;
use App\Entity\Grupo;
use App\Entity\Partido;
use App\Entity\Torneo;
use App\Enum\Genero;
use PHPUnit\Framework\TestCase;

class CategoriaEntityTest extends TestCase
{
    public function testCategoriaGettersSettersYRelaciones(): void
    {
        $categoria = new Categoria();

        $torneo = new Torneo();
        $equipo = new Equipo();
        $grupo = new Grupo();
        $partido = new Partido();

        $categoria
            ->setNombre('Sub 18')
            ->setNombreCorto('S18')
            ->setGenero(Genero::MASCULINO)
            ->setDisputa('Simple')
            ->setTorneo($torneo)
            ->setEstado('borrador');

        self::assertSame('Sub 18', $categoria->getNombre());
        self::assertSame('S18', $categoria->getNombreCorto());
        self::assertSame(Genero::MASCULINO, $categoria->getGenero());
        self::assertSame('Simple', $categoria->getDisputa());
        self::assertSame($torneo, $categoria->getTorneo());
        self::assertSame('borrador', $categoria->getEstado());

        $categoria->addEquipo($equipo);
        self::assertCount(1, $categoria->getEquipos());
        self::assertSame($categoria, $equipo->getCategoria());

        $categoria->removeEquipo($equipo);
        self::assertCount(0, $categoria->getEquipos());
        self::assertNull($equipo->getCategoria());

        $categoria->addGrupo($grupo);
        self::assertCount(1, $categoria->getGrupos());
        self::assertSame($categoria, $grupo->getCategoria());

        $categoria->removeGrupo($grupo);
        self::assertCount(0, $categoria->getGrupos());
        self::assertNull($grupo->getCategoria());

        $categoria->addPartido($partido);
        self::assertCount(1, $categoria->getPartidos());
        self::assertSame($categoria, $partido->getCategoria());

        $categoria->removePartido($partido);
        self::assertCount(0, $categoria->getPartidos());
        self::assertNull($partido->getCategoria());

        $categoria->setCreatedAt();
        $categoria->setUpdatedAt();
        self::assertInstanceOf(\DateTimeImmutable::class, $categoria->getCreatedAt());
        self::assertInstanceOf(\DateTimeImmutable::class, $categoria->getUpdatedAt());

        $categoria
            ->setDisputa(null)
            ->setTorneo(null);

        self::assertNull($categoria->getDisputa());
        self::assertNull($categoria->getTorneo());
    }
}
