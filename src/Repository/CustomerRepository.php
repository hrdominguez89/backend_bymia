<?php

namespace App\Repository;

use App\Entity\Customer;
use App\Form\Model\CustomerSearchDto;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Knp\Component\Pager\PaginatorInterface;

/**
 * @method Customer|null find($id, $lockMode = null, $lockVersion = null)
 * @method Customer|null findOneBy(array $criteria, array $orderBy = null)
 * @method Customer[]    findAll()
 * @method Customer[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CustomerRepository extends ServiceEntityRepository
{
    /** @var PaginatorInterface $pagination */
    private $pagination;

    public function __construct(ManagerRegistry $registry, PaginatorInterface $pagination)
    {
        parent::__construct($registry, Customer::class);

        $this->pagination = $pagination;
    }

    /**
     * @param $page
     * @param $limit
     * @return \Knp\Component\Pager\Pagination\PaginationInterface
     */
    public function list($page, $limit, CustomerSearchDto $customerSearchDto): \Knp\Component\Pager\Pagination\PaginationInterface
    {
        $entityManager = $this->getEntityManager();

        $where = '';
        if ($customerSearchDto->getName()) {
            $where .= "and clearstr(CONCAT(c.billingFirstName,c.billingLastName)) like clearstr('%" . $customerSearchDto->getName() . "%')";
        }

        $where = $where != '' ? 'WHERE ' . ltrim($where, 'and ') : '';

        $dql = $entityManager->createQuery(
            "SELECT c.id, c.image, c.billingFirstName, c.billingLastName, c.billingPhone, c.billingEmail
            FROM App\Entity\Customer c
            $where
            ORDER BY c.id DESC"
        );

        return $this->pagination->paginate($dql, $page, $limit);
    }
}
