<?php

namespace App\Repository;

use App\Entity\PoolCompletion;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<PoolCompletion>
 */
class PoolCompletionRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, PoolCompletion::class);
    }

       /**
        * @return PoolCompletion[] Returns an array of PoolCompletion objects
        */
       public function findCorrespondance($value): array
       {
           return $this->createQueryBuilder('p')
               ->andWhere('p.player = :plid')
               ->andWhere('p.pool = :poid')
               ->setParameter('plid', $value['player'])
               ->setParameter('poid', $value['pool'])
               ->orderBy('p.id', 'ASC')
               ->setMaxResults(10)
               ->getQuery()
               ->getResult()
           ;
       }

       public function getStatistics($start, $end): array
       {

           return $this->createQueryBuilder('p')
           ->select('COUNT(p)')
               ->andWhere('p.createdAt > :pstart')
               ->andWhere('p.createdAt < :pend')
               ->setParameter('pstart', $start)
               ->setParameter('pend', $end)
               ->getQuery()
               ->getResult()
           ;
       }
    //    public function findOneBySomeField($value): ?PoolCompletion
    //    {
    //        return $this->createQueryBuilder('p')
    //            ->andWhere('p.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}
