<?php

namespace App\Repository;

use App\Entity\SubregionType;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method SubregionType|null find($id, $lockMode = null, $lockVersion = null)
 * @method SubregionType|null findOneBy(array $criteria, array $orderBy = null)
 * @method SubregionType[]    findAll()
 * @method SubregionType[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class SubregionTypeRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, SubregionType::class);
    }

    // /**
    //  * @return SubregionType[] Returns an array of SubregionType objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('s')
            ->andWhere('s.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('s.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?SubregionType
    {
        return $this->createQueryBuilder('s')
            ->andWhere('s.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
