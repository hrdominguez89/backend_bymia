<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\ProductSubcategoryRepository")
 * @ORM\Table("mia_product_subcategories")
 */
class ProductSubcategory
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
     * @ORM\ManyToOne(targetEntity="App\Entity\Product", inversedBy="productSubcategories")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="product_id", referencedColumnName="id", nullable=false)
     * })
     */
    private $productId;

    /**
     * @var Subcategory
     *
     * @ORM\ManyToOne(targetEntity="App\Entity\Subcategory")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="sub_categoria_id", referencedColumnName="id", nullable=false)
     * })
     */
    private $subCategory;

    public function __construct(Product $product, Subcategory $subcategory)
    {
        $this->setProductId($product);
        $this->setSubCategory($subcategory);
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
    public function setProductId(Product $productId): ProductSubcategory
    {
        $this->productId = $productId;

        $productId->addProductSubcategory($this);

        return $this;
    }

    /**
     * @return Subcategory
     */
        public function getSubCategory(): Subcategory
    {
        return $this->subCategory;
    }

    /**
     * @param Subcategory $subCategory
     * @return $this
     */
    public function setSubCategory(Subcategory $subCategory): ProductSubcategory
    {
        $this->subCategory = $subCategory;

        return $this;
    }


}
