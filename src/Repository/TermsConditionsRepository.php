<?php

namespace App\Repository;

use App\Entity\TermsConditions;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method TermsConditions|null find($id, $lockMode = null, $lockVersion = null)
 * @method TermsConditions|null findOneBy(array $criteria, array $orderBy = null)
 * @method TermsConditions[]    findAll()
 * @method TermsConditions[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class TermsConditionsRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, TermsConditions::class);
    }

    /**
     * @return mixed|string
     */
    public function findTermsConditionDescription()
    {
        $entityManager = $this->getEntityManager();

        $data = $entityManager->createQuery(
            'SELECT e.id, e.description
            FROM App\Entity\TermsConditions e'
        )->getArrayResult();

        $response = "";
        foreach ($data as $datum) {
            $response = $datum['description'];
        }

        return $response;
    }
}
