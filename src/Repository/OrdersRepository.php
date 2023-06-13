<?php

namespace App\Repository;

use App\Entity\Orders;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Orders>
 *
 * @method Orders|null find($id, $lockMode = null, $lockVersion = null)
 * @method Orders|null findOneBy(array $criteria, array $orderBy = null)
 * @method Orders[]    findAll()
 * @method Orders[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class OrdersRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Orders::class);
    }

    /**
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function add(Orders $entity, bool $flush = true): void
    {
        $this->_em->persist($entity);
        if ($flush) {
            $this->_em->flush();
        }
    }

    /**
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function remove(Orders $entity, bool $flush = true): void
    {
        $this->_em->remove($entity);
        if ($flush) {
            $this->_em->flush();
        }
    }

    public function findOrdersToSendToCrm(array $statuses, array $orders = null, int $limit = null): array
    {
        $ordersBymia = $this->createQueryBuilder('o')
            ->where('o.status_sent_crm IN (:statuses)')
            ->setParameter('statuses', $statuses);
        if ($orders) {
            foreach ($orders as $orderKey => $orderValue) {
                $ordersBymia->orderBy('o.' . $orderKey, $orderValue);
            }
        }
        if ($limit) {
            $ordersBymia->setMaxResults($limit);
        }
        return $ordersBymia->getQuery()
            ->getResult();
    }

    public function findOrderByCustomerId($customer_id, $order_id)
    {
        return $this->createQueryBuilder('o')
            ->where('o.customer =:customer_id')
            ->andWhere('o.id =:order_id')
            ->setParameter('customer_id', $customer_id)
            ->setParameter('order_id', $order_id)
            ->getQuery()
            ->getOneOrNullResult();
    }

}
