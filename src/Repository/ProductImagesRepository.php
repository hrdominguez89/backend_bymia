<?php

namespace App\Repository;

use App\Entity\Product;
use App\Entity\ProductImages;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method ProductImages|null find($id, $lockMode = null, $lockVersion = null)
 * @method ProductImages|null findOneBy(array $criteria, array $orderBy = null)
 * @method ProductImages[]    findAll()
 * @method ProductImages[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ProductImagesRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ProductImages::class);
    }
    /**
     * @param int $parentId
     * @return Product[]
     */
    public function getDataImages($id): array
    {
        $entityManager = $this->getEntityManager();
        return $entityManager->createQuery('SELECT e.id,e.image as src, e.new as nueva
            FROM App\Entity\ProductImages e 
            WHERE e.productId =:id')->setParameter('id', $id)->getResult();
    }

    public function getImageNotPrincipal($product_id): array
    {
        $entityManager = $this->getEntityManager();
        return $entityManager->createQuery('SELECT i.id,i.principal
            FROM App\Entity\ProductImages i 
            WHERE i.product =:id and i.principal = :principal')
            ->setParameter('principal', false)
            ->setParameter('id', $product_id)
            ->setMaxResults(1)
            ->getResult();
    }
}
