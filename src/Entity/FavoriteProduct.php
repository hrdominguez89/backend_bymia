<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * @ORM\Entity(repositoryClass="App\Repository\FavoriteProductRepository")
 * @ORM\Table("mia_favorite_product")
 * @UniqueEntity(
 *     fields={"customerId", "productId"},
 *     errorPath="productId",
 *     message="The entity already exists."
 * )
 */
class FavoriteProduct
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="bigint")
     */
    private $id;

    /**
     * @var Customer
     *
     * @ORM\ManyToOne(targetEntity="App\Entity\Customer", inversedBy="favoriteProducts")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="customer_id", referencedColumnName="id", nullable=false)
     * })
     */
    private $customerId;

    /**
     * @var Product
     *
     * @ORM\ManyToOne(targetEntity="App\Entity\Product")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="product_id", referencedColumnName="id", nullable=false)
     * })
     */
    private $productId;

    /**
     * @param Customer $customerId
     * @param Product $product
     */
    public function __construct(Customer $customerId, Product $product)
    {
        $this->customerId = $customerId;
        $this->productId = $product;
    }

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return Customer
     */
    public function getCustomerId(): Customer
    {
        return $this->customerId;
    }

    /**
     * @param Customer $customerId
     * @return $this
     */
    public function setCustomerId(Customer $customerId): FavoriteProduct
    {
        $this->customerId = $customerId;

        $customerId->addFavoriteProduct($this);

        return $this;
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
    public function setProductId(Product $productId): FavoriteProduct
    {
        $this->productId = $productId;

        return $this;
    }


}
