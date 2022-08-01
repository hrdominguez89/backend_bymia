<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass="App\Repository\ProductSpecificationRepository")
 * @ORM\Table("mia_product_specification")
 */
class ProductSpecification
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="bigint")
     */
    private $id;

    /**
     * @var Product
     *
     * @ORM\ManyToOne(targetEntity="App\Entity\Product", inversedBy="productSpecifications")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="product_id", referencedColumnName="id", nullable=false)
     * })
     */
    private $productId;

    /**
     * @var Specification
     *
     * @ORM\ManyToOne(targetEntity="App\Entity\Specification")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="specification_id", referencedColumnName="id", nullable=false)
     * })
     */
    private $specificationId;

    /**
     * @var string
     *
     * @ORM\Column(name="value", type="string", length=255)
     */
    private $value;

    /**
     * @var string
     *
     * @ORM\Column(name="custom_fields_type", type="string", length=255)
     * @Assert\Choice({"select", "color", "image", "check"})
     */
    private $customFieldsType;

    /**
     * @var string
     *
     * @ORM\Column(name="custom_fields_value", type="string", length=255)
     */
    private $customFieldsValue;

    /**
     * @var bool|null
     *
     * @ORM\Column(name="create_variation", type="boolean", nullable=true)
     */
    private $createVariation;


    public function __construct(Product $product, Specification $specification)
    {
        $this->createVariation = false;

        $this->setProductId($product);
        $this->setSpecificationId($specification);
    }

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return Product
     */
    public function getProductId(): Product
    {
        return $this->productId;
    }

    /**
     * @param Product $productId
     * @return $this
     */
    public function setProductId(Product $productId): ProductSpecification
    {
        $this->productId = $productId;

        $productId->addProductSpecification($this);

        return $this;
    }

    /**
     * @return Specification
     */
    public function getSpecificationId(): Specification
    {
        return $this->specificationId;
    }

    /**
     * @param Specification $specificationId
     * @return $this
     */
    public function setSpecificationId(Specification $specificationId): ProductSpecification
    {
        $this->specificationId = $specificationId;

        return $this;
    }

    /**
     * @return string
     */
    public function getValue(): string
    {
        return $this->value;
    }

    /**
     * @param string $value
     * @return $this
     */
    public function setValue(string $value): ProductSpecification
    {
        $this->value = $value;

        return $this;
    }

    /**
     * @return bool|null
     */
    public function getCreateVariation(): ?bool
    {
        return $this->createVariation;
    }

    /**
     * @param bool|null $createVariation
     * @return $this
     */
    public function setCreateVariation(?bool $createVariation): ProductSpecification
    {
        $this->createVariation = $createVariation;

        return $this;
    }

    /**
     * @return string
     */
    public function getCustomFieldsType(): string
    {
        return $this->customFieldsType;
    }

    /**
     * @param string $customFieldsType
     * @return $this
     */
    public function setCustomFieldsType(string $customFieldsType): ProductSpecification
    {
        $this->customFieldsType = $customFieldsType;

        return $this;
    }

    /**
     * @return string
     */
    public function getCustomFieldsValue(): string
    {
        return $this->customFieldsValue;
    }

    /**
     * @param string $customFieldsValue
     * @return $this
     */
    public function setCustomFieldsValue(string $customFieldsValue): ProductSpecification
    {
        $this->customFieldsValue = $customFieldsValue;

        return $this;
    }

    /**
     * @param null $pId
     * @param null $pSlug
     * @return array
     */
    public function asArray($pId = null, $pSlug = null): array
    {
        $specificationType = $this->getSpecificationId()->getSpecificationTypeId();

        $values = [];
        foreach ($specificationType->getSpecifications() as $specification) {
            $values[] = [
                "name" => $specification->getName(),
                "slug" => $specification->getSlug(),
                "customFields" => [
                    "value" => $this->getCustomFieldsValue(),
                    "pId" => $pId ?? $this->getProductId()->getId(),
                    "pSlug" => $pSlug ?? $this->getProductId()->getSlug()
                ],
            ];
        }

        return [
            "name" => $specificationType->getName(),
            "slug" => $specificationType->getSlug(),
            "values" => $values,
            "customFields" => [
                "type" => $this->getCustomFieldsType(), // select|color|image,
                "pId" => $pId ?? $this->getProductId()->getId(),
                "pSlug" => $pSlug ?? $this->getProductId()->getSlug()
            ],
        ];
    }


}
