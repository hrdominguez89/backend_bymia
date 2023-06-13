<?php

namespace App\Entity;

use App\Repository\OrdersProductsRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=OrdersProductsRepository::class)
 */
class OrdersProducts
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity=Orders::class, inversedBy="ordersProducts")
     * @ORM\JoinColumn(nullable=false)
     */
    private $number_order;

    /**
     * @ORM\ManyToOne(targetEntity=Product::class, inversedBy="ordersProducts")
     * @ORM\JoinColumn(nullable=false)
     */
    private $product;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $name;

    /**
     * @ORM\Column(type="string", length=40)
     */
    private $sku;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $part_number;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $cod;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $weight;

    /**
     * @ORM\Column(type="float",nullable=true)
     */
    private $price;

    /**
     * @ORM\Column(type="integer")
     */
    private $quantity;

    /**
     * @ORM\Column(type="float",nullable=true)
     */
    private $discount;

    /**
     * @ORM\ManyToOne(targetEntity=ProductDiscount::class, inversedBy="ordersProducts")
     * @ORM\JoinColumn(nullable=true)
     */
    private $product_discount;

    /**
     * @ORM\OneToOne(targetEntity=ShoppingCart::class, inversedBy="ordersProducts", cascade={"persist", "remove"})
     * @ORM\JoinColumn(nullable=true)
     */
    private $shopping_cart;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNumberOrder(): ?Orders
    {
        return $this->number_order;
    }

    public function setNumberOrder(?Orders $number_order): self
    {
        $this->number_order = $number_order;

        return $this;
    }

    public function getProduct(): ?Product
    {
        return $this->product;
    }

    public function setProduct(?Product $product): self
    {
        $this->product = $product;

        return $this;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getSku(): ?string
    {
        return $this->sku;
    }

    public function setSku(string $sku): self
    {
        $this->sku = $sku;

        return $this;
    }

    public function getPartNumber(): ?string
    {
        return $this->part_number;
    }

    public function setPartNumber(?string $part_number): self
    {
        $this->part_number = $part_number;

        return $this;
    }

    public function getCod(): ?string
    {
        return $this->cod;
    }

    public function setCod(?string $cod): self
    {
        $this->cod = $cod;

        return $this;
    }

    public function getWeight(): ?string
    {
        return $this->weight;
    }

    public function setWeight(string $weight): self
    {
        $this->weight = $weight;

        return $this;
    }

    public function getPrice(): ?float
    {
        return $this->price;
    }

    public function setPrice(float $price): self
    {
        $this->price = $price;

        return $this;
    }

    public function getQuantity(): ?int
    {
        return $this->quantity;
    }

    public function setQuantity(int $quantity): self
    {
        $this->quantity = $quantity;

        return $this;
    }

    public function getDiscount(): ?float
    {
        return $this->discount;
    }

    public function setDiscount(float $discount): self
    {
        $this->discount = $discount;

        return $this;
    }

    public function getProductDiscount(): ?ProductDiscount
    {
        return $this->product_discount;
    }

    public function setProductDiscount(?ProductDiscount $product_discount): self
    {
        $this->product_discount = $product_discount;

        return $this;
    }

    public function getShoppingCart(): ?ShoppingCart
    {
        return $this->shopping_cart;
    }

    public function setShoppingCart(ShoppingCart $shopping_cart): self
    {
        $this->shopping_cart = $shopping_cart;

        return $this;
    }
}
