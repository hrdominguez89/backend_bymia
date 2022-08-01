<?php

namespace App\Repository;

use App\Entity\PayPal;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method PayPal|null find($id, $lockMode = null, $lockVersion = null)
 * @method PayPal|null findOneBy(array $criteria, array $orderBy = null)
 * @method PayPal[]    findAll()
 * @method PayPal[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PayPalRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, PayPal::class);
    }

    /**
     * @return int|mixed|string|null
     */
    public function findPaypal()
    {
        try {

            $entityManager = $this->getEntityManager();

            return $entityManager->createQuery(
                'SELECT e
            FROM App\Entity\PayPal e
            WHERE e.name =:name AND e.active =:active'
            )->setParameters(['name' => PayPal::NAME, 'active' => true])->getOneOrNullResult();

        }catch (\Exception $ex){

        }
        return null;
    }
}
