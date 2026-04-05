<?php

declare(strict_types=1);

namespace App\Tests\Unit\Entity;

use App\Entity\Cancha;
use App\Entity\Partido;
use App\Entity\Sede;
use PHPUnit\Framework\TestCase;

class CanchaEntityTest extends TestCase
{
    public function testCanchaGettersSettersYRelaciones(): void
    {
        $cancha = new Cancha();
        $sede = new Sede();
        $partido = new Partido();

        $cancha
            ->setNombre('Cancha 1')
            ->setDescripcion('Descripcion cancha')
            ->setSede($sede);

        self::assertSame('Cancha 1', $cancha->getNombre());
        self::assertSame('Descripcion cancha', $cancha->getDescripcion());
        self::assertSame($sede, $cancha->getSede());

        $cancha->addPartido($partido);
        self::assertCount(1, $cancha->getPartidos());
        self::assertSame($cancha, $partido->getCancha());

        $cancha->removePartido($partido);
        self::assertCount(0, $cancha->getPartidos());
        self::assertNull($partido->getCancha());

        $cancha->setCreatedAt();
        $cancha->setUpdatedAt();
        self::assertInstanceOf(\DateTimeImmutable::class, $cancha->getCreatedAt());
        self::assertInstanceOf(\DateTimeImmutable::class, $cancha->getUpdatedAt());

        $cancha->setSede(null);
        self::assertNull($cancha->getSede());
    }
}
