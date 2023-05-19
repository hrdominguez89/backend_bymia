<?php

namespace App\Repository;

use App\Entity\FavoriteProduct;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method FavoriteProduct|null find($id, $lockMode = null, $lockVersion = null)
 * @method FavoriteProduct|null findOneBy(array $criteria, array $orderBy = null)
 * @method FavoriteProduct[]    findAll()
 * @method FavoriteProduct[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class FavoriteProductRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, FavoriteProduct::class);
    }

    /**
     * @param $uid
     * @return array
     */
    public function findByUid($uid): array
    {
        $entityManager = $this->getEntityManager();

        return $entityManager->createQuery(
            'SELECT e, p
            FROM App\Entity\FavoriteProduct e 
            LEFT JOIN e.customerId c
            LEFT JOIN e.productId p
            WHERE c.id =:uid'
        )->setParameter('uid', $uid)->getResult();
    }

    /**
     * @param $newIds
     * @return int|mixed|string
     */
    public function findNewProduct($newIds)
    {
        $entityManager = $this->getEntityManager();

        return $entityManager->createQuery(
            'SELECT e
            FROM App\Entity\Product e
            WHERE e.id IN (:ids)'
        )->setParameter('ids', $newIds)->getResult();
    }

    /**
     * @param array $wlImport
     * @return array
     */
    public function getIds(array $wlImport): array
    {
        $ids = [];
        foreach ($wlImport as $item) {
            $ids[] = $item['id'];
        }

        return $ids;
    }


    public function findFavoriteProductByStatus($product_id, $customer_id, $status_id)
    {
        return $this->createQueryBuilder('f')
            ->where('f.product =:product_id')
            ->andWhere('f.customer =:customer_id')
            ->andWhere('f.status =:status_id')
            ->setParameter('product_id', $product_id)
            ->setParameter('customer_id', $customer_id)
            ->setParameter('status_id', $status_id)
            ->getQuery()
            ->getOneOrNullResult();
    }

    public function findAllFavoriteProductsByStatus($customer_id, $status_id)
    {
        return $this->createQueryBuilder('f')
            ->leftJoin('f.product', 'p')
            ->where('f.customer =:customer_id')
            ->andWhere('f.status =:status_id')
            ->andWhere('p.visible = true')
            ->setParameter('customer_id', $customer_id)
            ->setParameter('status_id', $status_id)
            ->getQuery()
            ->getResult();
    }
}
