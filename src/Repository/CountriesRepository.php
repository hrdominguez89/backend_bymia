<?php

namespace App\Repository;

use App\Entity\Countries;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Countries|null find($id, $lockMode = null, $lockVersion = null)
 * @method Countries|null findOneBy(array $criteria, array $orderBy = null)
 * @method Countries[]    findAll()
 * @method Countries[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CountriesRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Countries::class);
    }

    public function listCountries()
    {
        return $this->getEntityManager()
            ->createQuery('
            SELECT
            c.id as country_id,
            c.name as country_name,
            c.visible,
            rt.id as region_type_id,
            rt.name as region_type_name,
            srt.id as subregion_type,
            srt.name as subregion_type_name

            FROM App:Countries c

            LEFT JOIN App:RegionType rt WITH rt.id = c.region_type
            LEFT JOIN App:SubregionType srt WITH srt.id = c.subregion_type
            
            ')
            ->getResult();
    }

    public function getCountries(){
        return $this->getEntityManager()
            ->createQuery("
            SELECT
            c.id,
            c.name,
            c.phonecode,
            CONCAT('https://flagcdn.com/20x15/',LOWER(c.iso2),'.png') as flag

            FROM App:Countries c

            WHERE c.visible = :visible

            ORDER BY c.name

            ")
            ->setParameter('visible', true)
            ->getResult();
    }
}
