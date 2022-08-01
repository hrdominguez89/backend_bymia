<?php

namespace App\Repository;

use App\Entity\AboutUs;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method AboutUs|null find($id, $lockMode = null, $lockVersion = null)
 * @method AboutUs|null findOneBy(array $criteria, array $orderBy = null)
 * @method AboutUs[]    findAll()
 * @method AboutUs[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class AboutUsRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, AboutUs::class);
    }

    /**
     * @return mixed|string
     */
    public function findAboutUsDescription()
    {
        $entityManager = $this->getEntityManager();

        $data = $entityManager->createQuery(
            'SELECT e.id, e.description
            FROM App\Entity\AboutUs e'
        )->getArrayResult();

        $response = "";
        foreach ($data as $datum) {
            $response = $datum['description'];
        }

        return $response;
    }
}
