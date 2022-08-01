<?php

namespace App\Repository;

use App\Entity\ContactUs;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method ContactUs|null find($id, $lockMode = null, $lockVersion = null)
 * @method ContactUs|null findOneBy(array $criteria, array $orderBy = null)
 * @method ContactUs[]    findAll()
 * @method ContactUs[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ContactUsRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ContactUs::class);
    }

    /**
     * @return array
     */
    public function findContact(): array
    {
        $entityManager = $this->getEntityManager();

        $data = $entityManager->createQuery(
            'SELECT e.id, e.description, e.address, e.email, e.phoneMain as phone, e.phoneOther as other_phone, e.url
            FROM App\Entity\ContactUs e'
        )->getArrayResult();

        return count($data) > 0 ? $data[0] : $this->getContactUsFormat();
    }

    /**
     * @return string[]
     */
    private function getContactUsFormat(): array
    {
        return [
            "id" => '',
            "description" => '',
            "address" => '',
            "email" => '',
            "phone" => '',
            "other_phone" => '',
            "url" => '',
        ];
    }
}
