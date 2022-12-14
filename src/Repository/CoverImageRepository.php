<?php

namespace App\Repository;

use App\Entity\CoverImage;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method CoverImage|null find($id, $lockMode = null, $lockVersion = null)
 * @method CoverImage|null findOneBy(array $criteria, array $orderBy = null)
 * @method CoverImage[]    findAll()
 * @method CoverImage[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CoverImageRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, CoverImage::class);
    }

    /**
     * @return array
     */
    public function findCoverImage(): array
    {
        $entityManager = $this->getEntityManager();

        return $entityManager->createQuery(
            'SELECT e.id, e.title, e.subtitle, e.nameBtn, e.linkBtn, e.volanta, e.numberOrder, e.visible
            FROM App\Entity\CoverImage e
            WHERE e.visible = :visible
            ORDER BY e.numberOrder ASC'
        )
            ->setParameter('visible', true)
            ->getArrayResult();
    }
}
