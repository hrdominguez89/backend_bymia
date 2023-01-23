<?php

namespace App\Repository;

use App\Entity\HistoryProductStockUpdated;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<HistoryProductStockUpdated>
 *
 * @method HistoryProductStockUpdated|null find($id, $lockMode = null, $lockVersion = null)
 * @method HistoryProductStockUpdated|null findOneBy(array $criteria, array $orderBy = null)
 * @method HistoryProductStockUpdated[]    findAll()
 * @method HistoryProductStockUpdated[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class HistoryProductStockUpdatedRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, HistoryProductStockUpdated::class);
    }

    /**
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function add(HistoryProductStockUpdated $entity, bool $flush = true): void
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
    public function remove(HistoryProductStockUpdated $entity, bool $flush = true): void
    {
        $this->_em->remove($entity);
        if ($flush) {
            $this->_em->flush();
        }
    }

    // /**
    //  * @return HistoryProductStockUpdated[] Returns an array of HistoryProductStockUpdated objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('h')
            ->andWhere('h.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('h.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?HistoryProductStockUpdated
    {
        return $this->createQueryBuilder('h')
            ->andWhere('h.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
