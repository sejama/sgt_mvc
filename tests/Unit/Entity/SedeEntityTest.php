<?php

declare(strict_types=1);

namespace App\Tests\Unit\Entity;

use App\Entity\Cancha;
use App\Entity\Sede;
use App\Entity\Torneo;
use PHPUnit\Framework\TestCase;

class SedeEntityTest extends TestCase
{
    public function testSedeGettersSettersYRelaciones(): void
    {
        $sede = new Sede();
        $torneo = new Torneo();
        $cancha = new Cancha();

        $sede
            ->setNombre('Sede Norte')
            ->setDomicilio('Calle 123')
            ->setTorneo($torneo);

        self::assertSame('Sede Norte', $sede->getNombre());
        self::assertSame('Calle 123', $sede->getDomicilio());
        self::assertSame($torneo, $sede->getTorneo());

        $sede->addCancha($cancha);
        self::assertCount(1, $sede->getCanchas());
        self::assertSame($sede, $cancha->getSede());

        $sede->removeCancha($cancha);
        self::assertCount(0, $sede->getCanchas());
        self::assertNull($cancha->getSede());

        $sede->setCreatedAt();
        $sede->setUpdatedAt();
        self::assertInstanceOf(\DateTimeImmutable::class, $sede->getCreatedAt());
        self::assertInstanceOf(\DateTimeImmutable::class, $sede->getUpdatedAt());

        $sede->setTorneo(null);
        self::assertNull($sede->getTorneo());
    }
}
