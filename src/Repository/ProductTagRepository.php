<?php

namespace App\Repository;

use App\Entity\ProductTag;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method ProductTag|null find($id, $lockMode = null, $lockVersion = null)
 * @method ProductTag|null findOneBy(array $criteria, array $orderBy = null)
 * @method ProductTag[]    findAll()
 * @method ProductTag[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ProductTagRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ProductTag::class);
    }

    /**
     * @param int $parentId
     * @return ProductTag[]
     */
    public function getDataTags(int $id): array
    {
        $entityManager = $this->getEntityManager();
        return $entityManager->createQuery('SELECT e
            FROM App\Entity\ProductTag e 
            WHERE e.productId =:id 
            ORDER BY e.id DESC')
            ->setParameter('id', $id)
            ->getResult();
    }
}
