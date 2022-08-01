<?php

namespace App\Repository;

use App\Entity\TimeDelayStore;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method TimeDelayStore|null find($id, $lockMode = null, $lockVersion = null)
 * @method TimeDelayStore|null findOneBy(array $criteria, array $orderBy = null)
 * @method TimeDelayStore[]    findAll()
 * @method TimeDelayStore[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class TimeDelayStoreRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, TimeDelayStore::class);
    }
}
