<?php

namespace App\Repository;

use App\Entity\Shopping;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Shopping|null find($id, $lockMode = null, $lockVersion = null)
 * @method Shopping|null findOneBy(array $criteria, array $orderBy = null)
 * @method Shopping[]    findAll()
 * @method Shopping[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ShoppingRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Shopping::class);
    }

    /**
     * @param $uid
     * @return array
     */
    public function findByUid($uid): array
    {
        $entityManager = $this->getEntityManager();

        $dql = 'SELECT e
            FROM App\Entity\Shopping e 
            LEFT JOIN e.customerId c
            WHERE c.id =:uid';

        return $entityManager->createQuery($dql)->setParameter('uid', $uid)->getResult();
    }

    /**
     * @param $uid
     * @param $oid
     * @return array
     */
    public function findByUidOrderId($uid, $oid): array
    {
        $entityManager = $this->getEntityManager();

        $dql = 'SELECT e
            FROM App\Entity\Shopping e 
            LEFT JOIN e.customerId c
            LEFT JOIN e.shoppingOrderId o
            WHERE c.id =:uid AND o.id =:oid';

        return $entityManager
            ->createQuery($dql)
            ->setParameters(['uid'=> $uid, 'oid' => $oid])
            ->getResult();
    }

    /**
     * @param $uid
     * @param array $ids
     * @return array
     */
    public function findByUidIds($uid, array $ids): array
    {
        $entityManager = $this->getEntityManager();

        $dql = 'SELECT e
            FROM App\Entity\Shopping e 
            LEFT JOIN e.customerId c
            LEFT JOIN e.productId p
            WHERE c.id =:uid AND p.id IN (:ids)';

        return $entityManager
            ->createQuery($dql)
            ->setParameter('uid', $uid)
            ->setParameter('ids', $ids)
            ->getResult();
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
     * @param array $ids
     * @param array $oldIds
     * @return array
     */
    public function getNewIds(array $ids, array $oldIds): array
    {
        return count($oldIds) == 0 ? $ids : array_diff($ids, $oldIds);
    }

    public function summary($year, $month)
    {
        $sql = "select date(mo.date) as date, count(mo.id) as cant , sum(mo.total) as amount
                    from mia_order as mo
                    
                    where extract(year from mo.date) = '$year'
                    and extract(month from mo.date) = '$month'
                    
                    group by date(mo.date)
                    order by date(mo.date) asc";
        $statement = $this->getEntityManager()->getConnection()->prepare($sql);
        $statement->executeQuery();
        return $statement->fetchAll();
    }

    public function summaryByMonth($year)
    {
        $sql = "select extract(month from mo.date) as month, count(mo.id) as cant , sum(mo.total) as amount
                    from mia_order as mo
                    where extract(year from mo.date) = '$year'
                    group by extract(year from mo.date), extract(month from mo.date)
                    order by extract(year from mo.date) desc ,extract(month from mo.date) asc";
        $statement = $this->getEntityManager()->getConnection()->prepare($sql);
        $statement->executeQuery();
        return $statement->fetchAll();
    }

    public function bestSeller($month, $year)
    {
        $sql = "select p.name, sum(moi.quantity) as sales, sum(p.price*moi.quantity-mo.discount) as amount , p.price
                    from mia_product as p                    
                    inner join mia_order_items as moi on moi.pid = p.id
                    inner join mia_order as mo on mo.id = moi.order_id
                    where extract(month from date(mo.date)) = '$month' and extract(year from mo.date) = '$year'
                    group by p.id
                    order by sales desc";
        $statement = $this->getEntityManager()->getConnection()->prepare($sql);
        $statement->executeQuery();
        return $statement->fetchAll();
    }

    public function bestCategory($month, $year, int $limit)
    {
        $sql = "select mct.id,mct.image,mct.name, sum(moi.quantity) as sales, sum(p.price*moi.quantity-mo.discount) as amount 
                    from mia_sub_category as msct
                    inner join mia_category as mct on msct.categoria_id = mct.id
                    inner join mia_product_subcategories as mpsct on mpsct.sub_categoria_id = msct.id
                    inner join mia_product as p on mpsct.product_id = p.id
                    inner join mia_order_items as moi on moi.pid = p.id
                    inner join mia_order as mo on mo.id = moi.order_id
                    where extract(month from date(mo.date)) = '$month' and extract(year from mo.date) = '$year'
                    group by mct.id
                    order by sales desc
                    limit $limit";
        $statement = $this->getEntityManager()->getConnection()->prepare($sql);
        $statement->executeQuery();
        return $statement->fetchAll();
    }

    public function betterCustomer($month, $year)
    {
        $sql = "select c.name, max(c.billing_last_name) as last_name, max(c.image) as image, max(c.email) as email, max(c.billing_country) as country, max(c.billing_state) as province, max(c.billing_city) as municipality, max(c.billing_address) as direction,sum(mo.total) as amount, count(mo.id) as cant
                    from mia_customer as c
                    inner join mia_order as mo on mo.customer_id = c.id
                    where extract(month from date(mo.date)) = '$month' and extract(year from mo.date) = '$year'
                    group by c.id
                    order by cant desc
                    offset 0
                    limit 10";
        $statement = $this->getEntityManager()->getConnection()->prepare($sql);
        $statement->executeQuery();
        return $statement->fetchAll();
    }

    public function bestBrand($month, $year)
    {
        $sql = "select b.name, b.image, sum(moi.quantity) as sales, sum(p.price*moi.quantity-mo.discount) as amount 
                    from mia_brand as b
                    inner join mia_product as p on p.brand_id = b.id
                    inner join mia_order_items as moi on moi.pid = p.id
                    inner join mia_order as mo on mo.id = moi.order_id
                    where extract(month from date(mo.date)) = '$month' and extract(year from mo.date) = '$year'
                    group by b.id
                    order by amount desc";
        $statement = $this->getEntityManager()->getConnection()->prepare($sql);
        $statement->executeQuery();
        return $statement->fetchAll();
    }
}
