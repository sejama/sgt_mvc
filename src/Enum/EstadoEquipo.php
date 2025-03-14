<?php

declare(strict_types=1);

namespace App\Enum;

enum EstadoEquipo: string
{
    case BORRADOR = "Borrador";
    case ACTIVO = "Activo";
    case NO_PARTICIPA = "No_participa";
    case ELIMINADO = "Eliminado";
    case DESCALIFICADO = "Descalificado";
    case CALIFICADO = "Calificado";

    public static function getValues(): array
    {
        return array_map(fn ($case) => $case->value, self::cases());
    }
}
