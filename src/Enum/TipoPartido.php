<?php

declare(strict_types=1);

namespace App\Enum;

enum TipoPartido: string
{
    case CLASIFICATORIO = "Clasificatorio";
    case ELIMINATORIO = "Eliminatorio";

    public static function getValues(): array
    {
        return array_map(fn ($case) => $case->value, self::cases());
    }
}
