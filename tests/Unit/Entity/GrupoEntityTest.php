<?php

declare(strict_types=1);

namespace App\Tests\Unit\Entity;

use App\Entity\Categoria;
use App\Entity\Equipo;
use App\Entity\Grupo;
use App\Entity\Partido;
use PHPUnit\Framework\TestCase;

class GrupoEntityTest extends TestCase
{
    public function testGrupoGettersSettersYRelaciones(): void
    {
        $grupo = new Grupo();
        $categoria = new Categoria();
        $equipo = new Equipo();
        $partido = new Partido();

        $grupo
            ->setNombre('Grupo A')
            ->setClasificaOro(2)
            ->setClasificaPlata(1)
            ->setClasificaBronce(1)
            ->setCategoria($categoria)
            ->setEstado('borrador');

        self::assertSame('Grupo A', $grupo->getNombre());
        self::assertSame(2, $grupo->getClasificaOro());
        self::assertSame(1, $grupo->getClasificaPlata());
        self::assertSame(1, $grupo->getClasificaBronce());
        self::assertSame($categoria, $grupo->getCategoria());
        self::assertSame('borrador', $grupo->getEstado());

        $grupo->addEquipo($equipo);
        self::assertCount(1, $grupo->getEquipo());
        self::assertSame($grupo, $equipo->getGrupo());

        $grupo->removeEquipo($equipo);
        self::assertCount(0, $grupo->getEquipo());
        self::assertNull($equipo->getGrupo());

        $grupo->addPartido($partido);
        self::assertCount(1, $grupo->getPartidos());
        self::assertSame($grupo, $partido->getGrupo());

        $grupo->removePartido($partido);
        self::assertCount(0, $grupo->getPartidos());
        self::assertNull($partido->getGrupo());

        $grupo->setCreatedAt();
        $grupo->setUpdatedAt();
        self::assertInstanceOf(\DateTimeImmutable::class, $grupo->getCreatedAt());
        self::assertInstanceOf(\DateTimeImmutable::class, $grupo->getUpdatedAt());

        $grupo
            ->setClasificaPlata(null)
            ->setClasificaBronce(null)
            ->setCategoria(null);

        self::assertNull($grupo->getClasificaPlata());
        self::assertNull($grupo->getClasificaBronce());
        self::assertNull($grupo->getCategoria());
    }
}
