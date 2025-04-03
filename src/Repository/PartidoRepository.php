<?php

namespace App\Repository;

use App\Entity\Partido;
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

    public function guardar(Partido $entity, bool $flush = true): void
    {
        $this->getEntityManager()->persist($entity);
        if ($flush) {
            $this->getEntityManager()->flush();
        }
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

    public function buscarPartidoXCanchaHorario(int $canchaId, \DateTimeImmutable $horario): ?Partido
    {
        return $this->createQueryBuilder('p')
            ->where('p.cancha = :canchaId')
            ->andWhere('p.horario = :horario')
            ->setParameter('canchaId', $canchaId)
            ->setParameter('horario', $horario)
            ->getQuery()
            ->getOneOrNullResult();
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
            ->select('p.id AS id, p.numero, eLocal.nombre AS equipoLocal, eVisitante.nombre AS equipoVisitante, g.nombre AS grupo, c.nombre AS categoria, t.fechaInicioTorneo AS fechaInicioTorneo, t.fechaFinTorneo AS fechaFinTorneo')
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
        SELECT p.id AS id, p.numero, pc.nombre AS nombre, pc1.nombre AS equipoPartidoLocal, pc2.nombre AS equipoPartidoVisitante,
        c.nombre AS categoria, t.fecha_inicio_torneo AS fechaInicioTorneo, t.fecha_fin_torneo AS fechaFinTorneo
        FROM partido p 
        JOIN partido_config pc ON pc.partido_id = p.id
        JOIN partido_config pc1 ON pc.ganador_partido1_id = pc1.partido_id
        JOIN partido_config pc2 ON pc.ganador_partido2_id = pc2.partido_id
        JOIN categoria c ON c.id = p.categoria_id
        JOIN torneo t ON c.torneo_id = t.id
        WHERE t.ruta = 'xiv-sudamericano-master-voley-sf' AND p.cancha_id IS NULL AND pc.ganador_partido1_id IS NOT NULL AND pc.ganador_partido2_id IS NOT NULL
        ORDER BY p.id ASC
        */
        return $this->createQueryBuilder('p')
            ->select('p.id AS id, p.numero, pc.nombre AS nombre, pc1.nombre AS equipoPartidoLocal, pc2.nombre AS equipoPartidoVisitante, c.nombre AS categoria, t.fechaInicioTorneo AS fechaInicioTorneo, t.fechaFinTorneo AS fechaFinTorneo')
            ->join('p.partidoConfig', 'pc')
            ->join('pc.ganadorPartido1', 'p1')
            ->join('pc.ganadorPartido2', 'p2')
            ->join('p1.partidoConfig', 'pc1')
            ->join('p2.partidoConfig', 'pc2')
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
            p.localSet1, p.visitanteSet1, p.localSet2, p.visitanteSet2, p.localSet3, p.visitanteSet3, p.localSet4, p.visitanteSet4, p.localSet5, p.visitanteSet5
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
            ->select('p.id, p.numero, s.nombre AS sede, c.nombre AS cancha, p.horario AS horario, eLocal.nombre AS equipoLocal, eVisitante.nombre AS equipoVisitante, g.nombre AS grupo, cat.nombre AS categoria, p.localSet1, p.visitanteSet1, p.localSet2, p.visitanteSet2, p.localSet3, p.visitanteSet3, p.localSet4, p.visitanteSet4, p.localSet5, p.visitanteSet5')
            ->join('p.cancha', 'c')
            ->join('c.sede', 's')
            ->join('p.grupo', 'g')
            ->join('g.categoria', 'cat')
            ->join('p.equipoLocal', 'eLocal')
            ->join('p.equipoVisitante', 'eVisitante')
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
        */

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
    }

    public function buscarPartidosProgramadosPlayOffFinalesXTorneo(string $ruta): array
    {
        /*
        SELECT p.id, p.numero, s.nombre sede, c.nombre cancha, p.horario hora, CONCAT('Ganador ',pc1.nombre) equipoLocal, CONCAT('Ganador ',pc2.nombre) equipoVisitante, pc.nombre grupo, cat.nombre categoria,
        p.localSet1, p.visitanteSet1, p.localSet2, p.visitanteSet2, p.localSet3, p.visitanteSet3, p.localSet4, p.visitanteSet4, p.localSet5, p.visitanteSet5 
        FROM partido p 
        INNER JOIN cancha c ON p.cancha_id = c.id 
        INNER JOIN sede s ON c.sede_id = s.id 
        INNER JOIN categoria cat ON cat.id = p.categoria_id
        INNER JOIN partido_config pc ON pc.partido_id = p.id
        INNER JOIN partido p1 ON p1.id = pc.ganador_partido1_id
        INNER JOIN partido_config pc1 ON pc1.partido_id = p1.id
        INNER JOIN partido p2 ON p2.id = pc.ganador_partido2_id
        INNER JOIN partido_config pc2 ON pc2.partido_id = p2.id
        ORDER BY s.id, c.id, hora;
        */
        
        return $this->createQueryBuilder('p')
            ->select('p.id, p.numero, s.nombre AS sede, c.nombre AS cancha, p.horario AS horario, CONCAT(\'Ganador \', pc1.nombre) AS equipoLocal, CONCAT(\'Ganador \', pc2.nombre) AS equipoVisitante, pc.nombre AS grupo, cat.nombre AS categoria, p.localSet1, p.visitanteSet1, p.localSet2, p.visitanteSet2, p.localSet3, p.visitanteSet3, p.localSet4, p.visitanteSet4, p.localSet5, p.visitanteSet5')
            ->join('p.cancha', 'c')
            ->join('c.sede', 's')
            ->join('p.categoria', 'cat')
            ->join('p.partidoConfig', 'pc')
            ->join('pc.ganadorPartido1', 'p1')
            ->join('p1.partidoConfig', 'pc1')
            ->join('pc.ganadorPartido2', 'p2')
            ->join('p2.partidoConfig', 'pc2')
            ->orderBy('s.id, c.id, horario')
            ->getQuery()
            ->getResult();
    }

    public function obtenerPartidoXNumero(int $numero): Partido
    {
        return $this->findOneBy(['numero' => $numero]);
    }

    public function obtenerPartidosXCategoriaEliminatoriaPostClasificatorio(int $categoriaId): array
    {
        /*
        SELECT partido.id, equipo_local_id as Local, equipo_visitante_id as Visitante, estado, tipo, partido_config.grupo_equipo1_id as Grupo1, partido_config.posicion_equipo1 as Posicion1, partido_config.grupo_equipo2_id as Grupo2, partido_config.posicion_equipo2 as Posicion2 
        FROM `partido` 
        INNER JOIN partido_config ON partido_config.partido_id = partido.id
        WHERE `categoria_id` = 1 AND `equipo_local_id` IS NULL AND `equipo_visitante_id` IS NULL AND grupo_equipo1_id IS NOT NULL AND grupo_equipo2_id IS NOT NULL
        */
        return $this->createQueryBuilder('p')
            ->select('p.id, p.estado, p.tipo, g1.id AS Grupo1, pc.posicionEquipo1 AS Posicion1, g2.id AS Grupo2, pc.posicionEquipo2 AS Posicion2')
            ->join('p.partidoConfig', 'pc')
            ->join('pc.grupoEquipo1', 'g1')
            ->join('pc.grupoEquipo2', 'g2')
            ->where('p.categoria = :categoriaId')
            ->andWhere('p.equipoLocal IS NULL')
            ->andWhere('p.equipoVisitante IS NULL')
            ->andWhere('pc.grupoEquipo1 IS NOT NULL')
            ->andWhere('pc.grupoEquipo2 IS NOT NULL')
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
