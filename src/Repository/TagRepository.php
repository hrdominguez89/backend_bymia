<?php

namespace App\Repository;

use App\Entity\Tag;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Tag|null find($id, $lockMode = null, $lockVersion = null)
 * @method Tag|null findOneBy(array $criteria, array $orderBy = null)
 * @method Tag[]    findAll()
 * @method Tag[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class TagRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Tag::class);
    }

    public function findTagVisibleBySlug($slug_tag)
    {
        $tag = $this->createQueryBuilder('t')
            ->where('t.slug = :slug_tag')
            ->andWhere('t.visible = true')
            ->setParameter('slug_tag', $slug_tag);
        return $tag->getQuery()
            ->getOneOrNullResult();
    }

    /**
     * @param $tagsSlugs
     */
    public function findTagsBySlug($tagsSlugs)
    {
        $entityManager = $this->getEntityManager();

        $queryBuilder = $entityManager->createQueryBuilder();
        $queryBuilder->select('t')
            ->from(Tag::class, 't')
            ->where($queryBuilder->expr()->in('t.slug', $tagsSlugs));

        $tags = $queryBuilder->getQuery()->getResult();
        return $tags;
    }

    public function getPrincipalTags(){
        return  $this->createQueryBuilder('t')
            ->select('t.id,t.name')
            ->where('t.visible = :visible')
            ->andWhere('t.principal = :principal')
            ->setParameter('visible', true)
            ->setParameter('principal', true)
            ->addOrderBy('t.name', 'ASC')
            ->getQuery()
            ->getArrayResult();
    }
}
