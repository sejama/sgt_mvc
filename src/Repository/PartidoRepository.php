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
        return $this->createQueryBuilder('p')
            ->select('p.id AS id, p.numero, eLocal.nombre AS equipoLocal, eVisitante.nombre AS equipoVisitante, g.nombre AS grupo, c.nombre AS categoria, t.fechaInicioTorneo AS fechaInicioTorneo, t.fechaFinTorneo AS fechaFinTorneo')
            ->join('p.grupo', 'g')
            ->join('g.categoria', 'c')
            ->join('c.torneo', 't')
            ->join('p.equipoLocal', 'eLocal')
            ->join('p.equipoVisitante', 'eVisitante')
            ->where('t.ruta = :ruta')
            ->andWhere('p.cancha IS NULL')
            ->setParameter('ruta', $ruta)
            ->addOrderBy('p.id', 'ASC')
            ->getQuery()
            ->getResult();
    }

    public function buscarPartidosProgramadosXTorneo(string $ruta): array
    {
        return $this->createQueryBuilder('p')
            ->select('s.nombre AS sede, c.nombre AS cancha, p.horario AS horario, eLocal.nombre AS equipoLocal, eVisitante.nombre AS equipoVisitante, g.nombre AS grupo, cat.nombre AS categoria')
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

    /*
    SELECT s.nombre sede, c.nombre cancha, P.horario hora, eLocal.nombre equipoLocal, eVisitante.nombre equipoVisitante, g.nombre grupo, cat.nombre categoria 
    FROM partido p 
    INNER JOIN cancha c ON p.cancha_id = c.id 
    INNER JOIN sede s ON c.sede_id = s.id 
    INNER JOIN grupo g ON g.id = p.grupo_id 
    INNER JOIN categoria cat ON cat.id = g.categoria_id 
    INNER JOIN equipo eLocal ON eLocal.id = p.equipo_local_id 
    INNER JOIN equipo eVisitante ON eVisitante.id = p.equipo_visitante_id 
    ORDER BY s.id, c.id, hora;
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
