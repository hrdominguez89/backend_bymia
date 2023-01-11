<?php

namespace App\Repository;

use App\Entity\States;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method States|null find($id, $lockMode = null, $lockVersion = null)
 * @method States|null findOneBy(array $criteria, array $orderBy = null)
 * @method States[]    findAll()
 * @method States[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class StatesRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, States::class);
    }

    public function findStatesByCountryId($country_id): ?array
    {
        return $this->getEntityManager()
            ->createQuery('
            SELECT
            s.id,
            s.name,
            s.visible

            FROM App:States s

            INNER JOIN App:Countries co WITH co.id = s.country

            WHERE co.id = :country_id

            ORDER BY s.name
            ')
            ->setParameter('country_id', $country_id)
            ->getResult();
    }

    public function findVisibleStatesByCountryId($country_id): ?array
    {
        return $this->getEntityManager()
            ->createQuery('
            SELECT
            s.id,
            s.name

            FROM App:States s

            INNER JOIN App:Countries co WITH co.id = s.country

            WHERE co.id = :country_id AND s.visible = :visible

            ORDER BY s.name asc
            ')
            ->setParameter('country_id', $country_id)
            ->setParameter('visible', 1)
            ->getResult();
    }
}
