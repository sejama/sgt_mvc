<?php

declare(strict_types=1);

namespace App\Manager;

use App\Exception\AppException;

class ValidadorPartidoManager{
    /**
     * Validar que los equipos no sean nulos: Se verifica que grupoEquipo1, posicionEquipo1, grupoEquipo2 y posicionEquipo2 no estén vacíos.
     * Validar que las posiciones sean válidas: Se verifica que grupoEquipo1, posicionEquipo1, grupoEquipo2 y posicionEquipo2 sean números válidos.
     * Validar que los nombres de los partidos no estén vacíos: Se verifica que nombre no esté vacío.
     * Validar que los equipos ganadores sean válidos: Se verifica que equipoGanador1 y equipoGanador2 sean números válidos si están presentes.
     */
    public function validarPlayOff(array $partidosPlayOff): void
    {
        $equipos = [];

        foreach ($partidosPlayOff as $tiposPlayOff) {
            foreach ($tiposPlayOff as $partidoPlayOff) {
                foreach ($partidoPlayOff as $playOff) {
                    if (empty($playOff['nombre'])) {
                        throw new AppException('El nombre del partido es requerido');
                    }
                    if (empty($playOff['grupoEquipo1']) || empty($playOff['posicionEquipo1']) || empty($playOff['grupoEquipo2']) || empty($playOff['posicionEquipo2'])) {
                        throw new AppException('Los equipos y sus posiciones son requeridos');
                    }
                    if (!is_numeric($playOff['grupoEquipo1']) || !is_numeric($playOff['posicionEquipo1']) || !is_numeric($playOff['grupoEquipo2']) || !is_numeric($playOff['posicionEquipo2'])) {
                        throw new AppException('Las posiciones de los equipos deben ser números válidos');
                    }
                    if (isset($playOff['equipoGanador1']) && isset($playOff['equipoGanador2']) && (!is_numeric($playOff['equipoGanador1']) || !is_numeric($playOff['equipoGanador2']))) {
                        throw new AppException('Los equipos ganadores deben ser números válidos');
                    }

                    if ($playOff['grupoEquipo1'] === $playOff['grupoEquipo2'] && $playOff['posicionEquipo1'] === $playOff['posicionEquipo2']) {
                        throw new AppException('Los equipos no pueden ser del mismo grupo y posición');
                    }

                    if (isset($playOff['equipoGanador1']) && isset($playOff['equipoGanador2']) && $playOff['equipoGanador1'] === $playOff['equipoGanador2']) {
                        throw new AppException('Los equipos ganadores no pueden ser el mismo');
                    }

                    $equipo1 = $playOff['grupoEquipo1'] . '-' . $playOff['posicionEquipo1'];
                    $equipo2 = $playOff['grupoEquipo2'] . '-' . $playOff['posicionEquipo2'];

                    if (in_array($equipo1, $equipos, true)) {
                        throw new AppException('El equipo ' . $equipo1 . ' ya está asignado en otro partido');
                    }

                    if (in_array($equipo2, $equipos, true)) {
                        throw new AppException('El equipo ' . $equipo2 . ' ya está asignado en otro partido');
                    }

                    $equipos[] = $equipo1;
                    $equipos[] = $equipo2;
                }
            }
        }
    }
    /*
    array(2) { 
        ["oro"]=> array(3) { 
            ["Cuartos de Final Oro"]=> array(4) { 
                [1]=> array(5) { ["nombre"]=> string(22) "Cuartos de Final Oro 1" 
                    ["grupoEquipo1"]=> string(1) "1" ["posicionEquipo1"]=> string(1) "1" ["grupoEquipo2"]=> string(1) "2" ["posicionEquipo2"]=> string(1) "2" } 
                [2]=> array(5) { ["nombre"]=> string(22) "Cuartos de Final Oro 2" 
                    ["grupoEquipo1"]=> string(1) "2" ["posicionEquipo1"]=> string(1) "1" ["grupoEquipo2"]=> string(1) "1" ["posicionEquipo2"]=> string(1) "2" } 
                [3]=> array(5) { ["nombre"]=> string(22) "Cuartos de Final Oro 3" 
                    ["grupoEquipo1"]=> string(1) "3" ["posicionEquipo1"]=> string(1) "1" ["grupoEquipo2"]=> string(1) "4" ["posicionEquipo2"]=> string(1) "2" } 
                [4]=> array(5) { ["nombre"]=> string(22) "Cuartos de Final Oro 4" 
                    ["grupoEquipo1"]=> string(1) "4" ["posicionEquipo1"]=> string(1) "1" ["grupoEquipo2"]=> string(1) "3" ["posicionEquipo2"]=> string(1) "2" } } 
            
            ["Semi Final Oro"]=> array(2) { 
                [1]=> array(3) { ["nombre"]=> string(16) "Semi Final Oro 1" 
                    ["equipoGanador1"]=> string(1) "0" ["equipoGanador2"]=> string(1) "2" } 
                [2]=> array(3) { ["nombre"]=> string(16) "Semi Final Oro 2" 
                    ["equipoGanador1"]=> string(1) "1" ["equipoGanador2"]=> string(1) "3" } } 
                    
            ["Final Oro"]=> array(1) { 
                [1]=> array(3) { ["nombre"]=> string(11) "Final Oro 1" ["equipoGanador1"]=> string(1) "4" ["equipoGanador2"]=> string(1) "5" } } } 
                
        ["plata"]=> array(3) { 
            ["Cuartos de Final Plata"]=> array(4) { 
                [1]=> array(5) { ["nombre"]=> string(24) "Cuartos de Final Plata 1" 
                    ["grupoEquipo1"]=> string(1) "1" ["posicionEquipo1"]=> string(1) "9" ["grupoEquipo2"]=> string(1) "2" ["posicionEquipo2"]=> string(2) "10" } 
                [2]=> array(5) { ["nombre"]=> string(24) "Cuartos de Final Plata 2" 
                    ["grupoEquipo1"]=> string(1) "2" ["posicionEquipo1"]=> string(1) "9" ["grupoEquipo2"]=> string(1) "1" ["posicionEquipo2"]=> string(2) "10" } 
                [3]=> array(5) { ["nombre"]=> string(24) "Cuartos de Final Plata 3" 
                    ["grupoEquipo1"]=> string(1) "3" ["posicionEquipo1"]=> string(1) "9" ["grupoEquipo2"]=> string(1) "4" ["posicionEquipo2"]=> string(2) "10" } 
                [4]=> array(5) { ["nombre"]=> string(24) "Cuartos de Final Plata 4" 
                    ["grupoEquipo1"]=> string(1) "4" ["posicionEquipo1"]=> string(1) "9" ["grupoEquipo2"]=> string(1) "3" ["posicionEquipo2"]=> string(2) "10" } } 
            ["Semi Final Plata"]=> array(2) { 
                [1]=> array(3) { ["nombre"]=> string(18) "Semi Final Plata 1" ["equipoGanador1"]=> string(1) "0" ["equipoGanador2"]=> string(1) "2" } 
                [2]=> array(3) { ["nombre"]=> string(18) "Semi Final Plata 2" ["equipoGanador1"]=> string(1) "1" ["equipoGanador2"]=> string(1) "3" } } 
            ["Final Plata"]=> array(1) { 
                [1]=> array(3) { ["nombre"]=> string(13) "Final Plata 1" ["equipoGanador1"]=> string(1) "4" ["equipoGanador2"]=> string(1) "5" } } } }
    */
}