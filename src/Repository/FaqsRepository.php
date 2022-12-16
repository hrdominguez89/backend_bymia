<?php

namespace App\Repository;

use App\Entity\Faqs;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Faqs>
 *
 * @method Faqs|null find($id, $lockMode = null, $lockVersion = null)
 * @method Faqs|null findOneBy(array $criteria, array $orderBy = null)
 * @method Faqs[]    findAll()
 * @method Faqs[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class FaqsRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Faqs::class);
    }

    /**
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function add(Faqs $entity, bool $flush = true): void
    {
        $this->_em->persist($entity);
        if ($flush) {
            $this->_em->flush();
        }
    }

    /**
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function remove(Faqs $entity, bool $flush = true): void
    {
        $this->_em->remove($entity);
        if ($flush) {
            $this->_em->flush();
        }
    }

    public function findByTopicId($topic_id): ?array
    {
        return $this->getEntityManager()
            ->createQuery('
            SELECT
            f.id,
            f.number_order,
            f.question,
            f.icon,
            f.visible

            FROM App:Faqs f

            INNER JOIN App:Topics t WITH t.id = f.topic

            WHERE t.id = :topic_id

            ORDER BY f.number_order
            ')
            ->setParameter('topic_id', $topic_id)
            ->getResult();
    }

    public function getFaqsByTopic($topic_id): ?array
    {
        return $this->getEntityManager()
            ->createQuery('
            SELECT
            f.id,
            f.question,
            f.answer

            FROM App:Faqs f

            WHERE f.topic = :topic_id and f.visible=:visible

            ORDER BY f.number_order asc, f.question asc
            ')
            ->setParameter('topic_id', $topic_id)
            ->setParameter('visible', true)
            ->getResult();
    }
}
