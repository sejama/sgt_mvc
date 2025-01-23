<?php

declare(strict_types=1);

namespace App\Enum;

enum EstadoEquipo: string
{
    case BORRADOR = "Borrador";
    case Activo = "Activo";
    case NO_SE_PRESENTO = "Partidos_creados";
    case FINALIZADO = "Finalizado";

    public static function getValues(): array
    {
        return array_map(fn ($case) => $case->value, self::cases());
    }
}
