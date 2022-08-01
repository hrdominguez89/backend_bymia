<?php

namespace App\Repository;

use App\Entity\ProductSpecification;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method ProductSpecification|null find($id, $lockMode = null, $lockVersion = null)
 * @method ProductSpecification|null findOneBy(array $criteria, array $orderBy = null)
 * @method ProductSpecification[]    findAll()
 * @method ProductSpecification[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ProductSpecificationRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ProductSpecification::class);
    }

    /**
     * @param int $parentId
     * @return ProductSpecification[]
     */
    public function getDataSpecification(int $id, string $type): array
    {
        $entityManager = $this->getEntityManager();
        return $entityManager->createQuery('SELECT e
            FROM App\Entity\ProductSpecification e 
            WHERE e.productId =:id and e.customFieldsType =:type 
            ORDER BY e.id DESC')
            ->setParameter('id', $id)
            ->setParameter('type', $type)
            ->getResult();
    }

    /**
     * @param int $parentId
     * @return string[]
     */
    public function getDistincSpecification($id): array
    {
        $sql = "SELECT Distinct(pe.custom_fields_type,pe.specification_id) as data,  pe.specification_id,pe.custom_fields_type,e.name
                FROM public.mia_product_specification pe
                join public.mia_specification e on e.id = pe.specification_id
                where product_id = $id";
        $statement = $this->getEntityManager()->getConnection()->prepare($sql);
        $statement->executeQuery();
        return $statement->fetchAll();
    }

    /**
     * @return string[]
     */
    public function findByAsArray(array $criteria, array $orderBy = null, $limit = null, $offset = null): array
    {
        $dataProductSPecification = [];

        $prductSpecification = $this->findBy($criteria, $orderBy = null, $limit = null, $offset = null);

        /** @var ProductSpecification $item */
        foreach ($prductSpecification as $item) {
            $dataProductSPecification[] = [
                'id' => $item->getId(),
                'product_id' => $item->getProductId()->getId(),
                'specification_id' => $item->getSpecificationId()->getId(),
                'value' => $item->getValue(),
                'custom_field_type' => $item->getCustomFieldsType(),
                'custom_field_value' => $item->getCustomFieldsValue(),
                'create_variation' => $item->getCreateVariation()
            ];
        }

        return $dataProductSPecification;
    }

    /**
     * @return {array}
     * [
     *   id,
     *   specifications:
     *      [ 
     *          { specification, specification_name, property, value }
     *      ]
     *   ]
     */
    public function findProductoSpecificationsByProduct($id_product): array
    {
        $dataProductSPecification = [];

        $prductSpecification = $this->findBy(['productId' => $id_product]);
        
        if (!count($prductSpecification)) return [];
        
        /** @var ProductSpecification $item */
        foreach ($prductSpecification as $item) {
            $dataProductSPecification[] = [
                'id' => $item->getId(),
                'specification' => $item->getSpecificationId()->getId(),
                'specification_name' => $item->getSpecificationId()->getName(),
                'type' => $item->getCustomFieldsType(),
                'property' => $item->getValue(),
                'value' => $item->getCustomFieldsValue()

                // 'specification_id' => $item->getSpecificationId()->getId(),
                // 'value' => $item->getValue(),
                // 'custom_field_type' => $item->getCustomFieldsType(),
                // 'custom_field_value' => $item->getCustomFieldsValue(),
                // 'create_variation' => $item->getCreateVariation()
            ];
        }

        return [
            'id' => $id_product,
            'specifications' => $dataProductSPecification
        ];
    }
}
