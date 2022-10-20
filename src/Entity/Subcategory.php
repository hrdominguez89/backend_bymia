<?php

namespace App\Entity;

use Cocur\Slugify\Slugify;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\SubcategoryRepository")
 * @ORM\Table("mia_sub_category")
 */
class Subcategory
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="bigint")
     */
    protected $id;

    /**
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=255)
     */
    protected $name;

    /**
     * @var string
     *
     * @ORM\Column(name="slug", type="string", length=255)
     */
    protected $slug;


    /**
     * @var string|null
     *
     * @ORM\Column(name="id3pl", type="bigint", nullable=true)
     */
    protected $id3pl;

    /**
     * @ORM\ManyToMany(targetEntity=Product::class, mappedBy="subcategory")
     */
    private $products;

    /**
     * @ORM\Column(type="boolean", nullable=true, options={"default":False})
     */
    private $visible;

    /**
     * @ORM\Column(type="datetime",nullable=false, options={"default":"CURRENT_TIMESTAMP"})
     */
    private $created_at;


    public function __construct()
    {
        $this->products = new ArrayCollection();
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
    public function setName(string $name): self
    {
        $this->name = $name;

        $slugify = new Slugify();

        $this->slug = $slugify->slugify($name);

        return $this;
    }

    /**
     * @return string
     */
    public function getSlug(): string
    {
        return $this->slug;
    }


    /**
     * @return int|null
     */
    public function getId3pl(): ?int
    {
        return $this->id3pl;
    }

    /**
     * @param int|null $id3pl
     * @return $this
     */
    public function setId3pl(?int $id3pl): self
    {
        $this->id3pl = $id3pl;

        return $this;
    }


    /**
     * @return array
     */
    public function asArray(): array
    {
        return [
            "id" => $this->getId(),
            "name" => $this->getName(),
            "slug" => $this->getSlug(),
            "customFields" => "",
        ];
    }

    /**
     * @return array
     */
    public function asArray2(): array
    {
        return [
            "slug" => $this->getSlug(),
            "name" => $this->getName(),
            "type" => "child",
        ];
    }

    /**
     * @return string[]
     */
    public function asMenu(): array
    {
        return [
            "type" => 'link',
            "label" => $this->getName(),
            "url" => '/shop/catalog/' . $this->getSlug(),
        ];
    }

    public function setSlug(string $slug): self
    {
        $this->slug = $slug;

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
            $product->addSubcategory($this);
        }

        return $this;
    }

    public function removeProduct(Product $product): self
    {
        if ($this->products->removeElement($product)) {
            $product->removeSubcategory($this);
        }

        return $this;
    }

    public function getVisible(): ?bool
    {
        return $this->visible;
    }

    public function setVisible(?bool $visible): self
    {
        $this->visible = $visible;

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
}
