<?php

namespace App\Repository;

use App\Entity\SpecificationType;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method SpecificationType|null find($id, $lockMode = null, $lockVersion = null)
 * @method SpecificationType|null findOneBy(array $criteria, array $orderBy = null)
 * @method SpecificationType[]    findAll()
 * @method SpecificationType[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class SpecificacionTypeRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, SpecificationType::class);
    }
}
