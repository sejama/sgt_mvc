<?php

declare(strict_types=1);

namespace App\Enum;

enum EstadoTorneo: string
{
    case BORRADOR = "Borrador";
    case ACTIVO = "Inscripcion";
    case EN_CURSO = "En curso";
    case FINALIZADO = "Finalizado";

    public static function getValues(): array
    {
        return array_map(fn ($case) => $case->value, self::cases());
    }
}
