<?php

namespace App\Repository;

use App\Entity\CommunicationStatesBetweenPlatforms;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<CommunicationStatesBetweenPlatforms>
 *
 * @method CommunicationStatesBetweenPlatforms|null find($id, $lockMode = null, $lockVersion = null)
 * @method CommunicationStatesBetweenPlatforms|null findOneBy(array $criteria, array $orderBy = null)
 * @method CommunicationStatesBetweenPlatforms[]    findAll()
 * @method CommunicationStatesBetweenPlatforms[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CommunicationStatesBetweenPlatformsRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, CommunicationStatesBetweenPlatforms::class);
    }

    /**
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function add(CommunicationStatesBetweenPlatforms $entity, bool $flush = true): void
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
    public function remove(CommunicationStatesBetweenPlatforms $entity, bool $flush = true): void
    {
        $this->_em->remove($entity);
        if ($flush) {
            $this->_em->flush();
        }
    }

    // /**
    //  * @return CommunicationStatesBetweenPlatforms[] Returns an array of CommunicationStatesBetweenPlatforms objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('c.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?CommunicationStatesBetweenPlatforms
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
