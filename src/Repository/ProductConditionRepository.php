<?php

namespace App\Repository;

use App\Entity\ProductCondition;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method ProductCondition|null find($id, $lockMode = null, $lockVersion = null)
 * @method ProductCondition|null findOneBy(array $criteria, array $orderBy = null)
 * @method ProductCondition[]    findAll()
 * @method ProductCondition[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ProductConditionRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ProductCondition::class);
    }

    // /**
    //  * @return ProductCondition[] Returns an array of ProductCondition objects
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
    public function findOneBySomeField($value): ?ProductCondition
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
