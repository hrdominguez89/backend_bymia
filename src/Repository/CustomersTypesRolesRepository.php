<?php

namespace App\Repository;

use App\Entity\CustomersTypesRoles;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method CustomersTypesRoles|null find($id, $lockMode = null, $lockVersion = null)
 * @method CustomersTypesRoles|null findOneBy(array $criteria, array $orderBy = null)
 * @method CustomersTypesRoles[]    findAll()
 * @method CustomersTypesRoles[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CustomersTypesRolesRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, CustomersTypesRoles::class);
    }

    // /**
    //  * @return CustomersTypesRoles[] Returns an array of CustomersTypesRoles objects
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
    public function findOneBySomeField($value): ?CustomersTypesRoles
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */

    public function findCustomerTypesRole()
    {
        return $this->getEntityManager()
            ->createQuery('
                SELECT
                c.id,
                c.name
    
                FROM App:CustomersTypesRoles c
    
                ORDER BY c.id
                ')
            ->getResult();
    }
}
