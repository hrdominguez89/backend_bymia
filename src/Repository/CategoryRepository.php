<?php

namespace App\Repository;

use App\Entity\Category;
use App\Entity\Subcategory;
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
     * @return \App\Entity\Model\Category
     */
    public function findOneBySlug($slug): \App\Entity\Model\Category
    {
        $entityManager = $this->getEntityManager();

        $entity = $this->findOneBy(['slug' => $slug], ['name' => 'ASC']);

        return $entity ?? $entityManager->getRepository(Subcategory::class)->findOneBy(['slug' => $slug], ['name' => 'ASC']);
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
            $dql = $dql.'ORDER BY e.items DESC';
            $limit = 3;
        }

        return $entityManager->createQuery($dql)->setMaxResults($limit)->getResult();

    }
}
