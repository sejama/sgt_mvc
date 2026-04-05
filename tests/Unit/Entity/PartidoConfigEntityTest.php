<?php

declare(strict_types=1);

namespace App\Tests\Unit\Entity;

use App\Entity\Grupo;
use App\Entity\Partido;
use App\Entity\PartidoConfig;
use PHPUnit\Framework\TestCase;

class PartidoConfigEntityTest extends TestCase
{
    public function testPartidoConfigGettersSettersYCamposOpcionales(): void
    {
        $config = new PartidoConfig();

        $partido = new Partido();
        $grupo1 = new Grupo();
        $grupo2 = new Grupo();
        $ganador1 = new Partido();
        $ganador2 = new Partido();
        $perdedor1 = new Partido();
        $perdedor2 = new Partido();

        $config
            ->setPartido($partido)
            ->setGrupoEquipo1($grupo1)
            ->setPosicionEquipo1(1)
            ->setGrupoEquipo2($grupo2)
            ->setPosicionEquipo2(2)
            ->setGanadorPartido1($ganador1)
            ->setGanadorPartido2($ganador2)
            ->setPerdedorPartido1($perdedor1)
            ->setPerdedorPartido2($perdedor2)
            ->setNombre('Semi final oro');

        self::assertSame($partido, $config->getPartido());
        self::assertSame($grupo1, $config->getGrupoEquipo1());
        self::assertSame(1, $config->getPosicionEquipo1());
        self::assertSame($grupo2, $config->getGrupoEquipo2());
        self::assertSame(2, $config->getPosicionEquipo2());
        self::assertSame($ganador1, $config->getGanadorPartido1());
        self::assertSame($ganador2, $config->getGanadorPartido2());
        self::assertSame($perdedor1, $config->getPerdedorPartido1());
        self::assertSame($perdedor2, $config->getPerdedorPartido2());
        self::assertSame('Semi final oro', $config->getNombre());

        $config->setCreatedAt();
        $config->setUpdatedAt();
        self::assertInstanceOf(\DateTimeImmutable::class, $config->getCreatedAt());
        self::assertInstanceOf(\DateTimeImmutable::class, $config->getUpdatedAt());

        $config
            ->setGrupoEquipo1(null)
            ->setPosicionEquipo1(null)
            ->setGrupoEquipo2(null)
            ->setPosicionEquipo2(null)
            ->setGanadorPartido1(null)
            ->setGanadorPartido2(null)
            ->setPerdedorPartido1(null)
            ->setPerdedorPartido2(null);

        self::assertNull($config->getGrupoEquipo1());
        self::assertNull($config->getPosicionEquipo1());
        self::assertNull($config->getGrupoEquipo2());
        self::assertNull($config->getPosicionEquipo2());
        self::assertNull($config->getGanadorPartido1());
        self::assertNull($config->getGanadorPartido2());
        self::assertNull($config->getPerdedorPartido1());
        self::assertNull($config->getPerdedorPartido2());
    }
}
