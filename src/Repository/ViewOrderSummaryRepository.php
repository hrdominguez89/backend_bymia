<?php

namespace App\Repository;

use App\Entity\ViewOrderSummary;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method ViewOrderSummary|null find($id, $lockMode = null, $lockVersion = null)
 * @method ViewOrderSummary|null findOneBy(array $criteria, array $orderBy = null)
 * @method ViewOrderSummary[]    findAll()
 * @method ViewOrderSummary[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ViewOrderSummaryRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ViewOrderSummary::class);
    }
}
