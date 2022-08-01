<?php

namespace App\Repository;

use App\Entity\Order;
use App\Form\Model\OrderSearchDto;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Knp\Component\Pager\PaginatorInterface;

/**
 * @method Order|null find($id, $lockMode = null, $lockVersion = null)
 * @method Order|null findOneBy(array $criteria, array $orderBy = null)
 * @method Order[]    findAll()
 * @method Order[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class OrderRepository extends ServiceEntityRepository
{
    /** @var PaginatorInterface $pagination */
    private $pagination;

    /**
     * @param ManagerRegistry $registry
     * @param PaginatorInterface $pagination
     */
    public function __construct(ManagerRegistry $registry, PaginatorInterface $pagination)
    {
        parent::__construct($registry, Order::class);

        $this->pagination = $pagination;
    }

    /**
     * @param $oId
     * @return int|mixed|string|null
     */
    public function findOneById($oId)
    {
        try {

            $entityManager = $this->getEntityManager();

            [$id, $cId] = [(is_numeric($oId) ? $oId : 0), $oId];

            return $entityManager->createQuery(
                'SELECT e
            FROM App\Entity\Order e
            WHERE e.id =:id OR e.checkoutId =:cId'
            )->setParameters(['id' => $id, 'cId' => $cId])->getOneOrNullResult();
        } catch (\Exception $ex) {
        }

        return null;
    }

    /**
     * @param $customer
     * @param $cId
     * @return Order|null
     */
    public function findOneByCustomerCheckoutId($customer, $cId): ?Order
    {
        return $this->findOneBy(['customerId' => $customer, 'checkoutId' => $cId]);
    }

    /**
     * @param $customerId
     * @param $page
     * @param $limit
     * @return \Knp\Component\Pager\Pagination\PaginationInterface
     */
    public function findAllPartial($customerId, $page, $limit): \Knp\Component\Pager\Pagination\PaginationInterface
    {
        $entityManager = $this->getEntityManager();

        $dql = $entityManager->createQuery(
            'SELECT e.id, e.checkoutId, e.date, e.status, e.checkoutStatus, e.total, e.quantity
            FROM App\Entity\Order e
            LEFT JOIN e.customerId c
            WHERE c.id =:id
            ORDER BY e.id DESC'
        )->setParameter('id', $customerId);

        return $this->pagination->paginate($dql, $page, $limit);
    }

    /**
     * @param $page
     * @param $limit
     * @return \Knp\Component\Pager\Pagination\PaginationInterface
     */
    public function list($page, $limit, OrderSearchDto $orderSearch): \Knp\Component\Pager\Pagination\PaginationInterface
    {
        $entityManager = $this->getEntityManager();

        $where = '';
        if ($orderSearch->getCustomer()) {
            $where .= "and clearstr(CONCAT(e.checkoutBillingFirstName,e.checkoutBillingLastName)) like clearstr('%" . $orderSearch->getCustomer() . "%')";
        }

        if ($orderSearch->getNumber()) {
            $where .= "and clearstr(e.checkoutId) like clearstr('%" . $orderSearch->getNumber() . "%')";
        }
        if ($orderSearch->getStatus()) {
            $where .= "and e.checkoutStatus like '%" . $orderSearch->getStatus() . "%'";
        }
        $where = $where != '' ? 'WHERE ' . ltrim($where, 'and ') : '';

        $dql = $entityManager->createQuery(
            "SELECT e.id, e.checkoutId, e.date, e.status, e.checkoutStatus, e.total, e.quantity, e.checkoutBillingFirstName, e.checkoutBillingLastName
            FROM App\Entity\Order e
            $where
            ORDER BY e.id DESC"
        );

        return $this->pagination->paginate($dql, $page, $limit);
    }

    public function lastOrders()
    {
        $sql = "select c.name, c.billing_last_name as last_name, c.image, mo.checkout_id as order_number, mo.total, mo.status as state, mo.customer_id
                    from mia_order as mo
                    inner join mia_customer as c on mo.customer_id = c.id
                    order by mo.date desc
                    offset 0
                    limit 10";
        $statement = $this->getEntityManager()->getConnection()->prepare($sql);
        $statement->executeQuery();
        return $statement->fetchAll();
    }
}
