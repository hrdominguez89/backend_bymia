<?php

namespace App\Repository;

use App\Entity\Cities;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Cities|null find($id, $lockMode = null, $lockVersion = null)
 * @method Cities|null findOneBy(array $criteria, array $orderBy = null)
 * @method Cities[]    findAll()
 * @method Cities[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CitiesRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Cities::class);
    }

    public function findCitiesByStateId($state_id): ?array
    {
        return $this->getEntityManager()
            ->createQuery('
            SELECT
            c.id,
            c.name,
            c.visible

            FROM App:Cities c

            INNER JOIN App:States st WITH st.id = c.state

            WHERE st.id = :state_id

            ORDER BY c.name
            ')
            ->setParameter('state_id', $state_id)
            ->getResult();
    }
    public function findVisibleCitiesByStateId($state_id): ?array
    {
        return $this->getEntityManager()
            ->createQuery('
            SELECT
            c.id,
            c.name

            FROM App:Cities c

            INNER JOIN App:States st WITH st.id = c.state

            WHERE st.id = :state_id and c.visible = :visible

            ORDER BY c.name asc
            ')
            ->setParameter('state_id', $state_id)
            ->setParameter('visible', true)
            ->getResult();
    }
    
}
