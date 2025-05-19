<?php

declare(strict_types=1);

namespace App\Enum;

enum Rol: string
{
    case ROLE_ADMIN = "ROLE_ADMIN";
    case ROLE_ORGANIZADOR = "ROLE_ORGANIZADOR";
    case ROLE_COLABORADOR = "ROLE_COLABORADOR";
    case ROLE_PLANILLERO = "ROLE_PLANILLERO";
    case ROLE_PARTICIPANTE = "ROLE_PARTICIPANTE";
}
