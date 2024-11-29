<?php

declare(strict_types=1);

namespace App\Manager;

use App\Entity\Categoria;
use App\Entity\Torneo;
use App\Entity\Usuario;
use App\Enum\Genero;
use App\Exception\AppException;

class ValidadorManager
{
    public function validarUsuario($username, $password): void
    {
        $this->validarLongitud('nombre de usuario', $username, 4, 128);
        $this->validarLongitud('contraseña', $password, 5, 255);
        if (false !== strpos($username, ' ')) {
            throw new AppException('El Usuario ingresado no puede contener espacios');
        }

        if ($username === $password) {
            throw new AppException('El nombre de usuario y la contraseña no pueden ser iguales');
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
        $this->validarFechayHora('Inicio del torneo', $inicio_torneo);
        $this->validarFechayHora('Fin del torneo', $fin_torneo);
        $this->validarFechayHora('Inicio de inscripción', $inicio_inscripcion);
        $this->validarFechayHora('Fin de inscripción', $fin_inscripcion);
        $this->validarFechaInicioFin(
            'Torneo',
            new \DateTime($inicio_torneo),
            new \DateTime($fin_torneo)
        );
        $this->validarFechaInicioFin(
            'Inscripción',
            new \DateTime($inicio_inscripcion),
            new \DateTime($fin_inscripcion)
        );
        $this->validarFechaInicioFin(
            'Inscripción y Torneo',
            new \DateTime($fin_inscripcion),
            new \DateTime($inicio_torneo)
        );
    }

    public function validarCategoria(
        Torneo $torneo,
        string $genero,
        string $nombre,
        string $nombreCorto
    ): void {
        $this->validarLongitud('Nombre', $nombre, 3, 128);
        $this->validarLongitud('Nombre Corto', $nombreCorto, 3, 32);
        $this->validarGenero($genero);
    }

    public function validarSede(
        string $nombre,
        string $direccion
    ): void {
        $this->validarLongitud('Nombre', $nombre, 3, 128);
        $this->validarLongitud('Dirección', $direccion, 8, 128);
    }

    public function validarCancha(
        string $nombre,
        string $descripcion
    ): void {
        $this->validarLongitud('Nombre', $nombre, 1, 128);
        $this->validarLongitud('Descripción', $descripcion, 0, 255);
    }

    public function validarEquipo(
        string $nombre,
        string $nombreCorto,
        string $pais,
        string $provincia,
        string $localidad
    ): void {
        $this->validarLongitud('Nombre', $nombre, 3, 128);
        $this->validarLongitud('Nombre Corto', $nombreCorto, 3, 16);
        //$this->validarLongitud('País', $pais, 3, 128);
        //$this->validarLongitud('Provincia', $provincia, 3, 128);
        //$this->validarLongitud('Localidad', $localidad, 3, 128);
    }

    public function validarJugador(
        string $nombre,
        string $apellido,
        string $tipoDocumento,
        string $numeroDocumento,
        string $fechaNacimiento,
    ): void {
        $this->validarLongitud('Nombre', $nombre, 3, 128);
        $this->validarLongitud('Apellido', $apellido, 3, 128);
        $this->validarLongitud('Tipo Documento', $tipoDocumento, 1, 8);
        $this->validarLongitud('Número Documento', $numeroDocumento, 5, 8);
        $this->validarFecha('Fecha de Nacimiento', $fechaNacimiento);
    }

    private function validarGenero(string $genero): void
    {
        if (!in_array($genero, Genero::getValues())) {
                throw new AppException('El género no es válido');
        }
    }

    private function validarLongitud(string $nombre, string $campo, int $min, int $max): void
    {
        if (strlen($campo) < $min || strlen($campo) > $max) {
            throw new AppException(sprintf('El %s debe tener entre %d y %d caracteres', $nombre, $min, $max));
        }
    }

    private function validarFechayHora(string $nombre, string $fecha): void
    {
        $fecha = \DateTime::createFromFormat('Y-m-d H:i', $fecha);

        if ($fecha === false) {
            throw new AppException(sprintf('La fecha de %s no es válida', $nombre));
        }
    }

    private function validarFecha(string $nombre, string $fecha): void
    {
        $fecha = \DateTime::createFromFormat('Y-m-d', $fecha);

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
        if ($inicio >= $fin) {
            throw new AppException(sprintf('La fecha de %s no puede ser mayor a la fecha de fin', $nombre));
        }
    }
}
