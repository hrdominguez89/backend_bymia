<?php

namespace App\Repository;

use App\Entity\Warehouses;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Warehouses|null find($id, $lockMode = null, $lockVersion = null)
 * @method Warehouses|null findOneBy(array $criteria, array $orderBy = null)
 * @method Warehouses[]    findAll()
 * @method Warehouses[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class WarehousesRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Warehouses::class);
    }

    // /**
    //  * @return Warehouses[] Returns an array of Warehouses objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('w')
            ->andWhere('w.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('w.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Warehouses
    {
        return $this->createQueryBuilder('w')
            ->andWhere('w.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
