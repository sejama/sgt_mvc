<?php

declare(strict_types=1);

namespace App\Enum;

enum Rol: string
{
    case ROLE_ADMIN = "ROLE_ADMIN";
    case ROLE_ORGANIZADOR = "ROLE_ORGANIZADOR";
    case ROLE_USER = "ROLE_USER";
}