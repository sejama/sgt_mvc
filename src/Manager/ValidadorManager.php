<?php

declare(strict_types=1);

namespace App\Manager;

use App\Entity\Usuario;
use App\Exception\AppException;

class ValidadorManager
{
    public function validarUsuario($username, $password): void
    {
        if (strlen($username) < 4 || strlen($username) > 128) {
            throw new AppException('El nombre de usuario debe tener entre 4 y 128 caracteres');
        }

        if (false !== strpos($username, ' ')) {
            throw new AppException('El Usuario ingresado no puede contener espacios');
        }

        if ($username === $password) {
            throw new AppException('El nombre de usuario y la contraseña no pueden ser iguales');
        }

        if (strlen($password) < 5 || strlen($password) > 255) {
            throw new AppException('La contraseña debe tener entre 5 y 255 caracteres');
        }

        if (false === preg_match('/[A-Z]/', $password)) {
            throw new AppException('La contraseña debe contener al menos una letra mayúscula');
        }

        if (false === preg_match('/[a-z]/', $password)) {
            throw new AppException('La contraseña debe contener al menos una letra minúscula');
        }

        if (false === preg_match('/[0-9]/', $password)) {
            throw new AppException('La contraseña debe contener al menos un número');
        }
    }

    public function validarTorneo(
        string $nombre,
        string $ruta,
        string $descripcion,
        string $inicio_torneo,
        string $fin_torneo,
        string $inicio_inscripcion,
        string $fin_inscripcion,
        Usuario $user
    ): void {
        $this->validarLongitud('Nombre', $nombre, 3, 128);
        $this->validarLongitud('Nombre Corto', $ruta, 3, 32);
        $this->validarRuta($ruta);
        $this->validarLongitud('Descripción', $descripcion, 0, 255);
        $this->validarFecha('Inicio del torneo', $inicio_torneo);
        $this->validarFecha('Fin del torneo', $fin_torneo);
        $this->validarFecha('Inicio de inscripción', $inicio_inscripcion);
        $this->validarFecha('Fin de inscripción', $fin_inscripcion);
        $this->validarFechaInicioFin('torneo', new \DateTime($inicio_torneo), new \DateTime($fin_torneo));
        $this->validarFechaInicioFin(
            'inscripción',
            new \DateTime($inicio_inscripcion),
            new \DateTime($fin_inscripcion)
        );
    }

    private function validarLongitud(string $nombre, string $campo, int $min, int $max): void
    {
        if (strlen($campo) < $min || strlen($campo) > $max) {
            throw new AppException(sprintf('El %d debe tener entre %d y %d caracteres', $nombre, $min, $max));
        }
    }

    private function validarFecha(string $nombre, string $fecha): void
    {
        $fecha = \DateTime::createFromFormat('Y-m-d H:i', $fecha);

        if ($fecha === false) {
            throw new AppException(sprintf('La fecha de %s no es válida', $nombre));
        }
    }

    private function validarRuta(string $ruta): void
    {
        if (false !== strpos($ruta, ' ')) {
            throw new AppException('La ruta no puede contener espacios');
        }

        if (false === preg_match('/^[a-z0-9-]+$/', $ruta)) {
            throw new AppException('La ruta solo puede contener letras minúsculas, mayuscula y guiones');
        }
    }

    private function validarFechaInicioFin(string $nombre, \DateTime $inicio, \DateTime $fin): void
    {
        if ($inicio > $fin) {
            throw new AppException(sprintf('La fecha de %s no puede ser mayor a la fecha de fin', $nombre));
        }
    }
}
