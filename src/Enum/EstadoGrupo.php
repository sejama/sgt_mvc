<?php

declare(strict_types=1);

namespace App\Enum;

enum EstadoGrupo: string
{
    case BORRADOR = "Borrador";
    case ZONAS_CREADAS = "Zonas_creadas";
    case PARTIDOS_CREADOS = "Partidos_creados";
    case FINALIZADO = "Finalizado";

    public static function getValues(): array
    {
        return array_map(fn ($case) => $case->value, self::cases());
    }
}
