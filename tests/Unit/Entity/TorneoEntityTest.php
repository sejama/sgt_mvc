<?php

declare(strict_types=1);

namespace App\Tests\Unit\Entity;

use App\Entity\Categoria;
use App\Entity\Sede;
use App\Entity\Torneo;
use App\Entity\Usuario;
use PHPUnit\Framework\TestCase;

class TorneoEntityTest extends TestCase
{
    public function testTorneoGettersSettersYRelaciones(): void
    {
        $torneo = new Torneo();

        $creador = (new Usuario())
            ->setUsername('creador')
            ->setPassword('hash')
            ->setRoles(['ROLE_ADMIN', 'ROLE_USER']);

        $colaborador = (new Usuario())
            ->setUsername('colab')
            ->setPassword('hash')
            ->setRoles(['ROLE_USER']);

        $categoria = new Categoria();
        $sede = new Sede();

        $inicioInscripcion = new \DateTimeImmutable('2026-01-01 10:00:00');
        $finInscripcion = new \DateTimeImmutable('2026-01-10 10:00:00');
        $inicioTorneo = new \DateTimeImmutable('2026-02-01 10:00:00');
        $finTorneo = new \DateTimeImmutable('2026-02-20 10:00:00');

        $torneo
            ->setNombre('Torneo Test')
            ->setRuta('torneo-test')
            ->setDescripcion('Descripcion torneo test')
            ->setFechaInicioInscripcion($inicioInscripcion)
            ->setFechaFinInscripcion($finInscripcion)
            ->setFechaInicioTorneo($inicioTorneo)
            ->setFechaFinTorneo($finTorneo)
            ->setReglamento('<p>Reglamento</p>')
            ->setCreador($creador)
            ->setEstado('activo');

        self::assertSame('Torneo Test', $torneo->getNombre());
        self::assertSame('torneo-test', $torneo->getRuta());
        self::assertSame('Descripcion torneo test', $torneo->getDescripcion());
        self::assertSame($inicioInscripcion, $torneo->getFechaInicioInscripcion());
        self::assertSame($finInscripcion, $torneo->getFechaFinInscripcion());
        self::assertSame($inicioTorneo, $torneo->getFechaInicioTorneo());
        self::assertSame($finTorneo, $torneo->getFechaFinTorneo());
        self::assertSame('<p>Reglamento</p>', $torneo->getReglamento());
        self::assertSame($creador, $torneo->getCreador());
        self::assertSame('activo', $torneo->getEstado());

        $torneo->addCategoria($categoria);
        self::assertCount(1, $torneo->getCategorias());
        self::assertSame($torneo, $categoria->getTorneo());

        $torneo->removeCategoria($categoria);
        self::assertCount(0, $torneo->getCategorias());
        self::assertNull($categoria->getTorneo());

        $torneo->addSede($sede);
        self::assertCount(1, $torneo->getSedes());
        self::assertSame($torneo, $sede->getTorneo());

        $torneo->removeSede($sede);
        self::assertCount(0, $torneo->getSedes());
        self::assertNull($sede->getTorneo());

        $torneo->addColaborador($colaborador);
        self::assertCount(1, $torneo->getColaborador());

        $torneo->removeColaborador($colaborador);
        self::assertCount(0, $torneo->getColaborador());

        $torneo->setCreatedAt();
        $torneo->setUpdatedAt();
        self::assertInstanceOf(\DateTimeImmutable::class, $torneo->getCreatedAt());
        self::assertInstanceOf(\DateTimeImmutable::class, $torneo->getUpdatedAt());

        $torneo
            ->setDescripcion(null)
            ->setReglamento(null)
            ->setCreador(null);

        self::assertNull($torneo->getDescripcion());
        self::assertNull($torneo->getReglamento());
        self::assertNull($torneo->getCreador());
    }
}
