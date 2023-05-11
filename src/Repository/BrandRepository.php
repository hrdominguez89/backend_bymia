<?php

namespace App\Repository;

use App\Entity\Brand;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Brand|null find($id, $lockMode = null, $lockVersion = null)
 * @method Brand|null findOneBy(array $criteria, array $orderBy = null)
 * @method Brand[]    findAll()
 * @method Brand[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class BrandRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Brand::class);
    }

    /**
     * @return array
     */
    public function findBrands(): array
    {
        $entityManager = $this->getEntityManager();

        return $entityManager->createQuery(
            'SELECT e.id, e.name, e.slug, e.image
            FROM App\Entity\Brand e
            ORDER BY e.name ASC'
        )->getArrayResult();
    }

    /**
     * @param $brandsSlugs
     */
    public function findBrandsBySlug($brandsSlugs)
    {
        $entityManager = $this->getEntityManager();

        $queryBuilder = $entityManager->createQueryBuilder();
        $queryBuilder->select('b')
            ->from(Brand::class, 'b')
            ->where($queryBuilder->expr()->in('b.slug', $brandsSlugs));

        $brands = $queryBuilder->getQuery()->getResult();
        return $brands;
    }

    public function getCantProductByBrand($brandId)
    {
        $entityManager = $this->getEntityManager();

        return $entityManager->createQuery(
            'SELECT e
            FROM App\Entity\Product e
            WHERE e.brandId =:brandId
            ORDER BY e.id ASC'
        )->setParameters(['brandId' => $brandId])->getResult();
    }

    public function findBrandsToSendTo3pl(array $statuses, array $orders = null, int $limit = null): array
    {
        $brands = $this->createQueryBuilder('b')
            ->where('b.status_sent_3pl IN (:statuses)')
            ->setParameter('statuses', $statuses);
        if ($orders) {
            foreach ($orders as $orderKey => $orderValue) {
                $brands->orderBy('b.' . $orderKey, $orderValue);
            }
        }
        if ($limit) {
            $brands->setMaxResults($limit);
        }
        return $brands->getQuery()
            ->getResult();
    }


    public function getPrincipalBrands(){
        return  $this->createQueryBuilder('b')
            ->select('b.id,b.name')
            ->where('b.id3pl IS NOT NULL')
            ->andWhere('b.visible = :visible')
            ->andWhere('b.principal = :principal')
            ->setParameter('visible', true)
            ->setParameter('principal', true)
            ->addOrderBy('b.name', 'ASC')
            ->getQuery()
            ->getArrayResult();
    }
}
