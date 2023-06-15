<?php

namespace App\Repository;

use App\Entity\Recipients;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Recipients>
 *
 * @method Recipients|null find($id, $lockMode = null, $lockVersion = null)
 * @method Recipients|null findOneBy(array $criteria, array $orderBy = null)
 * @method Recipients[]    findAll()
 * @method Recipients[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class RecipientsRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Recipients::class);
    }

    public function add(Recipients $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(Recipients $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

//    /**
//     * @return Recipients[] Returns an array of Recipients objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('r')
//            ->andWhere('r.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('r.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?Recipients
//    {
//        return $this->createQueryBuilder('r')
//            ->andWhere('r.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
