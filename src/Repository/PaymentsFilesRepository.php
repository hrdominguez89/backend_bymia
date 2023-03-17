<?php

namespace App\Repository;

use App\Entity\PaymentsFiles;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<PaymentsFiles>
 *
 * @method PaymentsFiles|null find($id, $lockMode = null, $lockVersion = null)
 * @method PaymentsFiles|null findOneBy(array $criteria, array $orderBy = null)
 * @method PaymentsFiles[]    findAll()
 * @method PaymentsFiles[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PaymentsFilesRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, PaymentsFiles::class);
    }

    /**
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function add(PaymentsFiles $entity, bool $flush = true): void
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
    public function remove(PaymentsFiles $entity, bool $flush = true): void
    {
        $this->_em->remove($entity);
        if ($flush) {
            $this->_em->flush();
        }
    }

    // /**
    //  * @return PaymentsFiles[] Returns an array of PaymentsFiles objects
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
    public function findOneBySomeField($value): ?PaymentsFiles
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
