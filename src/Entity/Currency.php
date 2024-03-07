<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\CurrencyRepository")
 * @ORM\Table("mia_currency")
 */
class Currency
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="bigint")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=255, unique=false)
     */
    private $name;

    /**
     * @var string|null
     *
     * @ORM\Column(name="sign", type="string", length=10, nullable=false)
     */
    private $sign;

    /**
     * @ORM\OneToMany(targetEntity=Product::class, mappedBy="currency")
     */
    private $products;

    /**
     * @ORM\OneToMany(targetEntity=OrdersProducts::class, mappedBy="currency")
     */
    private $ordersProducts;

    public function __construct()
    {
        $this->products = new ArrayCollection();
        $this->ordersProducts = new ArrayCollection();
    }

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @param string $name
     * @return $this
     */
    public function setName(string $name): Currency
    {
        $this->name = $name;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getSign(): ?string
    {
        return $this->sign;
    }

    /**
     * @param string|null $sign
     * @return $this
     */
    public function setSign(?string $sign): Currency
    {
        $this->sign = $sign;

        return $this;
    }

    /**
     * @return Collection<int, Product>
     */
    public function getProducts(): Collection
    {
        return $this->products;
    }

    public function addProduct(Product $product): self
    {
        if (!$this->products->contains($product)) {
            $this->products[] = $product;
            $product->setCurrency($this);
        }

        return $this;
    }

    public function removeProduct(Product $product): self
    {
        if ($this->products->removeElement($product)) {
            // set the owning side to null (unless already changed)
            if ($product->getCurrency() === $this) {
                $product->setCurrency(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, OrdersProducts>
     */
    public function getOrdersProducts(): Collection
    {
        return $this->ordersProducts;
    }

    public function addOrdersProduct(OrdersProducts $ordersProduct): self
    {
        if (!$this->ordersProducts->contains($ordersProduct)) {
            $this->ordersProducts[] = $ordersProduct;
            $ordersProduct->setCurrency($this);
        }

        return $this;
    }

    public function removeOrdersProduct(OrdersProducts $ordersProduct): self
    {
        if ($this->ordersProducts->removeElement($ordersProduct)) {
            // set the owning side to null (unless already changed)
            if ($ordersProduct->getCurrency() === $this) {
                $ordersProduct->setCurrency(null);
            }
        }

        return $this;
    }


}
