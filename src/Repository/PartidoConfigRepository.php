<?php

namespace App\Repository;

use App\Entity\PartidoConfig;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<PartidoConfig>
 */
class PartidoConfigRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, PartidoConfig::class);
    }

    public function obtenerPartidoConfig($CategoriaId, $nombre): PartidoConfig
    {
        return $this->createQueryBuilder('p')
            ->join('p.partido', 'pa')
            ->where('pa.categoria = :categoriaId')
            ->andWhere('p.nombre = :nombre')
            ->setParameter('categoriaId', $CategoriaId)
            ->setParameter('nombre', $nombre)
            ->getQuery()
            ->getOneOrNullResult();
    }

    public function guardar(PartidoConfig $entity, bool $flush = true): void
    {
        $this->getEntityManager()->persist($entity);
        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    //    /**
    //     * @return PartidoConfig[] Returns an array of PartidoConfig objects
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

    //    public function findOneBySomeField($value): ?PartidoConfig
    //    {
    //        return $this->createQueryBuilder('p')
    //            ->andWhere('p.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}
