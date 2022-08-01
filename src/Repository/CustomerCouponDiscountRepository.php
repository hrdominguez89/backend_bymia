<?php

namespace App\Repository;

use App\Entity\CustomerCouponDiscount;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method CustomerCouponDiscount|null find($id, $lockMode = null, $lockVersion = null)
 * @method CustomerCouponDiscount|null findOneBy(array $criteria, array $orderBy = null)
 * @method CustomerCouponDiscount[]    findAll()
 * @method CustomerCouponDiscount[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CustomerCouponDiscountRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, CustomerCouponDiscount::class);
    }

    /**
     * @param $cId
     * @return int|mixed|string|null
     */
    public function findOneByCustomerId($cId)
    {
        try {

            $entityManager = $this->getEntityManager();

            return $entityManager->createQuery(
                'SELECT e
            FROM App\Entity\CustomerCouponDiscount e
            LEFT JOIN e.customerId c
            WHERE c.id =:cId AND e.applied = FALSE'
            )->setParameter('cId', $cId)->getOneOrNullResult();

        } catch (\Exception $e) {

        }

        return null;
    }
}
