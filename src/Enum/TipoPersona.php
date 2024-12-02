<?php

declare(strict_types=1);

namespace App\Enum;

enum TipoPersona: string
{
    case JUGADOR = "Jugador";
    case ENTRENADOR = "Entrenador";
}
