<?php

namespace App\Repository;

use App\Entity\CouponDiscount;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method CouponDiscount|null find($id, $lockMode = null, $lockVersion = null)
 * @method CouponDiscount|null findOneBy(array $criteria, array $orderBy = null)
 * @method CouponDiscount[]    findAll()
 * @method CouponDiscount[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CouponDiscountRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, CouponDiscount::class);
    }

    /**
     * @param $nro
     * @return int|mixed|string|null
     */
    public function findOneByNro($nro)
    {
        try{

            $entityManager = $this->getEntityManager();

            return $entityManager->createQuery(
                'SELECT e
            FROM App\Entity\CouponDiscount e
            WHERE e.nro =:nro AND e.numberOfUses > 0'
            )->setParameter('nro', $nro)->getOneOrNullResult();

        } catch (\Exception $ex){

        }
        return null;
    }

}
