<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\ShoppingRepository")
 * @ORM\Table("mia_shopping_cart")
 */
class Shopping
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
     * @ORM\ManyToOne(targetEntity="App\Entity\Customer", inversedBy="shoppingCarts")
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
     * @var integer
     *
     * @ORM\Column(name="quantity", type="integer")
     */
    private $quantity;

    /**
     * @var float
     *
     * @ORM\Column(name="price", type="float", nullable=true)
     */
    private $price;

    /**
     * @param Customer $customerId
     * @param Product $productId
     * @param float|int $quantity
     */
    public function __construct(Customer $customerId, Product $productId, $quantity)
    {
        $this->customerId = $customerId;
        $this->productId = $productId;
        $this->quantity = $quantity;
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
    public function setCustomerId(Customer $customerId): Shopping
    {
        $this->customerId = $customerId;

        $customerId->addShoppingCart($this);

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
    public function setProductId(Product $productId): Shopping
    {
        $this->productId = $productId;

        return $this;
    }

    /**
     * @return integer
     */
    public function getQuantity()
    {
        return $this->quantity;
    }

    /**
     * @param $quantity
     * @return $this
     */
    public function setQuantity($quantity): Shopping
    {
        $this->quantity = $quantity;

        return $this;
    }

    /**
     * @return float|null
     */
    public function getPrice(): ?float
    {
        return $this->price;
    }

    /**
     * @param float|null $price
     * @return $this
     */
    public function setPrice(?float $price): Shopping
    {
        $this->price = $price;

        return $this;
    }

    /**
     * @param false $full
     * @return array
     */
    public function asArray(bool $full = false): array
    {
        return $full
            ? [
                'name' => $this->getProductId()->getName(),
                'description' => substr($this->getProductId()->getShortDescription(), 0, 40).'...',
                'sku' => $this->getProductId()->getSku(),
                'unit_amount' => [
                    'currency_code' => 'USD',
                    'value' => $this->getProductId()->calcPrice(),
                ],
                'tax' => [
                    'currency_code' => 'USD',
                    'value' => '0.00',
                ],
                'quantity' => $this->getQuantity(),
                'category' => 'PHYSICAL_GOODS',
            ]
            : [
                "product" => $this->getProductId()->asArray(false),
                "options" => [
                    "name" => '',
                    "value" => '',
                ],
                "quantity" => $this->getQuantity(),
            ];
    }

}
