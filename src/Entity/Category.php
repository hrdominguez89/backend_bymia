<?php

namespace App\Entity;

use Cocur\Slugify\Slugify;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;



/**
 * @ORM\Entity(repositoryClass="App\Repository\CategoryRepository")
 * @ORM\Table("mia_category")
 * 
 * @UniqueEntity(fields="name", message="La categoría indicada ya se encuentra registrada.")
 * @UniqueEntity(fields="nomenclature", message="La nomenclatura indicada ya se encuentra registrada, por favor intente con otra.")
 * 
 */
class Category
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
     * @ORM\Column(name="name", type="string", length=255, unique=true)
     */
    protected $name;

    /**
     * @var string
     *
     * @ORM\Column(name="slug", type="string", length=255)
     */
    protected $slug;


    /**
     *
     * @ORM\Column(name="id3pl", type="integer", nullable=true)
     */
    protected $id3pl;

    /**
     * @var string|null
     *
     * @ORM\Column(name="image", type="text", nullable=true)
     */
    protected $image;

    /**
     * @ORM\OneToMany(targetEntity=Product::class, mappedBy="category")
     */
    private $products;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $description;

    /**
     * @ORM\Column(type="string", length=2, nullable=true, unique=true)
     */
    private $nomenclature;

    /**
     * @ORM\Column(type="datetime", nullable=false, options={"default":"CURRENT_TIMESTAMP"})
     */
    private $created_at;


    /**
     * @ORM\Column(type="boolean", nullable=false, options={"default":false})
     */
    private $visible;

    /**
     * @ORM\Column(type="boolean", nullable=false, options={"default":false})
     */
    private $principal;

    public function __construct()
    {
        $this->products = new ArrayCollection();
        $this->created_at = new \DateTime();
        $this->visible = false;
        $this->principal = false;
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
        $this->name = strtoupper($name);

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
     * @return string|null
     */
    public function getImage(): ?string
    {
        return $this->image;
    }

    /**
     * @param string|null $image
     * @return $this
     */
    public function setImage(?string $image): self
    {
        $this->image = $image;

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
            "image" => $this->getImage(),
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
            "category" => [
                "id" => $this->getId(),
                "name" => $this->getName(),
                "slug" => $this->getSlug(),
                "image" => $this->getImage(),
                "customFields" => [],
                "parents" => null,
                "children" => null,
            ],
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

    /**
     * @return Collection|Product[]
     */
    public function getProducts(): Collection
    {
        return $this->products;
    }

    public function addProduct(Product $product): self
    {
        if (!$this->products->contains($product)) {
            $this->products[] = $product;
            $product->setCategory($this);
        }

        return $this;
    }

    public function removeProduct(Product $product): self
    {
        if ($this->products->removeElement($product)) {
            // set the owning side to null (unless already changed)
            if ($product->getCategory() === $this) {
                $product->setCategory(null);
            }
        }

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getNomenclature(): ?string
    {
        return strtoupper($this->nomenclature);
    }

    public function setNomenclature(?string $nomenclature): self
    {
        $this->nomenclature = strtoupper($nomenclature);

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeInterface
    {
        return $this->created_at;
    }

    public function setCreatedAt(?\DateTimeInterface $created_at): self
    {
        $this->created_at = $created_at;

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

    public function setSlug(string $slug): self
    {
        $this->slug = $slug;

        return $this;
    }

    public function getPrincipal(): ?bool
    {
        return $this->principal;
    }

    public function setPrincipal(bool $principal): self
    {
        $this->principal = $principal;

        return $this;
    }
}
