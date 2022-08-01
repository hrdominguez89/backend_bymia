<?php

namespace App\Repository;

use App\Entity\ProductReviews;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Knp\Component\Pager\PaginatorInterface;

/**
 * @method ProductReviews|null find($id, $lockMode = null, $lockVersion = null)
 * @method ProductReviews|null findOneBy(array $criteria, array $orderBy = null)
 * @method ProductReviews[]    findAll()
 * @method ProductReviews[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ProductReviewsRepository extends ServiceEntityRepository
{
    /** @var PaginatorInterface $pagination */
    private $pagination;

    /**
     * @param ManagerRegistry $registry
     * @param PaginatorInterface $pagination
     */
    public function __construct(ManagerRegistry $registry, PaginatorInterface $pagination)
    {
        parent::__construct($registry, ProductReviews::class);

        $this->pagination = $pagination;
    }

    /**
     * @param $pId
     * @param int $page
     * @param int $limit
     * @return \Knp\Component\Pager\Pagination\PaginationInterface
     */
    public function findReviews($pId, int $page = 1, int $limit = 5): \Knp\Component\Pager\Pagination\PaginationInterface
    {
        $entityManager = $this->getEntityManager();

        $query = $entityManager->createQuery(
            'SELECT e.id, e.rating, e.message as text, e.dateCreated as date, c.name as author, c.image as avatar
            FROM App\Entity\ProductReviews e 
            LEFT JOIN e.productId p 
            LEFT JOIN e.customerId c 
            WHERE p.id =:pId ORDER BY e.id DESC'
        )->setParameter('pId', $pId);

        return $this->pagination->paginate($query, $page, $limit);
    }
}
