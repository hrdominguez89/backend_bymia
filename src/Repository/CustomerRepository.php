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

        // $this->pagination = $pagination;
    }

    /**
     * @param $page
     * @param $limit
     * @return \Knp\Component\Pager\Pagination\PaginationInterface
     */
    // public function list($page, $limit, CustomerSearchDto $customerSearchDto): \Knp\Component\Pager\Pagination\PaginationInterface
    // {
    //     $entityManager = $this->getEntityManager();

    //     $where = '';
    //     if ($customerSearchDto->getName()) {
    //         $where .= "and clearstr(CONCAT(c.billingFirstName,c.billingLastName)) like clearstr('%" . $customerSearchDto->getName() . "%')";
    //     }

    //     $where = $where != '' ? 'WHERE ' . ltrim($where, 'and ') : '';

    //     $dql = $entityManager->createQuery(
    //         "SELECT c.id, c.image, c.billingFirstName, c.billingLastName, c.billingPhone, c.billingEmail
    //         FROM App\Entity\Customer c
    //         $where
    //         ORDER BY c.id DESC"
    //     );

    //     return $this->pagination->paginate($dql, $page, $limit);
    // }


    // /**
    //  * @return Customers[] Returns an array of customers objects
    //  */

    public function listCustomersInfo()
    {
        return $this->getEntityManager()
            ->createQuery('
            SELECT
            c.id,
            c.email,
            c.image,
            c.name,
            (SELECT co1.phonecode FROM App:Countries co1 WHERE c.country_phone_code=co1.id) as country_phone_code,
            c.state_code_cel_phone,
            c.cel_phone,
            c.state_code_phone,
            c.phone,
            c.registration_date,
            c.url_facebook,
            c.url_instagram,
            c.date_of_birth,
            ctr.name as customer_type_role,
            ctr.id as customer_type_role_id,
            gt.initials as gender_type
            

            FROM App:Customer c
            LEFT JOIN App:CustomersTypesRoles ctr WITH ctr.id = c.customer_type_role
            LEFT JOIN App:GenderType gt WITH gt.id = c.gender_type

            GROUP BY
            c.id,
            c.email,
            c.image,
            c.name,
            country_phone_code,
            c.state_code_cel_phone,
            c.cel_phone,
            c.state_code_phone,
            c.phone,
            c.registration_date,
            customer_type_role,
            customer_type_role_id,
            gender_type
            ')
            ->getResult();
    }

    public function findCustomersToSendToCrm(array $statuses, array $orders = null, int $limit = null): array
    {
        $customers = $this->createQueryBuilder('c')
            ->where('c.status_sent_crm IN (:statuses)')
            ->andWhere('c.attempts_send_crm <=5')
            ->setParameter('statuses', $statuses);
        if ($orders) {
            foreach ($orders as $orderKey => $orderValue) {
                $customers->orderBy('c.' . $orderKey, $orderValue);
            }
        }
        if ($limit) {
            $customers->setMaxResults($limit);
        }
        return $customers->getQuery()
            ->getResult();
    }
}
