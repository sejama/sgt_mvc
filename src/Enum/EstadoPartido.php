<?php

declare(strict_types=1);

namespace App\Enum;

enum EstadoPartido: string
{
    case BORRADOR = "Borrador";
    case ACTIVO = "Activo";
    case SUSPENDIDO = "Suspendido";
    case FINALIZADO = "Finalizado";

    public static function getValues(): array
    {
        return array_map(fn ($case) => $case->value, self::cases());
    }
}
