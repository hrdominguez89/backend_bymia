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
            max(sc.id) as subcategory_id,
            max(sc.apiId) as subcategory_api_id,
            max(sc.name) as subcategory_name,
            max(sc.slug) as slug,
            COUNT(c.id) as cantidad

            FROM App:Subcategory sc 
            LEFT JOIN sc.category c

            GROUP BY sc.id

            ')
            ->getResult();
    }
}
