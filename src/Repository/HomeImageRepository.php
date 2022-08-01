<?php

namespace App\Repository;

use App\Entity\HomeImage;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method HomeImage|null find($id, $lockMode = null, $lockVersion = null)
 * @method HomeImage|null findOneBy(array $criteria, array $orderBy = null)
 * @method HomeImage[]    findAll()
 * @method HomeImage[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class HomeImageRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, HomeImage::class);
    }

    public function geAllByOrder():array
    {
        $entityManager = $this->getEntityManager();

        $data = $entityManager->createQuery(
            'SELECT e.id,e.image,e.order_image as orderImage
            FROM App\Entity\HomeImage e
            order by e.order_image ASC'
        )->getArrayResult();
        return $data;
    }
    // /**
    //  * @return HomeImage[] Returns an array of HomeImage objects
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
    public function findOneBySomeField($value): ?HomeImage
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
