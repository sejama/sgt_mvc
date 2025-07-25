<?php

declare(strict_types=1);

namespace App\Enum;

enum Genero: string
{
    case FEMENINO = "Femenino";
    case MASCULINO = "Masculino";

    public static function getValues(): array
    {
        return array_map(fn ($case) => $case->value, self::cases());
    }
}
