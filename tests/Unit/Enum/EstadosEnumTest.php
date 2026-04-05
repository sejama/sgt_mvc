<?php

declare(strict_types=1);

namespace App\Tests\Unit\Enum;

use App\Enum\EstadoCategoria;
use App\Enum\EstadoEquipo;
use App\Enum\EstadoGrupo;
use App\Enum\EstadoPartido;
use App\Enum\EstadoTorneo;
use App\Enum\TipoPartido;
use PHPUnit\Framework\TestCase;

class EstadosEnumTest extends TestCase
{
    public function testGetValuesDevuelveValoresDeEnums(): void
    {
        self::assertContains('Borrador', EstadoCategoria::getValues());
        self::assertContains('Activo', EstadoEquipo::getValues());
        self::assertContains('Zonas_creadas', EstadoGrupo::getValues());
        self::assertContains('Programado', EstadoPartido::getValues());
        self::assertContains('Inscripcion', EstadoTorneo::getValues());
        self::assertSame(['Clasificatorio', 'Eliminatorio'], TipoPartido::getValues());
    }
}
