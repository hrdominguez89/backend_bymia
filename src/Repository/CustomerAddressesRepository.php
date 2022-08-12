<?php

namespace App\Repository;

use App\Entity\CustomerAddresses;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method CustomerAddresses|null find($id, $lockMode = null, $lockVersion = null)
 * @method CustomerAddresses|null findOneBy(array $criteria, array $orderBy = null)
 * @method CustomerAddresses[]    findAll()
 * @method CustomerAddresses[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CustomerAddressesRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, CustomerAddresses::class);
    }

    // /**
    //  * @return CustomerAddresses[] Returns an array of CustomerAddresses objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('c.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?CustomerAddresses
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
