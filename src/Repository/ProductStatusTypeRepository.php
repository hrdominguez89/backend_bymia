<?php

namespace App\Repository;

use App\Entity\ProductStatusType;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method ProductStatusType|null find($id, $lockMode = null, $lockVersion = null)
 * @method ProductStatusType|null findOneBy(array $criteria, array $orderBy = null)
 * @method ProductStatusType[]    findAll()
 * @method ProductStatusType[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ProductStatusTypeRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ProductStatusType::class);
    }

    // /**
    //  * @return ProductStatusType[] Returns an array of ProductStatusType objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('p.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?ProductStatusType
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
