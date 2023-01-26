<?php

namespace App\Repository;

use App\Entity\Specification;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Specification|null find($id, $lockMode = null, $lockVersion = null)
 * @method Specification|null findOneBy(array $criteria, array $orderBy = null)
 * @method Specification[]    findAll()
 * @method Specification[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class SpecificationRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Specification::class);
    }

    public function findAllSpecificationsOrdered($specification_type_id)
    {
        return $this->createQueryBuilder('s')
            ->where('s.specification_type = :specification_type_id')
            ->setParameter('specification_type_id',$specification_type_id)
            ->orderBy('s.name', 'ASC')
            ->getQuery()
            ->getResult();
    }
}
