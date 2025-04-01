<?php

declare(strict_types=1);

namespace App\Enum;

enum EstadoCategoria: string
{
    case BORRADOR = "Borrador";
    case ACTIVA = "Activa";
    case CERRADA = "Cerrada";
    case ZONAS_CREADAS = "Zonas_creadas";
    case ZONAS_CERRADAS = "Zonas_cerradas";
    case FINALIZADO = "Finalizado";

    public static function getValues(): array
    {
        return array_map(fn ($case) => $case->value, self::cases());
    }
}
