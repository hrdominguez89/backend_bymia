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
            c.lastname,
            c.country_code_cel_phone,
            c.state_code_cel_phone,
            c.cel_phone,
            c.country_code_phone,
            c.state_code_phone,
            c.phone,
            c.registration_date,
            c.status,
            c.url_facebook,
            c.url_instagram,
            c.date_of_birth,
            max(ctr.name) as customer_type_role,
            max(ctr.id) as customer_type_role_id,
            max(gt.initials) as gender_type,
            (SELECT co.name FROM App:Countries co left join App:CustomerAddresses ca2 WITH ca2.country=co.id where ca2.favorite_address = true and ca2.customer=c.id) as country,
            (SELECT st.name FROM App:States st left join App:CustomerAddresses ca3 WITH ca3.state=st.id where ca3.favorite_address = true and ca3.customer=c.id) as state,
            (SELECT ci.name FROM App:Cities ci left join App:CustomerAddresses ca4 WITH ca4.city=ci.id where ca4.favorite_address = true and ca4.customer=c.id) as city,
            (SELECT ca5.street FROM App:CustomerAddresses ca5 where ca5.favorite_address = true and ca5.customer=c.id) as street,
            (SELECT ca6.number_street FROM App:CustomerAddresses ca6 where ca6.favorite_address = true and ca6.customer=c.id) as number_street,
            (SELECT ca7.floor FROM App:CustomerAddresses ca7 where ca7.favorite_address = true and ca7.customer=c.id) as floor,
            (SELECT ca8.department FROM App:CustomerAddresses ca8 where ca8.favorite_address = true and ca8.customer=c.id) as department,
            (SELECT ca9.postal_code FROM App:CustomerAddresses ca9 where ca9.favorite_address = true and ca9.customer=c.id) as postal_code
            

            FROM App:Customer c
            LEFT JOIN App:CustomersTypesRoles ctr WITH ctr.id = c.customer_type_role
            LEFT JOIN App:GenderType gt WITH gt.id = c.gender_type
            LEFT JOIN App:CustomerAddresses ca WITH c.id = ca.customer

            GROUP BY
            c.id,
            c.email,
            c.image,
            c.name,
            c.lastname,
            c.country_code_cel_phone,
            c.state_code_cel_phone,
            c.cel_phone,
            c.country_code_phone,
            c.state_code_phone,
            c.phone,
            c.registration_date
            ')
            ->getResult();
    }
}
