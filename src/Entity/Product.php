<?php

namespace App\Entity;

use App\Entity\Model\Product as BaseProduct;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\ProductRepository")
 * @ORM\Table("mia_product")
 */
class Product extends BaseProduct
{

    /**
     * @ORM\Column(type="string", length=100, nullable="true")
     */
    private $cod;

    /**
     * @ORM\Column(type="string", length=100, nullable="true")
     */
    private $part_number;

    /**
     * @ORM\Column(type="integer", nullable="true")
     */
    private $onhand;

    /**
     * @ORM\Column(type="integer", nullable="true")
     */
    private $commited;

    /**
     * @ORM\Column(type="integer", nullable="true")
     */
    private $incomming;

    /**
     * @ORM\Column(type="integer", nullable="true")
     */
    private $available;

    /**
     * @ORM\Column(type="integer", nullable="true")
     */
    private $id_3pl;

    /**
     * @ORM\ManyToOne(targetEntity=Warehouses::class, inversedBy="products")
     */
    private $warehouse;

    /**
     * @ORM\ManyToOne(targetEntity=Category::class, inversedBy="products")
     */
    private $category;

    /**
     * @ORM\ManyToOne(targetEntity=Subcategory::class, inversedBy="products")
     */
    private $subcategory;

    /**
     * @ORM\ManyToOne(targetEntity=Brand::class, inversedBy="products")
     */
    private $brand;

    /**
     * @ORM\ManyToOne(targetEntity=ProductCondition::class, inversedBy="products")
     */
    private $condition;

    /**
     * @ORM\ManyToOne(targetEntity=ProductStatusType::class, inversedBy="products")
     */
    private $status_type;

    public function __construct()
    {
        parent::__construct();
    }

    public function getCod(): ?string
    {
        return $this->cod;
    }

    public function setCod(string $cod): self
    {
        $this->cod = $cod;

        return $this;
    }

    public function getPartNumber(): ?string
    {
        return $this->part_number;
    }

    public function setPartNumber(string $part_number): self
    {
        $this->part_number = $part_number;

        return $this;
    }

    public function getOnhand(): ?int
    {
        return $this->onhand;
    }

    public function setOnhand(int $onhand): self
    {
        $this->onhand = $onhand;

        return $this;
    }

    public function getCommited(): ?int
    {
        return $this->commited;
    }

    public function setCommited(int $commited): self
    {
        $this->commited = $commited;

        return $this;
    }

    public function getIncomming(): ?int
    {
        return $this->incomming;
    }

    public function setIncomming(int $incomming): self
    {
        $this->incomming = $incomming;

        return $this;
    }

    public function getAvailable(): ?int
    {
        return $this->available;
    }

    public function setAvailable(int $available): self
    {
        $this->available = $available;

        return $this;
    }

    public function getId3pl(): ?int
    {
        return $this->id_3pl;
    }

    public function setId3pl(int $id_3pl): self
    {
        $this->id_3pl = $id_3pl;

        return $this;
    }

    public function getWarehouse(): ?Warehouses
    {
        return $this->warehouse;
    }

    public function setWarehouse(?Warehouses $warehouse): self
    {
        $this->warehouse = $warehouse;

        return $this;
    }

    public function getCategory(): ?Category
    {
        return $this->category;
    }

    public function setCategory(?Category $category): self
    {
        $this->category = $category;

        return $this;
    }

    public function getSubcategory(): ?Subcategory
    {
        return $this->subcategory;
    }

    public function setSubcategory(?Subcategory $subcategory): self
    {
        $this->subcategory = $subcategory;

        return $this;
    }

    public function getBrand(): ?Brand
    {
        return $this->brand;
    }

    public function setBrand(?Brand $brand): self
    {
        $this->brand = $brand;

        return $this;
    }

    public function getCondition(): ?ProductCondition
    {
        return $this->condition;
    }

    public function setCondition(?ProductCondition $condition): self
    {
        $this->condition = $condition;

        return $this;
    }

    public function getStatusType(): ?ProductStatusType
    {
        return $this->status_type;
    }

    public function setStatusType(?ProductStatusType $status_type): self
    {
        $this->status_type = $status_type;

        return $this;
    }
}
