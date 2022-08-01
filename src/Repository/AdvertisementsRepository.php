<?php

namespace App\Repository;

use App\Entity\Advertisements;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Advertisements|null find($id, $lockMode = null, $lockVersion = null)
 * @method Advertisements|null findOneBy(array $criteria, array $orderBy = null)
 * @method Advertisements[]    findAll()
 * @method Advertisements[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class AdvertisementsRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Advertisements::class);
    }

    /**
     * @return array|mixed
     */
    public function findAdvertisements()
    {
        $entityManager = $this->getEntityManager();

        $data = $entityManager->createQuery(
            'SELECT e.id, e.src1, e.srcSm1, e.src2, e.srcSm2, e.src3, e.srcSm3
            FROM App\Entity\Advertisements e'
        )->getArrayResult();


        return count($data) > 0 ? $data[0] : [];
    }
}
