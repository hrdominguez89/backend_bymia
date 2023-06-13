<?php

namespace App\Entity;

use App\Repository\ShoppingCartRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=ShoppingCartRepository::class)
 */
class ShoppingCart
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity=Customer::class, inversedBy="shoppingCarts")
     * @ORM\JoinColumn(nullable=false)
     */
    private $customer;

    /**
     * @ORM\ManyToOne(targetEntity=Product::class, inversedBy="shoppingCarts")
     * @ORM\JoinColumn(nullable=false)
     */
    private $product;

    /**
     * @ORM\Column(type="integer", options={"default":1})
     */
    private $quantity;

    /**
     * @ORM\ManyToOne(targetEntity=StatusTypeShoppingCart::class, inversedBy="shoppingCarts")
     * @ORM\JoinColumn(nullable=false)
     */
    private $status;

    /**
     * @ORM\Column(type="datetime", nullable=false, options={"default":"CURRENT_TIMESTAMP"})
     */
    private $created_at;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $updated_at;

    /**
     * @ORM\OneToOne(targetEntity=FavoriteProduct::class, cascade={"persist", "remove"})
     * @ORM\JoinColumn(nullable=true)
     */
    private $favorite;

    /**
     * @ORM\OneToOne(targetEntity=OrdersProducts::class, mappedBy="shopping_cart", cascade={"persist", "remove"})
     */
    private $ordersProducts;

    public function __construct()
    {
        $this->created_at = new \DateTime();
        $this->quantity = 1;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCustomer(): ?Customer
    {
        return $this->customer;
    }

    public function setCustomer(?Customer $customer): self
    {
        $this->customer = $customer;

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

    public function getQuantity(): ?int
    {
        return $this->quantity;
    }

    public function setQuantity(int $quantity): self
    {
        $this->quantity = $quantity;

        return $this;
    }

    public function getStatus(): ?StatusTypeShoppingCart
    {
        return $this->status;
    }

    public function setStatus(?StatusTypeShoppingCart $status): self
    {
        $this->status = $status;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeInterface
    {
        return $this->created_at;
    }

    public function setCreatedAt(\DateTimeInterface $created_at): self
    {
        $this->created_at = $created_at;

        return $this;
    }

    public function getUpdatedAt(): ?\DateTimeInterface
    {
        return $this->updated_at;
    }

    public function setUpdatedAt(?\DateTimeInterface $updated_at): self
    {
        $this->updated_at = $updated_at;

        return $this;
    }

    public function getFavorite(): ?FavoriteProduct
    {
        return $this->favorite;
    }

    public function setFavorite(?FavoriteProduct $favorite): self
    {
        $this->favorite = $favorite;

        return $this;
    }

    public function getOrdersProducts(): ?OrdersProducts
    {
        return $this->ordersProducts;
    }

    public function setOrdersProducts(OrdersProducts $ordersProducts): self
    {
        // set the owning side of the relation if necessary
        if ($ordersProducts->getShoppingCart() !== $this) {
            $ordersProducts->setShoppingCart($this);
        }

        $this->ordersProducts = $ordersProducts;

        return $this;
    }
}
