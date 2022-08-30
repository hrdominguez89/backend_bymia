<?php

namespace App\Repository;

use App\Entity\SubregionType;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method SubregionType|null find($id, $lockMode = null, $lockVersion = null)
 * @method SubregionType|null findOneBy(array $criteria, array $orderBy = null)
 * @method SubregionType[]    findAll()
 * @method SubregionType[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class SubregionTypeRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, SubregionType::class);
    }
    public function findSubregiones($region_id): ?array
    {
        return $this->getEntityManager()
            ->createQuery('
            SELECT
            sr.id,
            sr.name

            FROM App:SubregionType sr

            WHERE sr.region_type = :region_id

            ORDER BY sr.name
            ')
            ->setParameter('region_id', $region_id)
            ->getResult();
    }
}
