<?php

namespace App\Manager;

use App\Entity\Categoria;
use App\Entity\Grupo;
use App\Entity\Partido;
use App\Entity\PartidoConfig;
use App\Exception\AppException;
use App\Manager\CanchaManager;
use App\Repository\PartidoConfigRepository;
use App\Repository\PartidoRepository;
use App\Utils\GenerarPdf;

class PartidoManager
{
    public function __construct(
        private CanchaManager $canchaManager,
        private GrupoManager $grupoManager,
        private PartidoRepository $partidoRepository,
        private PartidoConfigRepository $partidoConfigRepository,
    ) {
    }

    public function obtenerPartidosXGrupo(int $grupoId): array
    {
        return $this->partidoRepository->findBy(['grupo' => $grupoId]);
    }

    public function obtenerPartido(int $partidoId): Partido
    {
        return $this->partidoRepository->findOneBy(['id' => $partidoId]);
    }

    public function obtenerPartidosXTorneo(string $ruta): array
    {
        return $this->partidoRepository->buscarPartidosXTorneo($ruta);
    }

    public function obtenerPartidosSinAsignarXTorneo($ruta): array
    {
        $partidos = [];
        $partidos['clasificatorios'] = $this->partidoRepository->buscarPartidosSinAsignarXTorneo($ruta);
        $partidos['eliminatorias'] = $this->partidoRepository->buscarPartidosPlayOffGrupoXTorneo($ruta);
        $partidos['finales'] = $this->partidoRepository->buscarPartidosPlayOffFinalesXTorneo($ruta);
        return $partidos;
    }

    public function obtenerPartidosProgramadosXTorneo($ruta): array
    {
        $paritdosOrdenados = [];
        foreach ($this->partidoRepository->buscarPartidosProgramadosXTorneo($ruta) as $partido) {
            $partido['fecha'] = $partido['horario']->format('Y-m-d');
            $partido['hora'] = $partido['horario']->format('H:i');
            $paritdosOrdenados[$partido['sede']][$partido['cancha']][] = $partido;
        }
        return $paritdosOrdenados;
    }

    public function obtenerPartidoXCancha(int $canchaId): array
    {
        return $this->partidoRepository->findBy(['cancha' => $canchaId]);
    }

    public function crearPartidoXCategoria(Categoria $categoria, array $partidosPlayOff): void
    {
        $this->validatePlayOff($partidosPlayOff);
        foreach ($this->grupoManager->obtenerGrupos($categoria) as $grupo) {
            $this->crearPartidosXGrupo($grupo);
        }
        $numPartido = $this->obtenerPartidosXTorneo($categoria->getTorneo()->getRuta());
        $numero = count($numPartido) + 1;
        
        foreach ($partidosPlayOff as $tiposPlayOff) {
            $partidoId = [];
            foreach ($tiposPlayOff as $partidoPlayOff) {
                foreach ($partidoPlayOff as $playOff) {
                    
                    $partido = new Partido();
                    $partido->setCancha(null);
                    $partido->setGrupo(null);
                    $partido->setCategoria($categoria);
                    $partido->setEstado(\App\Enum\EstadoPartido::BORRADOR->value);
                    $partido->setTipo(\App\Enum\TipoPartido::ELIMINATORIO->value);
                    $partido->setEquipoLocal(null);
                    $partido->setEquipoVisitante(null);
                    $partido->setNumero($numero++);
                    
                    $this->partidoRepository->guardar($partido);
                    
                    $partidoConfig = new PartidoConfig();
                    $partidoConfig->setPartido($partido);
                    $partidoConfig->setNombre($playOff['nombre']);

                    if (isset($playOff['grupoEquipo1']) && isset($playOff['posicionEquipo1']) && isset($playOff['grupoEquipo2']) && isset($playOff['posicionEquipo2'])) {    
                        $partidoConfig->setGrupoEquipo1($this->grupoManager->obtenerGrupo((int)$playOff['grupoEquipo1']));
                        $partidoConfig->setPosicionEquipo1($playOff['posicionEquipo1']);
                        $partidoConfig->setGrupoEquipo2($this->grupoManager->obtenerGrupo((int)$playOff['grupoEquipo2']));
                        $partidoConfig->setPosicionEquipo2($playOff['posicionEquipo2']);
                    }

                    if (isset($playOff['equipoGanador1']) && isset($playOff['equipoGanador2'])) {
                        $partidoConfig->setGanadorPartido1($this->partidoRepository->obtenerPartidoXNumero($partidoId[(int)$playOff['equipoGanador1']]['partidoNumero']));
                        $partidoConfig->setGanadorPartido2($this->partidoRepository->obtenerPartidoXNumero($partidoId[(int)$playOff['equipoGanador2']]['partidoNumero']));
                    }

                    $this->partidoConfigRepository->guardar($partidoConfig);
                    $partidoId[] = [
                        'partidoNumero' => $partido->getNumero(),
                    ];
                }
            }
        }
    }

    public function crearPartidosXGrupo(Grupo $grupo): void
    {
        $equipos = $grupo->getEquipo();
        $numPartido = $this->obtenerPartidosXTorneo($grupo->getCategoria()->getTorneo()->getRuta());
        $numero = count($numPartido) + 1;
        for ($i = 0; $i < count($equipos); $i++) {
            for ($j = $i + 1; $j < count($equipos); $j++) {
                $partido = new Partido();
                $partido->setCancha(null);
                $partido->setGrupo($grupo);
                $partido->setCategoria($grupo->getCategoria());
                $partido->setEstado(\App\Enum\EstadoPartido::BORRADOR->value);
                $partido->setTipo(\App\Enum\TipoPartido::CLASIFICATORIO->value);
                $partido->setEquipoLocal($equipos[$i]);
                $partido->setEquipoVisitante($equipos[$j]);
                $partido->setNumero($numero++);

                $this->partidoRepository->guardar($partido);
            }
        }
    }

    public function obtenerSedesyCanchasXTorneo(string $ruta): array
    {
        return $this->canchaManager->obtenerSedesYCanchasByTorneo($ruta);
    }

    public function editarPartido(string $ruta, int $partidoId, int $canchaId, string $horario): void
    {
        $horario = new \DateTimeImmutable(substr_replace($horario, '00', -2));

        if ($this->partidoRepository->buscarPartidoXCanchaHorario($canchaId, $horario)) {
            throw new AppException('Ya existe un partido programado en esa cancha y horario');
        }
        $partido = $this->obtenerPartido($partidoId);
        $partido->setCancha($this->canchaManager->obtenerCancha($canchaId));
        $partido->setHorario($horario);

        $pdf = new GenerarPdf();
        $pdf->generarPdf($partido, $ruta);
        $this->partidoRepository->guardar($partido);
    }

    public function cargarResultado(int $partidoId, array $resultadoLocal, array $resultadoVisitante): void
    {
        $partido = $this->obtenerPartido($partidoId);
        
        $partido->setLocalSet1((int)$resultadoLocal[0]);
        $partido->setLocalSet2((int)$resultadoLocal[1]);
        $partido->setLocalSet3((int)$resultadoLocal[2]);

        $partido->setVisitanteSet1((int)$resultadoVisitante[0]);
        $partido->setVisitanteSet2((int)$resultadoVisitante[1]);
        $partido->setVisitanteSet3((int)$resultadoVisitante[2]);
        
        $partido->setEstado(\App\Enum\EstadoPartido::FINALIZADO->value);
        $this->partidoRepository->guardar($partido);
    }
    /**
     * Validar que los equipos no sean nulos: Se verifica que grupoEquipo1, posicionEquipo1, grupoEquipo2 y posicionEquipo2 no estén vacíos.
     * Validar que las posiciones sean válidas: Se verifica que grupoEquipo1, posicionEquipo1, grupoEquipo2 y posicionEquipo2 sean números válidos.
     * Validar que los nombres de los partidos no estén vacíos: Se verifica que nombre no esté vacío.
     * Validar que los equipos ganadores sean válidos: Se verifica que equipoGanador1 y equipoGanador2 sean números válidos si están presentes.
     */
    private function validatePlayOff(array $partidosPlayOff): void
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
    
}
/**
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
            
    ["plata"]=> array(3) { ["Cuartos de Final Plata"]=> array(4) { [1]=> array(5) { ["nombre"]=> string(24) "Cuartos de Final Plata 1" ["grupoEquipo1"]=> string(1) "1" ["posicionEquipo1"]=> string(1) "9" ["grupoEquipo2"]=> string(1) "2" ["posicionEquipo2"]=> string(2) "10" } [2]=> array(5) { ["nombre"]=> string(24) "Cuartos de Final Plata 2" ["grupoEquipo1"]=> string(1) "2" ["posicionEquipo1"]=> string(1) "9" ["grupoEquipo2"]=> string(1) "1" ["posicionEquipo2"]=> string(2) "10" } [3]=> array(5) { ["nombre"]=> string(24) "Cuartos de Final Plata 3" ["grupoEquipo1"]=> string(1) "3" ["posicionEquipo1"]=> string(1) "9" ["grupoEquipo2"]=> string(1) "4" ["posicionEquipo2"]=> string(2) "10" } [4]=> array(5) { ["nombre"]=> string(24) "Cuartos de Final Plata 4" ["grupoEquipo1"]=> string(1) "4" ["posicionEquipo1"]=> string(1) "9" ["grupoEquipo2"]=> string(1) "3" ["posicionEquipo2"]=> string(2) "10" } } ["Semi Final Plata"]=> array(2) { [1]=> array(3) { ["nombre"]=> string(18) "Semi Final Plata 1" ["equipoGanador1"]=> string(1) "0" ["equipoGanador2"]=> string(1) "2" } [2]=> array(3) { ["nombre"]=> string(18) "Semi Final Plata 2" ["equipoGanador1"]=> string(1) "1" ["equipoGanador2"]=> string(1) "3" } } ["Final Plata"]=> array(1) { [1]=> array(3) { ["nombre"]=> string(13) "Final Plata 1" ["equipoGanador1"]=> string(1) "4" ["equipoGanador2"]=> string(1) "5" } } } }
 */