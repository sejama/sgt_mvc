<?php

namespace App\Manager;

use App\Entity\Categoria;
use App\Entity\Grupo;
use App\Entity\Partido;
use App\Entity\PartidoConfig;
use App\Enum\TipoPartido;
use App\Exception\AppException;
use App\Manager\CanchaManager;
use App\Manager\ValidadorPartidoManager;
use App\Repository\CategoriaRepository;
use App\Repository\EquipoRepository;
use App\Repository\PartidoConfigRepository;
use App\Repository\PartidoRepository;

class PartidoManager
{
    public function __construct(
        private CanchaManager $canchaManager,
        private GrupoManager $grupoManager,
        private CategoriaRepository $categoriaRepository,
        private EquipoRepository $equipoRepository,
        private PartidoRepository $partidoRepository,
        private PartidoConfigRepository $partidoConfigRepository,
        private ValidadorPartidoManager $validadorPartidoManager
    ) {
    }

    public function obtenerPartidosXGrupo(int $grupoId): array
    {
        return $this->partidoRepository->findBy(['grupo' => $grupoId]);
    }

    public function obtenerPartidoxId(int $partidoId): Partido
    {
        return $this->partidoRepository->find($partidoId);
    }

    public function obtenerPartido(string $ruta, int $partidoNumero,): Partido
    {
        return $this->partidoRepository->obternerPartidoxRutaNumero(
            $ruta,
            $partidoNumero
        );
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
        $partidosOrdenados = [];
        $partidosProcesados = [];

        $this->agruparPartidosProgramados(
            $this->partidoRepository->buscarPartidosProgramadosClasificatorioXTorneo($ruta),
            $partidosOrdenados,
            $partidosProcesados
        );

        $this->agruparPartidosProgramados(
            $this->partidoRepository->buscarPartidosProgramadosPlayOffXTorneo($ruta),
            $partidosOrdenados,
            $partidosProcesados
        );

        $this->agruparPartidosProgramados(
            $this->partidoRepository->buscarPartidosProgramadosPlayOffFinalesXTorneo($ruta),
            $partidosOrdenados,
            $partidosProcesados
        );

        foreach ($partidosOrdenados as &$canchas) {
            foreach ($canchas as &$fechas) {
                foreach ($fechas as &$partidos) {
                    usort($partidos, static fn (array $a, array $b): int => strcmp((string) $a['hora'], (string) $b['hora']));
                }
            }
        }

        return $partidosOrdenados;
    }

    private function agruparPartidosProgramados(array $partidos, array &$partidosOrdenados, array &$partidosProcesados): void
    {
        foreach ($partidos as $partido) {
            if (!isset($partido['id'], $partido['horario'], $partido['sede'], $partido['cancha'])) {
                continue;
            }

            $partidoId = (int) $partido['id'];
            if (isset($partidosProcesados[$partidoId])) {
                continue;
            }

            if (!$partido['horario'] instanceof \DateTimeInterface) {
                continue;
            }

            $partidosProcesados[$partidoId] = true;

            $fecha = $partido['horario']->format('Y-m-d');
            $partido['fecha'] = $fecha;
            $partido['hora'] = $partido['horario']->format('H:i');

            $partidosOrdenados[$partido['sede']][$partido['cancha']][$fecha][] = $partido;
        }
    }

    public function obtenerPartidoXCancha(int $canchaId): array
    {
        return $this->partidoRepository->findBy(['cancha' => $canchaId]);
    }

    public function crearPartidoXCategoria(Categoria $categoria, array $partidosPlayOff): void
    {
        $this->partidoRepository->ejecutarEnTransaccion(function () use ($categoria, $partidosPlayOff): void {
            $this->validadorPartidoManager->validarPlayOff($partidosPlayOff);
            foreach ($this->grupoManager->obtenerGrupos($categoria) as $grupo) {
                $this->crearPartidosXGrupo($grupo);
            }
            $cantidadPartidosPlayOff = $this->contarPartidosPlayOff($partidosPlayOff);
            if ($cantidadPartidosPlayOff === 0) {
                return;
            }

            $numero = $this->partidoRepository->reservarRangoNumerosXTorneo($categoria->getTorneo()->getRuta(), $cantidadPartidosPlayOff);
            
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
        });
    }

    public function crearPartidosXGrupo(Grupo $grupo): void
    {
        $this->partidoRepository->ejecutarEnTransaccion(function () use ($grupo): void {
            $equipos = $grupo->getEquipo();
            $cantidadEquipos = count($equipos);
            $cantidadCruces = (int) (($cantidadEquipos * ($cantidadEquipos - 1)) / 2);

            if ($cantidadCruces === 0) {
                return;
            }

            $numero = $this->partidoRepository->reservarRangoNumerosXTorneo($grupo->getCategoria()->getTorneo()->getRuta(), $cantidadCruces);
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

                    $this->partidoRepository->guardar($partido, false);

                    if ($equipos[$i]->getEstado() === \App\Enum\EstadoEquipo::BORRADOR->value) {
                        $equipos[$i]->setEstado(\App\Enum\EstadoEquipo::ACTIVO->value);
                        $this->equipoRepository->guardar($equipos[$i], false);
                    }

                    if ($equipos[$j]->getEstado() === \App\Enum\EstadoEquipo::BORRADOR->value) {
                        $equipos[$j]->setEstado(\App\Enum\EstadoEquipo::ACTIVO->value);
                        $this->equipoRepository->guardar($equipos[$j], false);
                    }
                }
            }

            $this->partidoRepository->flush();
        });
    }

    public function obtenerSedesyCanchasXTorneo(string $ruta): array
    {
        return $this->canchaManager->obtenerSedesYCanchasByTorneo($ruta);
    }

    public function obtenerHorariosProgramadosXTorneo(string $ruta): array
    {
        $horariosProgramados = [
            'porCancha' => [],
            'porSede' => [],
        ];

        foreach ($this->partidoRepository->obtenerHorariosProgramadosXTorneo($ruta) as $partido) {
            if (!isset($partido['horario'], $partido['canchaId'], $partido['sedeId'])) {
                continue;
            }

            if (!$partido['horario'] instanceof \DateTimeInterface) {
                continue;
            }

            $fechaHora = $partido['horario']->format('Y-m-d\\TH:i');
            $canchaId = (string) $partido['canchaId'];
            $sedeId = (string) $partido['sedeId'];

            $horariosProgramados['porCancha'][$canchaId][$fechaHora] = true;
            $horariosProgramados['porSede'][$sedeId][$fechaHora] = true;
        }

        foreach ($horariosProgramados['porCancha'] as $canchaId => $horarios) {
            $horariosProgramados['porCancha'][$canchaId] = array_keys($horarios);
        }

        foreach ($horariosProgramados['porSede'] as $sedeId => $horarios) {
            $horariosProgramados['porSede'][$sedeId] = array_keys($horarios);
        }

        return $horariosProgramados;
    }

    public function editarPartido(string $ruta, int $partidoId, int $canchaId, string $horario): void
    {
        $horario = new \DateTimeImmutable(substr_replace($horario, '00', -2));

        $cancha = $this->canchaManager->obtenerCancha($canchaId);
        $sede = $cancha->getSede();

        if ($sede === null || $sede->getTorneo()?->getRuta() !== $ruta) {
            throw new AppException('La cancha seleccionada no pertenece al torneo actual.');
        }

        if ($this->partidoRepository->buscarPartidoXCanchaHorario($ruta, $partidoId, $canchaId, $horario)) {
            throw new AppException('Ya existe un partido programado en esa cancha y horario');
        }

        if ($this->partidoRepository->buscarPartidoXSedeHorario($ruta, $partidoId, (int) $sede->getId(), $horario)) {
            throw new AppException('Ya existe un partido programado en esa sede y horario');
        }

        $partido = $this->obtenerPartidoxId($partidoId);
        $torneo = $partido->getCategoria()?->getTorneo();
        $inicioTorneo = $torneo?->getFechaInicioTorneo();
        $hayOtrosPartidosProgramados = $this->partidoRepository->existenOtrosPartidosProgramadosXTorneo($ruta, $partidoId);

        if (
            $inicioTorneo !== null
            && $hayOtrosPartidosProgramados === false
            && $horario < $inicioTorneo
        ) {
            throw new AppException('El primer partido del torneo no puede programarse antes de la fecha y hora de inicio del torneo: ' . $inicioTorneo->format('d/m/Y H:i'));
        }

        $partido->setCancha($cancha);
        $partido->setHorario($horario);
        $partido->setEstado(\App\Enum\EstadoPartido::PROGRAMADO->value);

        $this->partidoRepository->guardar($partido);
        
        if ($partido->getEquipoLocal() !== null) {
            $equipoLocal = $partido->getEquipoLocal();
            if ($equipoLocal->getEstado() === \App\Enum\EstadoEquipo::BORRADOR->value) {
                $equipoLocal->setEstado(\App\Enum\EstadoEquipo::ACTIVO->value);
                $this->equipoRepository->guardar($equipoLocal);
            }
        }
       
        if ($partido->getEquipoVisitante() !== null) {
            $equipoVisitante = $partido->getEquipoVisitante();
            if ($equipoVisitante->getEstado() === \App\Enum\EstadoEquipo::BORRADOR->value) {
                $equipoVisitante->setEstado(\App\Enum\EstadoEquipo::ACTIVO->value);
                $this->equipoRepository->guardar($equipoVisitante);
            }
        }

    }

    public function crearPartidoManual(string $ruta, array $data): Partido
    {
        return $this->partidoRepository->ejecutarEnTransaccion(function () use ($ruta, $data): Partido {
            $categoria = $this->obtenerCategoriaDesdeData($data, 'crear_categoriaId', $ruta);
            $tipo = $this->obtenerTipoDesdeData($data, 'crear_tipo');
            $grupo = $this->obtenerGrupoDesdeData($data, 'crear_grupoId', $categoria);
            [$equipoLocal, $equipoVisitante] = $this->obtenerEquiposDesdeData($data, 'crear_equipoLocalId', 'crear_equipoVisitanteId', $categoria);

            if ($tipo === TipoPartido::CLASIFICATORIO->value && $grupo === null) {
                throw new AppException('Para un partido clasificatorio debe seleccionar un grupo.');
            }

            $partido = new Partido();
            $partido->setCancha(null);
            $partido->setHorario(null);
            $partido->setCategoria($categoria);
            $partido->setGrupo($tipo === TipoPartido::CLASIFICATORIO->value ? $grupo : null);
            $partido->setEstado(\App\Enum\EstadoPartido::BORRADOR->value);
            $partido->setTipo($tipo);
            $partido->setEquipoLocal($equipoLocal);
            $partido->setEquipoVisitante($equipoVisitante);
            $partido->setNumero($this->obtenerSiguienteNumeroPartido($ruta));

            $this->partidoRepository->guardar($partido);

            $usarConfig = isset($data['crear_usarConfig']) && $data['crear_usarConfig'] === '1';
            if ($usarConfig) {
                $this->sincronizarConfiguracionPartido($partido, $data, 'crear_');
            }

            return $partido;
        });
    }

    public function editarPartidoManual(string $ruta, array $data): Partido
    {
        $partidoId = (int)($data['editar_partidoId'] ?? 0);
        if ($partidoId <= 0) {
            throw new AppException('Debe seleccionar un partido para editar.');
        }

        $partido = $this->obtenerPartidoxId($partidoId);
        if ($partido->getCategoria()?->getTorneo()?->getRuta() !== $ruta) {
            throw new AppException('El partido no pertenece al torneo seleccionado.');
        }

        $categoria = $this->obtenerCategoriaDesdeData($data, 'editar_categoriaId', $ruta);
        $tipo = $this->obtenerTipoDesdeData($data, 'editar_tipo');
        $grupo = $this->obtenerGrupoDesdeData($data, 'editar_grupoId', $categoria);
        [$equipoLocal, $equipoVisitante] = $this->obtenerEquiposDesdeData($data, 'editar_equipoLocalId', 'editar_equipoVisitanteId', $categoria);

        if ($tipo === TipoPartido::CLASIFICATORIO->value && $grupo === null) {
            throw new AppException('Para un partido clasificatorio debe seleccionar un grupo.');
        }

        $partido->setCategoria($categoria);
        $partido->setTipo($tipo);
        $partido->setGrupo($tipo === TipoPartido::CLASIFICATORIO->value ? $grupo : null);
        $partido->setEquipoLocal($equipoLocal);
        $partido->setEquipoVisitante($equipoVisitante);

        $this->partidoRepository->guardar($partido);

        $usarConfig = isset($data['editar_usarConfig']) && $data['editar_usarConfig'] === '1';
        if ($usarConfig) {
            $this->sincronizarConfiguracionPartido($partido, $data, 'editar_');
        }

        return $partido;
    }

    private function obtenerSiguienteNumeroPartido(string $ruta): int
    {
        return $this->partidoRepository->reservarRangoNumerosXTorneo($ruta, 1);
    }

    private function contarPartidosPlayOff(array $partidosPlayOff): int
    {
        $cantidad = 0;

        foreach ($partidosPlayOff as $tiposPlayOff) {
            foreach ($tiposPlayOff as $partidoPlayOff) {
                $cantidad += count($partidoPlayOff);
            }
        }

        return $cantidad;
    }

    private function obtenerCategoriaDesdeData(array $data, string $key, string $ruta): Categoria
    {
        $categoriaId = (int)($data[$key] ?? 0);
        if ($categoriaId <= 0) {
            throw new AppException('Debe seleccionar una categoría válida.');
        }

        $categoria = $this->categoriaRepository->find($categoriaId);

        if (!$categoria instanceof Categoria) {
            throw new AppException('No se encontró la categoría seleccionada.');
        }

        if ($categoria->getTorneo()?->getRuta() !== $ruta) {
            throw new AppException('La categoría seleccionada no pertenece al torneo actual.');
        }

        return $categoria;
    }

    private function obtenerTipoDesdeData(array $data, string $key): string
    {
        $tipo = (string)($data[$key] ?? '');
        if (!in_array($tipo, TipoPartido::getValues(), true)) {
            throw new AppException('Debe seleccionar un tipo de partido válido.');
        }

        return $tipo;
    }

    private function obtenerGrupoDesdeData(array $data, string $key, Categoria $categoria): ?Grupo
    {
        $grupoId = (int)($data[$key] ?? 0);
        if ($grupoId <= 0) {
            return null;
        }

        $grupo = $this->grupoManager->obtenerGrupo($grupoId);
        if ($grupo->getCategoria()?->getId() !== $categoria->getId()) {
            throw new AppException('El grupo seleccionado no pertenece a la categoría elegida.');
        }

        return $grupo;
    }

    private function obtenerEquiposDesdeData(array $data, string $keyLocal, string $keyVisitante, Categoria $categoria): array
    {
        $localId = (int)($data[$keyLocal] ?? 0);
        $visitanteId = (int)($data[$keyVisitante] ?? 0);

        if ($localId > 0 && $visitanteId > 0 && $localId === $visitanteId) {
            throw new AppException('El equipo local y visitante no pueden ser el mismo.');
        }

        $equipoLocal = $localId > 0 ? $this->equipoRepository->find($localId) : null;
        $equipoVisitante = $visitanteId > 0 ? $this->equipoRepository->find($visitanteId) : null;

        if ($equipoLocal !== null && $equipoLocal->getCategoria()?->getId() !== $categoria->getId()) {
            throw new AppException('El equipo local no pertenece a la categoría seleccionada.');
        }

        if ($equipoVisitante !== null && $equipoVisitante->getCategoria()?->getId() !== $categoria->getId()) {
            throw new AppException('El equipo visitante no pertenece a la categoría seleccionada.');
        }

        return [$equipoLocal, $equipoVisitante];
    }

    private function sincronizarConfiguracionPartido(Partido $partido, array $data, string $prefix): void
    {
        $nombre = trim((string)($data[$prefix . 'config_nombre'] ?? ''));
        if ($nombre === '') {
            throw new AppException('Debe indicar el nombre de instancia/configuración del partido.');
        }

        $origen = (string)($data[$prefix . 'config_origen'] ?? '');
        if (!in_array($origen, ['grupos', 'ganadores'], true)) {
            throw new AppException('Debe seleccionar un origen de configuración válido.');
        }

        $partidoConfig = $partido->getPartidoConfig() ?? new PartidoConfig();
        $partidoConfig->setPartido($partido);
        $partidoConfig->setNombre($nombre);
        $partidoConfig->setGrupoEquipo1(null);
        $partidoConfig->setPosicionEquipo1(null);
        $partidoConfig->setGrupoEquipo2(null);
        $partidoConfig->setPosicionEquipo2(null);
        $partidoConfig->setGanadorPartido1(null);
        $partidoConfig->setGanadorPartido2(null);

        if ($origen === 'grupos') {
            $grupoEquipo1Id = (int)($data[$prefix . 'config_grupoEquipo1Id'] ?? 0);
            $grupoEquipo2Id = (int)($data[$prefix . 'config_grupoEquipo2Id'] ?? 0);
            $posicion1 = (int)($data[$prefix . 'config_posicionEquipo1'] ?? 0);
            $posicion2 = (int)($data[$prefix . 'config_posicionEquipo2'] ?? 0);

            if ($grupoEquipo1Id <= 0 || $grupoEquipo2Id <= 0 || $posicion1 <= 0 || $posicion2 <= 0) {
                throw new AppException('Debe completar grupos y posiciones para la configuración por grupos.');
            }

            $partidoConfig->setGrupoEquipo1($this->grupoManager->obtenerGrupo($grupoEquipo1Id));
            $partidoConfig->setGrupoEquipo2($this->grupoManager->obtenerGrupo($grupoEquipo2Id));
            $partidoConfig->setPosicionEquipo1($posicion1);
            $partidoConfig->setPosicionEquipo2($posicion2);
        }

        if ($origen === 'ganadores') {
            $ganadorPartido1Id = (int)($data[$prefix . 'config_ganadorPartido1Id'] ?? 0);
            $ganadorPartido2Id = (int)($data[$prefix . 'config_ganadorPartido2Id'] ?? 0);

            if ($ganadorPartido1Id <= 0 || $ganadorPartido2Id <= 0) {
                throw new AppException('Debe seleccionar ambos partidos de origen para la configuración por ganadores.');
            }

            if ($ganadorPartido1Id === $partido->getId() || $ganadorPartido2Id === $partido->getId()) {
                throw new AppException('Un partido no puede depender de sí mismo en la configuración.');
            }

            $ganadorPartido1 = $this->partidoRepository->find($ganadorPartido1Id);
            $ganadorPartido2 = $this->partidoRepository->find($ganadorPartido2Id);

            if ($ganadorPartido1 === null || $ganadorPartido2 === null) {
                throw new AppException('No se pudieron obtener los partidos de origen para la configuración.');
            }

            $partidoConfig->setGanadorPartido1($ganadorPartido1);
            $partidoConfig->setGanadorPartido2($ganadorPartido2);
        }

        $this->partidoConfigRepository->guardar($partidoConfig);
    }

    public function cargarResultado(Partido $partido, array $resultadoLocal, array $resultadoVisitante): void
    {   
        $partido->setLocalSet1($resultadoLocal[0] ? (int)$resultadoLocal[0] : null);
        $partido->setLocalSet2($resultadoLocal[1] ? (int)$resultadoLocal[1] : null);
        $partido->setLocalSet3($resultadoLocal[2] ? (int)$resultadoLocal[2] : null);

        $partido->setVisitanteSet1($resultadoVisitante[0] ? (int)$resultadoVisitante[0] : null);
        $partido->setVisitanteSet2($resultadoVisitante[1] ? (int)$resultadoVisitante[1] : null);
        $partido->setVisitanteSet3($resultadoVisitante[2] ? (int)$resultadoVisitante[2] : null);
        
        $partido->setEstado(\App\Enum\EstadoPartido::FINALIZADO->value);
        $this->partidoRepository->guardar($partido);

        $partidoConfig = $this->partidoConfigRepository->obtenerPartidoConfigXGanadorPartido($partido);
        $ganador = null; 
        $local = $visitante = 0;

        if ($partido->getLocalSet1() > $partido->getVisitanteSet1()) {
            $local++;
        } else {
            $visitante++;
        } 

        if ($partido->getLocalSet2() > $partido->getVisitanteSet2()) {
            $local++;
        } else {
            $visitante++;
        }

        if ($partido->getLocalSet3() !== null and $partido->getVisitanteSet3() !== null) {
            if ($partido->getLocalSet3() > $partido->getVisitanteSet3()) {
                $local++;
            } else {
                $visitante++;
            }
        }

        if ($local > $visitante) {
            $ganador = $partido->getEquipoLocal();
            $perdedor = $partido->getEquipoVisitante();
        } else {
            $ganador = $partido->getEquipoVisitante();
            $perdedor = $partido->getEquipoLocal();
        }
        

        if ($partidoConfig) {
            $partidoSiguiente = $partidoConfig->getPartido();
            if ($partidoConfig->getGanadorPartido1() === $partido) {
                $partidoSiguiente->setEquipoLocal($ganador);  
            } elseif ($partidoConfig->getGanadorPartido2() === $partido) {
                $partidoSiguiente->setEquipoVisitante($ganador); 
            }

            if ($partidoConfig->getPerdedorPartido1() === $partido) {
                $partidoSiguiente->setEquipoLocal($perdedor);
            } elseif ($partidoConfig->getPerdedorPartido2() === $partido) {
                $partidoSiguiente->setEquipoVisitante($perdedor);
            }

            // Guardar solo si $partidoSiguiente está definido
             if (isset($partidoSiguiente)) {
                $this->partidoRepository->guardar($partidoSiguiente);
            }
        }

    }

    public function obtenerPartidosXCategoriaClasificatorio(Categoria $categoria): array
    {
        $partidos = $this->partidoRepository->obtenerPartidosXCategoriaClasificatorio($categoria->getId());
        
        return $partidos;
    }

    public function obtenerPartidosXCategoriaEliminatoriaPostClasificatorio(Categoria $categoria): array
    {
        $todos = $this->partidoRepository->obtenerPartidosXCategoriaEliminatoriaPostClasificatorio($categoria->getId());
        $partidos = [
            'oro' => [],
            'plata' => [],
            'bronce' => [],
            'general' => [],
        ];

        foreach ($todos as $partido) {
            if (str_contains($partido['nombre'], 'Oro')) {
                $partidos['oro'][] = $partido;
            } elseif (str_contains($partido['nombre'], 'Plata')) {
                $partidos['plata'][] = $partido;
            } elseif (str_contains($partido['nombre'], 'Bronce')) {
                $partidos['bronce'][] = $partido;
            } else {
                $partidos['general'][] = $partido;
            }
        }
        return $partidos;
    }
}