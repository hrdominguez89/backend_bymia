<?php

namespace App\Repository;

use App\Entity\CustomerAddresses;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method CustomerAddresses|null find($id, $lockMode = null, $lockVersion = null)
 * @method CustomerAddresses|null findOneBy(array $criteria, array $orderBy = null)
 * @method CustomerAddresses[]    findAll()
 * @method CustomerAddresses[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CustomerAddressesRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, CustomerAddresses::class);
    }

    /**
     * @return Customers[] Returns an array of customers objects
     */

    public function listCustomerAddresses($customer_id)
    {
        return $this->getEntityManager()
            ->createQuery('
            SELECT
                co.name as country,
                st.name as state,
                ci.name as city,
                c.id as customer_id,
                ca.id as customer_address_id,
                ca.street,
                ca.number_street,
                ca.floor,
                ca.department,
                ca.postal_code,
                ca.additional_info,
                ca.active,
                ca.registration_date,
                ca.favorite_address,
                ca.billing_address
            
            FROM App:CustomerAddresses ca

            LEFT JOIN App:Countries as co WITH ca.country = co.id
            LEFT JOIN App:States as st WITH ca.state = st.id
            LEFT JOIN App:Cities as ci WITH ca.city = ci.id
            LEFT JOIN App:Customer as c WITH ca.customer = c.id

            WHERE ca.customer =:customer_id
            ')
            ->setParameter('customer_id', $customer_id)
            ->getResult();
    }

    public function updateFavoriteAddress($customer_id)
    {
        $this->getEntityManager()
            ->createQuery('
            UPDATE App:CustomerAddresses ca
            SET
                ca.favorite_address = false
            WHERE ca.customer =:customer_id
            ')
            ->setParameter('customer_id', $customer_id)
            ->execute();
    }

    public function updateBillingAddress($customer_id)
    {
        $this->getEntityManager()
            ->createQuery('
            UPDATE App:CustomerAddresses ca
            SET
                ca.billing_address = false
            WHERE ca.customer =:customer_id
            ')
            ->setParameter('customer_id', $customer_id)
            ->execute();
    }
}
