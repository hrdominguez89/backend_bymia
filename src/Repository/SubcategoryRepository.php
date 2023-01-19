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
            sc.id,
            sc.id3pl,
            c.name as category_name,
            sc.name,
            sc.slug,
            sc.visible,
            sc.created_at

            FROM App:Subcategory sc 
            LEFT JOIN App:Category c WITH sc.category = c.id
            ')
            ->getResult();
    }

    public function findSubcategoriesToSendTo3pl(array $statuses, array $orders = null, int $limit = null): array
    {
        $categories = $this->createQueryBuilder('sc')
            ->where('sc.status_sent_3pl IN (:statuses)')
            ->setParameter('statuses', $statuses);
        if ($orders) {
            foreach ($orders as $orderKey => $orderValue) {
                $categories->orderBy('sc.' . $orderKey, $orderValue);
            }
        }
        if ($limit) {
            $categories->setMaxResults($limit);
        }
        return $categories->getQuery()
            ->getResult();
    }

    public function findSubcategoriesWithId3plByCategoryId($category_id): array
    {
        $subcategories = $this->createQueryBuilder('sc')
            ->select('sc.name,sc.id')
            ->where('sc.id3pl is not null')
            ->where('sc.category = :category_id')
            ->setParameter('category_id', $category_id)
            ->orderBy('sc.name', 'ASC');
        return $subcategories->getQuery()
            ->getResult();
    }
}
