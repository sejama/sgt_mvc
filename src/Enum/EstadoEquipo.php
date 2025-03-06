<?php

declare(strict_types=1);

namespace App\Enum;

enum EstadoEquipo: string
{
    case BORRADOR = "Borrador";
    case Activo = "Activo";
    case NO_SE_PRESENTO = "No_se_presento";
    case SUSPENDIDO = "Suspendido";
    case DESCALIFICADO = "Descalificado";
    case RETIRADO = "Retirado";
    case ELIMINADO = "Eliminado";

    public static function getValues(): array
    {
        return array_map(fn ($case) => $case->value, self::cases());
    }
}
