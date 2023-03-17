<?php

namespace App\Repository;

use App\Entity\PaymentsTransactionsCodes;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<PaymentsTransactionsCodes>
 *
 * @method PaymentsTransactionsCodes|null find($id, $lockMode = null, $lockVersion = null)
 * @method PaymentsTransactionsCodes|null findOneBy(array $criteria, array $orderBy = null)
 * @method PaymentsTransactionsCodes[]    findAll()
 * @method PaymentsTransactionsCodes[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PaymentsTransactionsCodesRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, PaymentsTransactionsCodes::class);
    }

    /**
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function add(PaymentsTransactionsCodes $entity, bool $flush = true): void
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
    public function remove(PaymentsTransactionsCodes $entity, bool $flush = true): void
    {
        $this->_em->remove($entity);
        if ($flush) {
            $this->_em->flush();
        }
    }

    // /**
    //  * @return PaymentsTransactionsCodes[] Returns an array of PaymentsTransactionsCodes objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('p.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?PaymentsTransactionsCodes
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
