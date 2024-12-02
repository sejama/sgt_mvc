<?php

declare(strict_types=1);

namespace App\Enum;

enum TipoDocumento: string
{
    case DNI = "DNI";
    case LC = "LC";
    case LE = "LE";
    case CI = "CI";
    case PASAPORTE = "PASAPORTE";
}
