<?php

namespace App\Repository;

use App\Entity\ShippingTypes;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<ShippingTypes>
 *
 * @method ShippingTypes|null find($id, $lockMode = null, $lockVersion = null)
 * @method ShippingTypes|null findOneBy(array $criteria, array $orderBy = null)
 * @method ShippingTypes[]    findAll()
 * @method ShippingTypes[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ShippingTypesRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ShippingTypes::class);
    }

    public function add(ShippingTypes $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(ShippingTypes $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

//    /**
//     * @return ShippingTypes[] Returns an array of ShippingTypes objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('s')
//            ->andWhere('s.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('s.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?ShippingTypes
//    {
//        return $this->createQueryBuilder('s')
//            ->andWhere('s.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
