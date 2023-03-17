<?php

namespace App\Repository;

use App\Entity\DebitCreditNotesFiles;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<DebitCreditNotesFiles>
 *
 * @method DebitCreditNotesFiles|null find($id, $lockMode = null, $lockVersion = null)
 * @method DebitCreditNotesFiles|null findOneBy(array $criteria, array $orderBy = null)
 * @method DebitCreditNotesFiles[]    findAll()
 * @method DebitCreditNotesFiles[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class DebitCreditNotesFilesRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, DebitCreditNotesFiles::class);
    }

    /**
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function add(DebitCreditNotesFiles $entity, bool $flush = true): void
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
    public function remove(DebitCreditNotesFiles $entity, bool $flush = true): void
    {
        $this->_em->remove($entity);
        if ($flush) {
            $this->_em->flush();
        }
    }

    // /**
    //  * @return DebitCreditNotesFiles[] Returns an array of DebitCreditNotesFiles objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('d')
            ->andWhere('d.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('d.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?DebitCreditNotesFiles
    {
        return $this->createQueryBuilder('d')
            ->andWhere('d.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
