<?php

namespace App\Repository;

use App\Entity\Message;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Message|null find($id, $lockMode = null, $lockVersion = null)
 * @method Message|null findOneBy(array $criteria, array $orderBy = null)
 * @method Message[]    findAll()
 * @method Message[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class MessageRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Message::class);
    }

    /**
     * @param $email
     * @return bool
     */
    public function checkExist($email): bool
    {
        $entityManager = $this->getEntityManager();

        [$now, $since] = [new \DateTime(), new \DateTime('now - 5 minutes')];

        $data = $entityManager->createQuery(
            'SELECT e.id, e.message
            FROM App\Entity\Message e WHERE e.email =:email AND e.dateCreated BETWEEN :since AND :now'
        )->setParameters(['email' => $email, 'now' => $now, 'since' => $since])->getArrayResult();

        return count($data) > 0;

    }
}
