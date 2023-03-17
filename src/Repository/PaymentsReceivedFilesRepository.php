<?php

namespace App\Repository;

use App\Entity\PaymentsReceivedFiles;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<PaymentsReceivedFiles>
 *
 * @method PaymentsReceivedFiles|null find($id, $lockMode = null, $lockVersion = null)
 * @method PaymentsReceivedFiles|null findOneBy(array $criteria, array $orderBy = null)
 * @method PaymentsReceivedFiles[]    findAll()
 * @method PaymentsReceivedFiles[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PaymentsReceivedFilesRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, PaymentsReceivedFiles::class);
    }

    /**
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function add(PaymentsReceivedFiles $entity, bool $flush = true): void
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
    public function remove(PaymentsReceivedFiles $entity, bool $flush = true): void
    {
        $this->_em->remove($entity);
        if ($flush) {
            $this->_em->flush();
        }
    }

    // /**
    //  * @return PaymentsReceivedFiles[] Returns an array of PaymentsReceivedFiles objects
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
    public function findOneBySomeField($value): ?PaymentsReceivedFiles
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
