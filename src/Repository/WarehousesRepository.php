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

    public function findAllWarehouseAndInventories(): array
    {
        return $this->createQueryBuilder('w')
            ->select('
                w.id as warehouse_id_bymia,
                w.id3pl as warehouse_id_3pl,
                i.id as inventory_id_bymia,
                i.id3pl as inventory_id_3pl,
                i.cod as inventory_cod,
                i.name as inventory_name,
                i.created_at as inventory_created_at
            ')
            ->innerJoin('App:Inventory', 'i', 'WITH', 'w.id = i.warehouse')
            ->orderBy('w.id3pl,i.id3pl', 'ASC')
            ->getQuery()
            ->getResult();
    }
}
