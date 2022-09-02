<?php

namespace App\Repository;

use App\Entity\Subcategory;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Subcategory|null find($id, $lockMode = null, $lockVersion = null)
 * @method Subcategory|null findOneBy(array $criteria, array $orderBy = null)
 * @method Subcategory[]    findAll()
 * @method Subcategory[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class SubcategoryRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Subcategory::class);
    }

    public function listSubcategories()
    {
        return $this->getEntityManager()
            ->createQuery('
            SELECT
            c.id as category_id,
            c.apiId as category_api_id,
            c.name as category_name,
            sc.id as subcategory_id,
            sc.apiId as subcategory_api_id,
            sc.name as subcategory_name,
            sc.slug,
            sc.image

            FROM App:Subcategory sc

            LEFT JOIN App:Category c WITH c.id = sc.categoryId
            
            ')
            ->getResult();
    }
}
