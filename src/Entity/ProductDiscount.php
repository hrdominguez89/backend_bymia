<?php

namespace App\Entity;

use App\Repository\ProductDiscountRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=ProductDiscountRepository::class)
 */
class ProductDiscount
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity=Product::class, inversedBy="productDiscounts")
     * @ORM\JoinColumn(nullable=false)
     */
    private $product;

    /**
     * @ORM\Column(type="integer")
     */
    private $percentage_discount;

    /**
     * @ORM\Column(type="date")
     */
    private $start_date;

    /**
     * @ORM\Column(type="date")
     */
    private $end_date;

    /**
     * @ORM\Column(type="datetime", options={"default":"CURRENT_TIMESTAMP"})
     */
    private $created_at;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="productDiscounts")
     * @ORM\JoinColumn(nullable=false)
     */
    private $created_by_user;

    /**
     * @ORM\Column(type="bigint")
     */
    private $product_limit;

    /**
     * @ORM\Column(type="bigint",options={"default":0})
     */
    private $used;

    /**
     * @ORM\Column(type="boolean",options={"default":true})
     */
    private $active;

    /**
     * @ORM\OneToMany(targetEntity=OrdersProducts::class, mappedBy="product_discount")
     */
    private $ordersProducts;

    public function __construct()
    {
        $this->ordersProducts = new ArrayCollection();
        $this->active = true;
        $this->used = 0;
        $this->created_at = new \DateTime();
    }

    public function getId(): ?int
    {
        return $this->id;
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

    public function getPercentageDiscount(): ?int
    {
        return $this->percentage_discount;
    }

    public function setPercentageDiscount(int $percentage_discount): self
    {
        $this->percentage_discount = $percentage_discount;

        return $this;
    }

    public function getStartDate(): ?\DateTimeInterface
    {
        return $this->start_date;
    }

    public function setStartDate(\DateTimeInterface $start_date): self
    {
        $this->start_date = $start_date;

        return $this;
    }

    public function getEndDate(): ?\DateTimeInterface
    {
        return $this->end_date;
    }

    public function setEndDate(\DateTimeInterface $end_date): self
    {
        $this->end_date = $end_date;

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

    public function getCreatedByUser(): ?User
    {
        return $this->created_by_user;
    }

    public function setCreatedByUser(?User $created_by_user): self
    {
        $this->created_by_user = $created_by_user;

        return $this;
    }

    public function getProductLimit(): ?string
    {
        return $this->product_limit;
    }

    public function setProductLimit(string $product_limit): self
    {
        $this->product_limit = $product_limit;

        return $this;
    }

    public function getUsed(): ?string
    {
        return $this->used;
    }

    public function setUsed(string $used): self
    {
        $this->used = $used;

        return $this;
    }

    public function getActive(): ?bool
    {
        return $this->active;
    }

    public function setActive(bool $active): self
    {
        $this->active = $active;

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
            $ordersProduct->setProductDiscount($this);
        }

        return $this;
    }

    public function removeOrdersProduct(OrdersProducts $ordersProduct): self
    {
        if ($this->ordersProducts->removeElement($ordersProduct)) {
            // set the owning side to null (unless already changed)
            if ($ordersProduct->getProductDiscount() === $this) {
                $ordersProduct->setProductDiscount(null);
            }
        }

        return $this;
    }
}
