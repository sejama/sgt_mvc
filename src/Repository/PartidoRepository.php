<?php

namespace App\Repository;

use App\Entity\Partido;
use Doctrine\DBAL\Connection;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Partido>
 */
class PartidoRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Partido::class);
    }

    public function obternerPartidoxRutaNumero(string $ruta, int $numero): ?Partido
    {
        return $this->createQueryBuilder('p')
            ->join('p.categoria', 'c')
            ->join('c.torneo', 't')
            ->where('t.ruta = :ruta')
            ->andWhere('p.numero = :numero')
            ->setParameter('ruta', $ruta)
            ->setParameter('numero', $numero)
            ->getQuery()
            ->getOneOrNullResult();
    }

    public function guardar(Partido $entity, bool $flush = true): void
    {
        $this->getEntityManager()->persist($entity);
        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function flush(): void
    {
        $this->getEntityManager()->flush();
    }

    public function buscarPartidosXTorneo(string $ruta): array
    {
        return $this->createQueryBuilder('p')
            ->select('p')
            ->join('p.categoria', 'c')
            ->join('c.torneo', 't')
            ->where('t.ruta = :ruta')
            ->setParameter('ruta', $ruta)
            ->addOrderBy('p.id', 'ASC')
            ->getQuery()
            ->getResult();
    }

    public function obtenerMaxNumeroPartidoXTorneo(string $ruta): int
    {
        $result = $this->createQueryBuilder('p')
            ->select('COALESCE(MAX(p.numero), 0)')
            ->join('p.categoria', 'c')
            ->join('c.torneo', 't')
            ->where('t.ruta = :ruta')
            ->setParameter('ruta', $ruta)
            ->getQuery()
            ->getSingleScalarResult();

        return (int) $result;
    }

    public function reservarRangoNumerosXTorneo(string $ruta, int $cantidad): int
    {
        if ($cantidad <= 0) {
            throw new \InvalidArgumentException('La cantidad de numeros a reservar debe ser mayor a cero.');
        }

        $connection = $this->getEntityManager()->getConnection();

        if ($connection->isTransactionActive()) {
            return $this->reservarRangoNumerosXTorneoInterno($connection, $ruta, $cantidad);
        }

        return (int) $connection->transactional(
            fn (Connection $connection): int => $this->reservarRangoNumerosXTorneoInterno($connection, $ruta, $cantidad)
        );
    }

    public function ejecutarEnTransaccion(callable $callback): mixed
    {
        $connection = $this->getEntityManager()->getConnection();

        if ($connection->isTransactionActive()) {
            return $callback();
        }

        return $connection->transactional(
            static fn (Connection $connection) => $callback()
        );
    }

    private function reservarRangoNumerosXTorneoInterno(Connection $connection, string $ruta, int $cantidad): int
    {
        $torneoId = $connection->fetchOne(
            'SELECT id FROM torneo WHERE ruta = :ruta FOR UPDATE',
            ['ruta' => $ruta]
        );

        if ($torneoId === false) {
            throw new \RuntimeException('No se encontro torneo para reservar numeracion de partidos.');
        }

        $torneoId = (int) $torneoId;

        $ultimoNumero = $connection->fetchOne(
            'SELECT ultimo_numero FROM torneo_partido_secuencia WHERE torneo_id = :torneoId FOR UPDATE',
            ['torneoId' => $torneoId]
        );

        if ($ultimoNumero === false) {
            $ultimoNumero = (int) $connection->fetchOne(
                'SELECT COALESCE(MAX(p.numero), 0)
                 FROM partido p
                 INNER JOIN categoria c ON c.id = p.categoria_id
                 WHERE c.torneo_id = :torneoId',
                ['torneoId' => $torneoId]
            );

            $connection->executeStatement(
                'INSERT INTO torneo_partido_secuencia (torneo_id, ultimo_numero) VALUES (:torneoId, :ultimoNumero)',
                [
                    'torneoId' => $torneoId,
                    'ultimoNumero' => $ultimoNumero,
                ]
            );
        } else {
            $ultimoNumero = (int) $ultimoNumero;
        }

        $inicioRango = $ultimoNumero + 1;
        $nuevoUltimoNumero = $ultimoNumero + $cantidad;

        $connection->executeStatement(
            'UPDATE torneo_partido_secuencia SET ultimo_numero = :nuevoUltimoNumero WHERE torneo_id = :torneoId',
            [
                'nuevoUltimoNumero' => $nuevoUltimoNumero,
                'torneoId' => $torneoId,
            ]
        );

        return $inicioRango;
    }

    public function buscarPartidoXCanchaHorario(string $ruta, int $partidoId, int $canchaId, \DateTimeImmutable $horario): ?Partido
    {
        return $this->createQueryBuilder('p')
            ->join('p.categoria', 'c')
            ->join('c.torneo', 't')
            ->where('p.cancha = :canchaId')
            ->andWhere('p.horario = :horario')
            ->andWhere('t.ruta = :ruta')
            ->andWhere('p.id != :partidoId')
            ->setParameter('ruta', $ruta)
            ->setParameter('partidoId', $partidoId)
            ->setParameter('canchaId', $canchaId)
            ->setParameter('horario', $horario)
            ->getQuery()
            ->getOneOrNullResult();
    }

    public function buscarPartidoXSedeHorario(string $ruta, int $partidoId, int $sedeId, \DateTimeImmutable $horario): ?Partido
    {
        return $this->createQueryBuilder('p')
            ->join('p.categoria', 'c')
            ->join('c.torneo', 't')
            ->join('p.cancha', 'can')
            ->join('can.sede', 's')
            ->where('s.id = :sedeId')
            ->andWhere('p.horario = :horario')
            ->andWhere('t.ruta = :ruta')
            ->andWhere('p.id != :partidoId')
            ->setParameter('ruta', $ruta)
            ->setParameter('partidoId', $partidoId)
            ->setParameter('sedeId', $sedeId)
            ->setParameter('horario', $horario)
            ->getQuery()
            ->getOneOrNullResult();
    }

    public function obtenerHorariosProgramadosXTorneo(string $ruta): array
    {
        return $this->createQueryBuilder('p')
            ->select('p.id, p.horario, can.id AS canchaId, s.id AS sedeId')
            ->join('p.categoria', 'c')
            ->join('c.torneo', 't')
            ->join('p.cancha', 'can')
            ->join('can.sede', 's')
            ->where('t.ruta = :ruta')
            ->andWhere('p.horario IS NOT NULL')
            ->andWhere('p.estado != :estadoCancelado')
            ->setParameter('ruta', $ruta)
            ->setParameter('estadoCancelado', 'Cancelado')
            ->orderBy('p.horario', 'ASC')
            ->getQuery()
            ->getResult();
    }

    public function existenOtrosPartidosProgramadosXTorneo(string $ruta, int $partidoId): bool
    {
        $cantidad = (int) $this->createQueryBuilder('p')
            ->select('COUNT(p.id)')
            ->join('p.categoria', 'c')
            ->join('c.torneo', 't')
            ->where('t.ruta = :ruta')
            ->andWhere('p.id != :partidoId')
            ->andWhere('p.cancha IS NOT NULL')
            ->andWhere('p.horario IS NOT NULL')
            ->andWhere('p.estado != :estadoCancelado')
            ->setParameter('ruta', $ruta)
            ->setParameter('partidoId', $partidoId)
            ->setParameter('estadoCancelado', 'Cancelado')
            ->getQuery()
            ->getSingleScalarResult();

        return $cantidad > 0;
    }

    public function buscarPartidosSinAsignarXTorneo(string $ruta): array
    {
        /*
        SELECT p.id AS id, p.numero, eLocal.nombre AS equipoLocal, eVisitante.nombre AS equipoVisitante, g.nombre AS grupo, c.nombre AS categoria, t.fecha_inicio_torneo AS fechaInicioTorneo, t.fecha_fin_torneo AS fechaFinTorneo 
        FROM partido AS p 
        JOIN grupo g ON p.grupo_id = g.id 
        JOIN categoria c ON g.categoria_id = c.id
        JOIN torneo t ON c.torneo_id = t.id
        JOIN equipo eLocal ON p.equipo_local_id = eLocal.id
        JOIN equipo eVisitante ON p.equipo_visitante_id = eVisitante.id
        WHERE t.ruta = 'xiv-sudamericano-master-voley-sf' and p.cancha_id IS NULL and p.estado != 'Cancelado'
        ORDER BY p.id ASC;
        */
        return $this->createQueryBuilder('p')
            ->select('p.id AS id, p.numero, eLocal.nombre AS equipoLocal, eLocal.logoPath AS equipoLocalLogoPath, eVisitante.nombre AS equipoVisitante, eVisitante.logoPath AS equipoVisitanteLogoPath, g.nombre AS grupo, c.nombre AS categoria, t.fechaInicioTorneo AS fechaInicioTorneo, t.fechaFinTorneo AS fechaFinTorneo')
            ->join('p.grupo', 'g')
            ->join('g.categoria', 'c')
            ->join('c.torneo', 't')
            ->join('p.equipoLocal', 'eLocal')
            ->join('p.equipoVisitante', 'eVisitante')
            ->where('t.ruta = :ruta')
            ->andWhere('p.cancha IS NULL')
            ->andWhere('p.estado != :estado')
            ->setParameter('ruta', $ruta)
            ->setParameter('estado', 'Cancelado')
            ->addOrderBy('p.id', 'ASC')
            ->getQuery()
            ->getResult();
    }

    public function buscarPartidosPlayOffGrupoXTorneo(string $ruta): array
    { 
        /*
        SELECT p.id AS id, p.numero, pc.nombre AS nombre, CONCAT(g1.nombre,'-',pc.posicion_equipo1) AS equipoLocal, CONCAT(g2.nombre,'-',pc.posicion_equipo2) AS equipoVisitante,
        c.nombre AS categoria, t.fecha_inicio_torneo AS fechaInicioTorneo, t.fecha_fin_torneo AS fechaFinTorneo
        FROM partido p 
        JOIN partido_config pc ON pc.partido_id = p.id
        JOIN categoria c ON c.id = p.categoria_id
        JOIN grupo g1 ON pc.grupo_equipo1_id = g1.id
        JOIN grupo g2 ON pc.grupo_equipo2_id = g2.id
        JOIN torneo t ON c.torneo_id = t.id
        WHERE t.ruta = 'xiv-sudamericano-master-voley-sf' and p.cancha_id IS NULL
        ORDER BY p.id ASC
        */

        return $this->createQueryBuilder('p')
            ->select('p.id AS id, p.numero, pc.nombre AS nombre, CONCAT(g1.nombre, \'-\', pc.posicionEquipo1) AS equipoLocal, CONCAT(g2.nombre, \'-\', pc.posicionEquipo2) AS equipoVisitante, c.nombre AS categoria, t.fechaInicioTorneo AS fechaInicioTorneo, t.fechaFinTorneo AS fechaFinTorneo')
            ->join('p.partidoConfig', 'pc')
            ->join('p.categoria', 'c')
            ->join('pc.grupoEquipo1', 'g1')
            ->join('pc.grupoEquipo2', 'g2')
            ->join('c.torneo', 't')
            ->where('t.ruta = :ruta')
            ->andWhere('p.cancha IS NULL')
            ->setParameter('ruta', $ruta)
            ->addOrderBy('p.id', 'ASC')
            ->getQuery()
            ->getResult();
    }

    public function buscarPartidosPlayOffFinalesXTorneo(string $ruta): array
    {
        /*
        SELECT p.id AS id, p.numero, pc.nombre AS nombre, CONCAT('Ganador ', pc1.nombre) AS equipoPartidoLocalGanador, CONCAT('Ganador ', pc2.nombre) AS equipoPartidoVisitanteGanador, CONCAT('Perdedor ', pc3.nombre) AS equipoPartidoLocalPerdedor, CONCAT('Perdedor ', pc4.nombre) AS equipoPartidoVisitantePerdedor,
            c.nombre AS categoria, t.fecha_inicio_torneo AS fechaInicioTorneo, t.fecha_fin_torneo AS fechaFinTorneo
        FROM partido p 
        JOIN partido_config pc ON pc.partido_id = p.id
        LEFT JOIN partido_config pc1 ON pc.ganador_partido1_id = pc1.partido_id
        LEFT JOIN partido_config pc2 ON pc.ganador_partido2_id = pc2.partido_id
        LEFT JOIN partido_config pc3 ON pc.perdedor_partido1_id = pc3.partido_id
        LEFT JOIN partido_config pc4 ON pc.perdedor_partido2_id = pc4.partido_id
        JOIN categoria c ON c.id = p.categoria_id
        JOIN torneo t ON c.torneo_id = t.id
        WHERE t.ruta = 'xv_master_voley' 
        AND p.cancha_id IS NULL 
        AND (pc.ganador_partido1_id IS NOT NULL OR pc.perdedor_partido1_id IS NOT NULL)
        AND (pc.ganador_partido2_id IS NOT NULL OR pc.perdedor_partido2_id IS NOT NULL)
        ORDER BY p.id ASC;
        */
        return $this->createQueryBuilder('p')
            ->select('p.id AS id, p.numero, pc.nombre AS nombre, 
                CONCAT(\'Ganador \', pc1.nombre) AS equipoPartidoLocalGanador, CONCAT(\'Ganador \', pc2.nombre) AS equipoPartidoVisitanteGanador,
                    \'\' AS equipoPartidoLocalPerdedor, \'\' AS equipoPartidoVisitantePerdedor,
                c.nombre AS categoria, t.fechaInicioTorneo AS fechaInicioTorneo, t.fechaFinTorneo AS fechaFinTorneo')
            ->join('p.partidoConfig', 'pc')
            ->leftJoin('pc.ganadorPartido1', 'p1')
            ->leftJoin('p1.partidoConfig', 'pc1')
            ->leftJoin('pc.ganadorPartido2', 'p2')
            ->leftJoin('p2.partidoConfig', 'pc2')
            ->join('p.categoria', 'c')
            ->join('c.torneo', 't')
            ->where('t.ruta = :ruta')
            ->andWhere('p.cancha IS NULL')
            ->andWhere('pc.ganadorPartido1 IS NOT NULL')
            ->andWhere('pc.ganadorPartido2 IS NOT NULL')
            ->setParameter('ruta', $ruta)
            ->addOrderBy('p.id', 'ASC')
            ->getQuery()
            ->getResult();
    }

    public function buscarPartidosProgramadosClasificatorioXTorneo(string $ruta): array
    {
        /*
            SELECT s.nombre sede, c.nombre cancha, p.horario hora, eLocal.nombre equipoLocal, eVisitante.nombre equipoVisitante, g.nombre grupo, cat.nombre categoria, 
            p.local_set1, p.visitante_set1, p.local_set2, p.visitante_set2, p.local_set3, p.visitante_set3, p.local_set4, p.visitante_set4, p.local_set5, p.visitante_set5
            FROM partido p 
            INNER JOIN cancha c ON p.cancha_id = c.id 
            INNER JOIN sede s ON c.sede_id = s.id 
            INNER JOIN grupo g ON g.id = p.grupo_id 
            INNER JOIN categoria cat ON cat.id = g.categoria_id 
            INNER JOIN equipo eLocal ON eLocal.id = p.equipo_local_id 
            INNER JOIN equipo eVisitante ON eVisitante.id = p.equipo_visitante_id 
            ORDER BY s.id, c.id, hora;
        */
        return $this->createQueryBuilder('p')
            ->select('p.id, p.numero, s.nombre AS sede, c.nombre AS cancha, p.horario AS horario, eLocal.nombre AS equipoLocal, eLocal.logoPath AS equipoLocalLogoPath, eVisitante.nombre AS equipoVisitante, eVisitante.logoPath AS equipoVisitanteLogoPath, g.nombre AS grupo, cat.nombre AS categoria, p.localSet1, p.visitanteSet1, p.localSet2, p.visitanteSet2, p.localSet3, p.visitanteSet3, p.localSet4, p.visitanteSet4, p.localSet5, p.visitanteSet5')
            ->join('p.cancha', 'c')
            ->join('c.sede', 's')
            ->join('p.grupo', 'g')
            ->join('g.categoria', 'cat')
            ->join('cat.torneo', 't')
            ->join('p.equipoLocal', 'eLocal')
            ->join('p.equipoVisitante', 'eVisitante')
            ->where('t.ruta = :ruta')
            ->setParameter('ruta', $ruta)
            ->orderBy('s.id, c.id, horario')
            ->getQuery()
            ->getResult();
    }

    public function buscarPartidosProgramadosPlayOffXTorneo(string $ruta): array
    {
        /*
        SELECT p.id, p.numero, s.nombre sede, c.nombre cancha, p.horario hora, CONCAT(g1.nombre,' ',pc.posicion_equipo1) equipoLocal, CONCAT(g2.nombre,' ',pc.posicion_equipo2) equipoVisitante, pc.nombre grupo, cat.nombre categoria,
        p.local_set1, p.visitante_set1, p.local_set2, p.visitante_set2, p.local_set3, p.visitante_set3, p.local_set4, p.visitante_set4, p.local_set5, p.visitante_set5
        FROM partido p 
        INNER JOIN cancha c ON p.cancha_id = c.id 
        INNER JOIN sede s ON c.sede_id = s.id 
        INNER JOIN categoria cat ON cat.id = p.categoria_id
        INNER JOIN partido_config pc ON pc.partido_id = p.id
        INNER JOIN grupo g1 ON g1.id = pc.grupo_equipo1_id  
        INNER JOIN grupo g2 ON g2.id = pc.grupo_equipo2_id 
        WHERE equipo_local_id IS null AND equipo_visitante_id IS null
        ORDER BY s.id, c.id, hora;

        SELECT p.id, p.numero, s.nombre sede, c.nombre cancha, p.horario hora, eLocal.nombre equipoLocal, eVisitante.nombre equipoVisitante, pc.nombre grupo, cat.nombre categoria,
        p.local_set1, p.visitante_set1, p.local_set2, p.visitante_set2, p.local_set3, p.visitante_set3, p.local_set4, p.visitante_set4, p.local_set5, p.visitante_set5
        FROM partido p 
        INNER JOIN cancha c ON p.cancha_id = c.id 
        INNER JOIN sede s ON c.sede_id = s.id 
        INNER JOIN categoria cat ON cat.id = p.categoria_id
        INNER JOIN partido_config pc ON pc.partido_id = p.id
        INNER JOIN equipo eLocal ON eLocal.id = p.equipo_local_id
        INNER JOIN equipo eVisitante ON eVisitante.id = p.equipo_visitante_id
        WHERE equipo_local_id IS NOT null AND equipo_visitante_id IS NOT null
        ORDER BY s.id, c.id, hora;

        return $this->createQueryBuilder('p')
            ->select('p.id, p.numero, s.nombre AS sede, c.nombre AS cancha, p.horario AS horario, CONCAT(g1.nombre, \' \', pc.posicionEquipo1) AS equipoLocal, CONCAT(g2.nombre, \' \', pc.posicionEquipo2) AS equipoVisitante, pc.nombre AS grupo, cat.nombre AS categoria, p.localSet1, p.visitanteSet1, p.localSet1, p.visitanteSet1, p.localSet2, p.visitanteSet2, p.localSet3, p.visitanteSet3, p.localSet4, p.visitanteSet4, p.localSet5, p.visitanteSet5')
            ->join('p.cancha', 'c')
            ->join('c.sede', 's')
            ->join('p.categoria', 'cat')
            ->join('p.partidoConfig', 'pc')
            ->join('pc.grupoEquipo1', 'g1')
            ->join('pc.grupoEquipo2', 'g2')
            ->where('p.equipoLocal IS NULL')
            ->andWhere('p.equipoVisitante IS NULL')
            ->orderBy('s.id, c.id, horario')
            ->getQuery()
            ->getResult();
        */

        return $this->createQueryBuilder('p')
            ->select('p.id, p.numero, s.nombre AS sede, c.nombre AS cancha, p.horario AS horario, 
                CASE 
                    WHEN p.equipoLocal IS NULL THEN CONCAT(g1.nombre, \' \', pc.posicionEquipo1)
                    ELSE eLocal.nombre 
                END AS equipoLocal,
                eLocal.logoPath AS equipoLocalLogoPath,
                CASE 
                    WHEN p.equipoVisitante IS NULL THEN CONCAT(g2.nombre, \' \', pc.posicionEquipo2)
                    ELSE eVisitante.nombre 
                END AS equipoVisitante,
                eVisitante.logoPath AS equipoVisitanteLogoPath,
                pc.nombre AS grupo, 
                cat.nombre AS categoria, 
                p.localSet1, p.visitanteSet1, p.localSet2, p.visitanteSet2, 
                p.localSet3, p.visitanteSet3, p.localSet4, p.visitanteSet4, 
                p.localSet5, p.visitanteSet5')
            ->join('p.cancha', 'c')
            ->join('c.sede', 's')
            ->join('p.categoria', 'cat')
            ->join('cat.torneo', 't')
            ->join('p.partidoConfig', 'pc')
            ->leftJoin('pc.grupoEquipo1', 'g1')
            ->leftJoin('pc.grupoEquipo2', 'g2')
            ->leftJoin('p.equipoLocal', 'eLocal')
            ->leftJoin('p.equipoVisitante', 'eVisitante')
            ->where('t.ruta = :ruta')
            ->andWhere('pc.grupoEquipo1 IS NOT NULL')
            ->andWhere('pc.grupoEquipo2 IS NOT NULL')
            ->setParameter('ruta', $ruta)
            ->orderBy('s.id, c.id, p.horario')
            ->getQuery()
            ->getResult();
            }

    public function buscarPartidosProgramadosPlayOffFinalesXTorneo(string $ruta): array
    {
        /*
        SELECT 
            p.id, p.numero, s.nombre sede, c.nombre cancha, p.horario hora, 
            (CASE 
                WHEN p.equipo_local_id IS NULL THEN 
                    CASE 
                        WHEN pc.ganador_partido1_id IS NOT NULL THEN CONCAT('Ganador ', pc1.nombre)
                        WHEN pc.perdedor_partido1_id IS NOT NULL THEN CONCAT('Perdedor ', pc1p.nombre)
                    END
                ELSE eLocal.nombre 
            END) AS equipoLocal,

            (CASE 
                WHEN p.equipo_visitante_id IS NULL THEN 
                    CASE 
                        WHEN pc.ganador_partido2_id IS NOT NULL THEN CONCAT('Ganador ', pc2.nombre)
                        WHEN pc.perdedor_partido2_id IS NOT NULL THEN CONCAT('Perdedor ', pc2p.nombre)
                    END
                ELSE eVisitante.nombre 
            END) AS equipoVisitante,
            pc.nombre grupo, cat.nombre categoria,
            p.local_set1, p.visitante_set1, p.local_set2, p.visitante_set2, 
            p.local_set3, p.visitante_set3, p.local_set4, p.visitante_set4, 
            p.local_set5, p.visitante_set5 
        FROM partido p 
        INNER JOIN cancha c ON p.cancha_id = c.id 
        INNER JOIN sede s ON c.sede_id = s.id 
        INNER JOIN categoria cat ON cat.id = p.categoria_id
        INNER JOIN partido_config pc ON pc.partido_id = p.id
        -- Ganadores
        LEFT JOIN partido p1 ON p1.id = pc.ganador_partido1_id
        LEFT JOIN partido_config pc1 ON pc1.partido_id = p1.id
        LEFT JOIN partido p2 ON p2.id = pc.ganador_partido2_id
        LEFT JOIN partido_config pc2 ON pc2.partido_id = p2.id
        -- Perdedores
        LEFT JOIN partido p1p ON p1p.id = pc.perdedor_partido1_id
        LEFT JOIN partido_config pc1p ON pc1p.partido_id = p1p.id
        LEFT JOIN partido p2p ON p2p.id = pc.perdedor_partido2_id
        LEFT JOIN partido_config pc2p ON pc2p.partido_id = p2p.id
        -- Equipos definidos
        LEFT JOIN equipo eLocal ON eLocal.id = p.equipo_local_id
        LEFT JOIN equipo eVisitante ON eVisitante.id = p.equipo_visitante_id
        WHERE 
            (
                p.equipo_local_id IS NOT NULL 
                OR pc.ganador_partido1_id IS NOT NULL 
                OR pc.perdedor_partido1_id IS NOT NULL
            )
            AND
            (
                p.equipo_visitante_id IS NOT NULL 
                OR pc.ganador_partido2_id IS NOT NULL 
                OR pc.perdedor_partido2_id IS NOT NULL
            )
        ORDER BY s.id, c.id, hora;
        */
        
        return $this->createQueryBuilder('p')
            ->select('p.id, p.numero, s.nombre AS sede, c.nombre AS cancha, p.horario AS horario, 
                (CASE 
                    WHEN p.equipoLocal IS NULL THEN 
                        CASE 
                            WHEN pc.ganadorPartido1 IS NOT NULL THEN CONCAT(\'Ganador \', pc1.nombre)
                            ELSE \'Sin definir\'
                        END
                    ELSE eLocal.nombre 
                END) AS equipoLocal,
                eLocal.logoPath AS equipoLocalLogoPath,
            (CASE 
                WHEN p.equipoVisitante IS NULL THEN 
                    CASE 
                        WHEN pc.ganadorPartido2 IS NOT NULL THEN CONCAT(\'Ganador \', pc2.nombre)
                        ELSE \'Sin definir\'
                    END
                ELSE eVisitante.nombre 
            END) AS equipoVisitante,
            eVisitante.logoPath AS equipoVisitanteLogoPath,
                pc.nombre AS grupo, 
                cat.nombre AS categoria, 
                p.localSet1, p.visitanteSet1, p.localSet2, p.visitanteSet2, 
                p.localSet3, p.visitanteSet3, p.localSet4, p.visitanteSet4, 
                p.localSet5, p.visitanteSet5')
            ->join('p.cancha', 'c')
            ->join('c.sede', 's')
            ->join('p.categoria', 'cat')
            ->join('cat.torneo', 't')
            ->join('p.partidoConfig', 'pc')
            //Ganador
            ->leftJoin('pc.ganadorPartido1', 'p1')
            ->leftJoin('p1.partidoConfig', 'pc1')
            ->leftJoin('pc.ganadorPartido2', 'p2')
            ->leftJoin('p2.partidoConfig', 'pc2')
            ->leftJoin('p.equipoLocal', 'eLocal')
            ->leftJoin('p.equipoVisitante', 'eVisitante')
            ->where('t.ruta = :ruta')
            ->andWhere(
                'p.equipoLocal IS NOT NULL OR pc.ganadorPartido1 IS NOT NULL'
            )
            ->andWhere(
                'p.equipoVisitante IS NOT NULL OR pc.ganadorPartido2 IS NOT NULL'
            )
            ->setParameter('ruta', $ruta)
            ->orderBy('s.id, c.id, p.horario')
            ->getQuery()
            ->getResult();
    }

    public function obtenerPartidoXNumero(int $numero): Partido
    {
        return $this->findOneBy(['numero' => $numero]);
    }

    public function obtenerPartidosXCategoriaClasificatorio(int $categoriaId): array
    {
        /*
        SELECT p.id, e1.nombre AS Local, p.local_set1, p.local_set2, p.local_set3, e2.nombre as Visitante, p.visitante_set1, p.visitante_set2, p.visitante_set3, p.tipo AS nombre
        FROM partido p
        INNER JOIN equipo e1 ON e1.id = p.equipo_local_id
        INNER JOIN equipo e2 ON e2.id = p.equipo_visitante_id
        WHERE p.tipo = 'Clasificatorio' AND p.categoria_id = 1 
        */

        return $this->createQueryBuilder('p')
            ->select('p.id, e1.nombre AS Local, p.localSet1, p.localSet2, p.localSet3, e2.nombre AS Visitante, p.visitanteSet1, p.visitanteSet2, p.visitanteSet3, p.tipo AS nombre')
            ->join('p.equipoLocal', 'e1')
            ->join('p.equipoVisitante', 'e2')
            ->where('p.tipo = :tipo')
            ->andWhere('p.categoria = :categoriaId')
            ->setParameter('tipo', 'Clasificatorio')
            ->setParameter('categoriaId', $categoriaId)
            ->getQuery()
            ->getResult();

    }

    public function obtenerPartidosXCategoriaEliminatoriaPostClasificatorio(int $categoriaId): array
    {
        /*
        SELECT p.id AS partidoID, pc.id AS configID, 
            (CASE
                WHEN p.equipo_local_id IS NULL THEN CONCAT(g1.nombre,' ', pc.posicion_equipo1)
                ELSE e1.nombre
            END) AS Local,
            p.local_set1, p.local_set2, p.local_set3,
            (CASE
                WHEN p.equipo_visitante_id IS NULL THEN CONCAT(g2.nombre,' ', pc.posicion_equipo2)
                ELSE e2.nombre
            END) AS Visitante,
            p.visitante_set1, p.visitante_set2, p.visitante_set3,
            pc.nombre
        FROM partido p 
        INNER JOIN partido_config pc ON pc.partido_id = p.id  
        INNER JOIN grupo g1 ON pc.grupo_equipo1_id = g1.id 
        INNER JOIN grupo g2 ON pc.grupo_equipo2_id = g2.id
        INNER JOIN equipo e1 ON p.equipo_local_id = e1.id
        INNER JOIN equipo e2 ON p.equipo_visitante_id = e2.id
        WHERE p.categoria_id = 1

        SELECT p.id AS partidoID, pc.id AS configID,
            (CASE 
                WHEN p.equipo_local_id IS NULL THEN CONCAT('Ganador ',pc1.nombre)
                ELSE p.equipo_local_id
            END) AS equipoLocal,
            (CASE
                WHEN p.equipo_visitante_id IS NULL THEN CONCAT('Ganador ',pc2.nombre) 
                ELSE p.equipo_visitante_id
            END) AS equipoVisitante,
            p.local_set1, p.local_set2, p.local_set3, p.local_set4, p.local_set5,
            p.visitante_set1, p.visitante_set2, p.visitante_set3, p.visitante_set4, p.visitante_set5,
            pc.nombre
        FROM partido p 
        INNER JOIN partido_config pc ON pc.partido_id = p.id
        INNER JOIN partido p1 ON p1.id = pc.ganador_partido1_id
        INNER JOIN partido_config pc1 ON pc1.partido_id = p1.id
        INNER JOIN partido p2 ON p2.id = pc.ganador_partido2_id
        INNER JOIN partido_config pc2 ON pc2.partido_id = p2.id
        WHERE p.categoria_id = 1

        SELECT 
    p.id AS partidoID, 
    pc.id AS configID,
    (CASE
        WHEN p.equipo_local_id IS NULL 
            THEN 
                (CASE 
                    WHEN pc.ganador_partido1_id IS NULL 
                        THEN CONCAT(g1.nombre, ' ', pc.posicion_equipo1)
                    ELSE CONCAT('Ganador ', pc1.nombre)
                END)
        ELSE e1.nombre
    END) AS equipoLocal,
    (CASE
        WHEN p.equipo_visitante_id IS NULL 
            THEN 
                (CASE 
                    WHEN pc.ganador_partido2_id IS NULL 
                        THEN CONCAT(g2.nombre, ' ', pc.posicion_equipo2)
                    ELSE CONCAT('Ganador ', pc2.nombre)
                END)
        ELSE e2.nombre
    END) AS equipoVisitante,
    p.local_set1, p.local_set2, p.local_set3, p.local_set4, p.local_set5,
    p.visitante_set1, p.visitante_set2, p.visitante_set3, p.visitante_set4, p.visitante_set5,
    pc.nombre
    FROM partido p
    INNER JOIN partido_config pc ON pc.partido_id = p.id
    LEFT JOIN grupo g1 ON pc.grupo_equipo1_id = g1.id
    LEFT JOIN grupo g2 ON pc.grupo_equipo2_id = g2.id
    LEFT JOIN equipo e1 ON p.equipo_local_id = e1.id
    LEFT JOIN equipo e2 ON p.equipo_visitante_id = e2.id
    LEFT JOIN partido p1 ON p1.id = pc.ganador_partido1_id
    LEFT JOIN partido_config pc1 ON pc1.partido_id = p1.id
    LEFT JOIN partido p2 ON p2.id = pc.ganador_partido2_id
    LEFT JOIN partido_config pc2 ON pc2.partido_id = p2.id
    WHERE p.categoria_id = 1;
        */
        return $this->createQueryBuilder('p')
            ->select('p.id AS partidoID, pc.id AS configID, 
            (CASE
            WHEN p.equipoLocal IS NULL 
                THEN 
                    (CASE 
                        WHEN pc.ganadorPartido1 IS NULL 
                            THEN CONCAT(g1.nombre, \' \', pc.posicionEquipo1)
                        ELSE CONCAT(\'Ganador \', pc1.nombre)
                    END)
            ELSE e1.nombre
            END) AS Local,
            p.localSet1, p.localSet2, p.localSet3,
            (CASE
                WHEN p.equipoVisitante IS NULL 
                    THEN 
                        (CASE 
                            WHEN pc.ganadorPartido2 IS NULL 
                                THEN CONCAT(g2.nombre, \' \', pc.posicionEquipo2)
                            ELSE CONCAT(\'Ganador \', pc2.nombre)
                        END)
                ELSE e2.nombre
            END) AS Visitante,
            p.visitanteSet1, p.visitanteSet2, p.visitanteSet3,
            pc.nombre')
            ->join('p.partidoConfig', 'pc')
            ->leftJoin('pc.grupoEquipo1', 'g1')
            ->leftJoin('pc.grupoEquipo2', 'g2')
            ->leftJoin('p.equipoLocal', 'e1')
            ->leftJoin('p.equipoVisitante', 'e2')
            ->leftJoin('pc.ganadorPartido1', 'p1')
            ->leftJoin('p1.partidoConfig', 'pc1')
            ->leftJoin('pc.ganadorPartido2', 'p2')
            ->leftJoin('p2.partidoConfig', 'pc2')
            ->where('p.categoria = :categoriaId')
            ->setParameter('categoriaId', $categoriaId)
            ->getQuery()
            ->getResult();
    }

    /*
    Ver los partidos de una categoria que tienen configuracion de partido
    
    SELECT partido.id, local.nombre as Local, visitante.nombre as Visitante, partido.estado, partido.tipo, partido_config.grupo_equipo1_id as Grupo1, partido_config.posicion_equipo1 as Posicion1, partido_config.grupo_equipo2_id as Grupo2, partido_config.posicion_equipo2 as Posicion2 
    FROM `partido` 
    INNER JOIN partido_config ON partido_config.partido_id = partido.id
    INNER JOIN equipo as local ON partido.equipo_local_id = local.id
    INNER JOIN equipo as visitante on partido.equipo_visitante_id = visitante.id
    WHERE partido.categoria_id = 1 AND grupo_equipo1_id IS NOT NULL AND grupo_equipo2_id IS NOT NULL
     */

    //    /**
    //     * @return Partido[] Returns an array of Partido objects
    //     */
    //    public function findByExampleField($value): array
    //    {
    //        return $this->createQueryBuilder('p')
    //            ->andWhere('p.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->orderBy('p.id', 'ASC')
    //            ->setMaxResults(10)
    //            ->getQuery()
    //            ->getResult()
    //        ;
    //    }

    //    public function findOneBySomeField($value): ?Partido
    //    {
    //        return $this->createQueryBuilder('p')
    //            ->andWhere('p.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}
