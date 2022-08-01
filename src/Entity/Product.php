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

    const NO_PARENT = 'no--parent';

    /**
     * @var Brand
     *
     * @ORM\ManyToOne(targetEntity="App\Entity\Brand",cascade={"persist"})
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="brand_id", referencedColumnName="id", nullable=true)
     * })
     */
    private $brandId;

    /**
     * @var ArrayCollection|ProductSpecification[]
     *
     * @ORM\OneToMany(targetEntity="App\Entity\ProductSpecification", mappedBy="productId", cascade={"remove"})
     */
    private $productSpecifications;

    /**
     * @var ArrayCollection|ProductSubcategory[]
     *
     * @ORM\OneToMany(targetEntity="App\Entity\ProductSubcategory", mappedBy="productId", cascade={"remove"})
     */
    private $productSubcategories;

    /**
     * @var ArrayCollection|ProductImages[]
     *
     * @ORM\OneToMany(targetEntity="App\Entity\ProductImages", mappedBy="productId", cascade={"remove"})
     */
    private $images;

    /**
     * @var ArrayCollection|ProductTag[]
     *
     * @ORM\OneToMany(targetEntity="App\Entity\ProductTag", mappedBy="productId", cascade={"remove"})
     */
    private $productTag;

    public function __construct()
    {
        parent::__construct();

        $this->productSpecifications = new ArrayCollection();
        $this->productSubcategories = new ArrayCollection();
        $this->images = new ArrayCollection();
        $this->productTag = new ArrayCollection();
    }

    /**
     * @return Brand
     */
    public function getBrandId(): ?Brand
    {
        return $this->brandId;
    }

    /**
     * @param Brand $brandId
     * @return $this
     */
    public function setBrandId(Brand $brandId): Product
    {
        $this->brandId = $brandId;

        return $this;
    }

    /**
     * @return ProductSpecification[]|ArrayCollection
     */
    public function getProductSpecifications()
    {
        return $this->productSpecifications;
    }

    /**
     * @param ProductSpecification $productSpecification
     * @return $this
     */
    public function addProductSpecification(ProductSpecification $productSpecification): Product
    {
        if (!$this->productSpecifications->contains($productSpecification)) {
            $this->productSpecifications[] = $productSpecification;
        }

        return $this;
    }

    /**
     * @param ProductSpecification $productSpecification
     * @return $this
     */
    public function removeProductSpecification(ProductSpecification $productSpecification): Product
    {
        if ($this->productSpecifications->contains($productSpecification)) {
            $this->productSpecifications->removeElement($productSpecification);
        }

        return $this;
    }

    /**
     * @return int|mixed
     */
    public function getCategoryId()
    {
        return $this->productSubcategories->count() > 0 ? $this->productSubcategories[0]->getSubCategory()->getId() : 0;
    }

    /**
     * @return string
     */
    public function getCategoryName(): string
    {
        return $this->productSubcategories->count() > 0
            ? $this->productSubcategories[0]->getSubCategory()->getName()
            : '';
    }

    /**
     * @return ProductSubcategory[]|ArrayCollection
     */
    public function getProductSubcategories()
    {
        return $this->productSubcategories;
    }

    /**
     * @param ProductSubcategory $productSubcategory
     * @return $this
     */
    public function addProductSubcategory(ProductSubcategory $productSubcategory): Product
    {
        if (!$this->productSubcategories->contains($productSubcategory)) {
            $this->productSubcategories[] = $productSubcategory;
        }

        return $this;
    }

    /**
     * @param ProductSubcategory $productSubcategory
     * @return $this
     */
    public function removeProductSubcategory(ProductSubcategory $productSubcategory): Product
    {
        if ($this->productSubcategories->contains($productSubcategory)) {
            $this->productSubcategories->removeElement($productSubcategory);
        }

        return $this;
    }

    /**
     * @return string|null
     */
    public function getImage(): ?string
    {
        if(!parent::getImage() && $this->images->count() > 0){
            return $this->images[0]->getImage();
        }

        return parent::getImage();
    }

    /**
     * @return ProductImages[]|ArrayCollection
     */
    public function getImages()
    {
        return $this->images;
    }

    /**
     * @param ProductImages $productImages
     * @return $this
     */
    public function addProductImages(ProductImages $productImages): Product
    {
        if (!$this->images->contains($productImages)) {
            $this->images[] = $productImages;
        }

        return $this;
    }

    /**
     * @param ProductImages $productImages
     * @return $this
     */
    public function removeProductImages(ProductImages $productImages): Product
    {
        if ($this->images->contains($productImages)) {
            $this->images->removeElement($productImages);
        }

        return $this;
    }

    /**
     * @return ProductTag[]|ArrayCollection
     */
    public function getProductTag()
    {
        return $this->productTag;
    }

    /**
     * @param ProductTag $productTag
     * @return $this
     */
    public function addProductTag(ProductTag $productTag): Product
    {
        if (!$this->productTag->contains($productTag)) {
            $this->productTag[] = $productTag;
        }

        return $this;
    }

    /**
     * @param ProductTag $productTag
     * @return $this
     */
    public function removeProductTag(ProductTag $productTag): Product
    {
        if ($this->productTag->contains($productTag)) {
            $this->productTag->removeElement($productTag);
        }

        return $this;
    }

    /**
     * @param bool $full
     * @param array $customFields
     * @return array
     */
    public function asArray(bool $full = true, array $customFields = []): array
    {
        $images = $this->getAllImages();

        [$categories, $attrs] = [[], []];

        if ($full) {
            $categories = $this->getAllCategories();
            $attrs = $this->getAllSpecifications();
        }

        return array_merge(parent::asArray(), [
            "brand" => $this->getBrandId() ? $this->getBrandId()->asArray() : null,
            "images" => $images,
            "categories" => $categories,
            "attributes" => $attrs,
            "customFields" => $customFields,
        ]);
    }

    /**
     * @return array
     */
    public function getAllCategories(): array
    {
        $categories = [];
        foreach ($this->getProductSubcategories() as $productSubcategory) {
            $categories[] = $productSubcategory->getSubCategory()->asArray();
        }

        return $categories;
    }

    /**
     * @return array
     */
    public function getAllSpecifications(): array
    {
        $attrs = [];
        foreach ($this->getProductSpecifications() as $productSpecification) {
            $attrs[] = $productSpecification->asArray($this->getId(), $this->getSlug());
        }

        return $attrs;
    }

    /**
     * @return array
     */
    public function getAllImages(): array
    {
        $images = [];
        foreach ($this->getImages() as $image) {
            $images[] = $image->getImage();
        }

        return $images;
    }

    public function addImage(ProductImages $image): self
    {
        if (!$this->images->contains($image)) {
            $this->images[] = $image;
            $image->setProductId($this);
        }

        return $this;
    }

    public function removeImage(ProductImages $image): self
    {
        if ($this->images->removeElement($image)) {
            // set the owning side to null (unless already changed)
            if ($image->getProductId() === $this) {
                $image->setProductId(null);
            }
        }

        return $this;
    }


}
