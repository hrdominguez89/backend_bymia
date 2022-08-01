<?php

namespace App\Repository;

use App\Entity\CurrencyChange;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method CurrencyChange|null find($id, $lockMode = null, $lockVersion = null)
 * @method CurrencyChange|null findOneBy(array $criteria, array $orderBy = null)
 * @method CurrencyChange[]    findAll()
 * @method CurrencyChange[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CurrencyChangeRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, CurrencyChange::class);
    }
}
