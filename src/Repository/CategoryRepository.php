<?php

namespace App\Repository;

use App\Entity\Category;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Knp\Component\Pager\PaginatorInterface;

/**
 * @method Category|null find($id, $lockMode = null, $lockVersion = null)
 * @method Category|null findOneBy(array $criteria, array $orderBy = null)
 * @method Category[]    findAll()
 * @method Category[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CategoryRepository extends ServiceEntityRepository
{
    /** @var PaginatorInterface $pagination */
    private $pagination;

    public function __construct(ManagerRegistry $registry, PaginatorInterface $pagination)
    {
        parent::__construct($registry, Category::class);

        $this->pagination = $pagination;
    }

    /**
     * @return int|mixed|string
     */
    public function findCategories()
    {
        $entityManager = $this->getEntityManager();

        return $entityManager->createQuery(
            'SELECT e
            FROM App\Entity\Category e ORDER BY e.name ASC'
        )->getResult();
    }

    /**
     * @param $slug
     */
    public function findOneBySlug($slug)
    {
        $entity = $this->findOneBy(['slug' => $slug], ['name' => 'ASC']);

        return $entity;
    }

    /**
     * @param $categoriesSlugs
     */
    public function findCategoriesBySlug($categoriesSlugs)
    {
        $entityManager = $this->getEntityManager();

        $queryBuilder = $entityManager->createQueryBuilder();
        $queryBuilder->select('c')
            ->from(Category::class, 'c')
            ->where($queryBuilder->expr()->in('c.slug', $categoriesSlugs));

        $categories = $queryBuilder->getQuery()->getResult();
        return $categories;
    }


    /**
     * @param string $filter
     * @param int $limit
     * @return int|mixed|string
     */
    public function filterCategory(string $filter = 'all', int $limit = 500)
    {
        $entityManager = $this->getEntityManager();

        $dql = 'SELECT e
            FROM App\Entity\Category e ';

        if ($filter == 'populars') {
            $dql = $dql . 'ORDER BY e.items DESC';
            $limit = 3;
        }

        return $entityManager->createQuery($dql)->setMaxResults($limit)->getResult();
    }

    public function listCategories()
    {
        return $this->getEntityManager()
            ->createQuery('
            SELECT
            c.id,
            c.id3pl,
            c.name,
            c.descriptionEn,
            c.descriptionEs,
            c.slug,
            c.image,
            c.nomenclature,
            c.visible,
            c.principal,
            c.created_at

            FROM App:Category c 
            ')
            ->getResult();
    }

    public function findCategoriesToSendTo3pl(array $statuses, array $orders = null, int $limit = null): array
    {
        $categories = $this->createQueryBuilder('c')
            ->where('c.status_sent_3pl IN (:statuses)')
            ->setParameter('statuses', $statuses);
        if ($orders) {
            foreach ($orders as $orderKey => $orderValue) {
                $categories->orderBy('c.' . $orderKey, $orderValue);
            }
        }
        if ($limit) {
            $categories->setMaxResults($limit);
        }
        return $categories->getQuery()
            ->getResult();
    }

    public function getVisibleCategories()
    {
        return  $this->createQueryBuilder('c')
            ->where('c.id3pl IS NOT NULL')
            ->andWhere('c.visible = :visible')
            ->setParameter('visible', true)
            ->orderBy('c.principal', 'DESC')
            ->addOrderBy('c.name', 'ASC')
            ->getQuery()
            ->getResult();
    }

    public function getPrincipalCategories()
    {
        return  $this->createQueryBuilder('c')
            ->select('c.id,c.name')
            ->where('c.id3pl IS NOT NULL')
            ->andWhere('c.visible = :visible')
            ->andWhere('c.principal = :principal')
            ->setParameter('visible', true)
            ->setParameter('principal', true)
            ->addOrderBy('c.name', 'ASC')
            ->getQuery()
            ->getArrayResult();
    }
}
