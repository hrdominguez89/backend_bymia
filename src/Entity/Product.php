<?php

namespace App\Entity;

use Cocur\Slugify\Slugify;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

use ApiPlatform\Core\Annotation\ApiResource;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ORM\Entity(repositoryClass="App\Repository\ProductRepository")
 * @ORM\Table("mia_product")
 * 
 * @ApiResource(
 * 
 * )
 * 
 */
class Product
{

    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="bigint")
     * 
     */
    protected $id;

    /**
     * @var string|null
     *
     * @ORM\Column(name="sku", type="string", length=255, nullable=true, unique=true)
     */
    protected $sku;


    /**
     * @var string|null
     *
     * @ORM\Column(name="title", type="string", nullable=true, length=255)
     */
    protected $title;

    /**
     * @var string
     *
     * @ORM\Column(name="slug", type="string", length=255)
     */
    protected $slug;

    /**
     * @var string|null
     *
     * @ORM\Column(name="weight", type="string", length=255, nullable=true)
     */
    protected $weight;

    /**
     * @var float|null
     *
     * @ORM\Column(name="cost", type="float", nullable=true)
     */
    protected $cost;

    /**
     * @var string|null
     *
     * @ORM\Column(name="description", type="text", nullable=true)
     */
    protected $description;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="created_at", type="datetime", nullable=false, options={"default":"CURRENT_TIMESTAMP"})
     */
    protected $createdAt;

    /**
     * @ORM\Column(type="string", length=100, nullable="true")
     */
    private $cod;

    /**
     * @ORM\Column(type="string", length=15, nullable="true")
     * 
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
     * 
     * 
     */
    private $incomming;

    /**
     * @ORM\Column(type="integer", nullable="true")
     */
    private $available;

    /**
     * @ORM\Column(name="id3pl",type="integer", nullable="true")
     */
    private $id3pl;

    /**
     * @ORM\ManyToOne(targetEntity=Warehouses::class, inversedBy="products")
     */
    private $warehouse;

    /**
     * @ORM\ManyToOne(targetEntity=Category::class, inversedBy="products")
     */
    private $category;

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

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $color;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $screen_resolution;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $cpu;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $gpu;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $ram;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $memory;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $screen_size;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $op_sys;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $model;

    /**
     * @ORM\ManyToMany(targetEntity=Subcategory::class, inversedBy="products")
     */
    private $subcategory;

    /**
     * @ORM\Column(type="boolean", nullable=true, options={"default":False})
     */
    private $visible;

    /**
     * @ORM\ManyToMany(targetEntity=Tag::class, inversedBy="products")
     */
    private $tag;

    /**
     * @ORM\Column(type="string", length=20, nullable=true)
     */
    private $conditium;

    /**
     * @ORM\OneToMany(targetEntity=ImagesProducts::class, mappedBy="product", orphanRemoval=true)
     */
    private $imagesProducts;

    public function __construct()
    {
        $this->createdAt = new \DateTime();
        $this->subcategory = new ArrayCollection();
        $this->tag = new ArrayCollection();
        $this->imagesProducts = new ArrayCollection();
        $this->visible = false;
    }

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return string|null
     */
    public function getSku(): ?string
    {
        return $this->sku;
    }

    /**
     * @param string|null $sku
     * @return $this
     */
    public function setSku(?string $sku): self
    {
        $this->sku = $sku;

        return $this;
    }


    /**
     * @return string|null
     */
    public function getTitle(): ?string
    {
        return $this->title;
    }

    /**
     * @param string|null $title
     * @return $this
     */
    public function setTitle(?string $title): self
    {
        $this->title = $title;

        $slugify = new Slugify();

        $this->slug = $slugify->slugify($title);

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
     * @return string|null
     */
    public function getWeight(): ?string
    {
        return $this->weight;
    }

    /**
     * @param string|null $weight
     * @return $this
     */
    public function setWeight(?string $weight): self
    {
        $this->weight = $weight;

        return $this;
    }

    /**
     * @return float|null
     */
    public function getCost(): ?float
    {
        return $this->cost;
    }

    /**
     * @param float|null $cost
     * @return $this
     */
    public function setCost(?float $cost): self
    {
        $this->cost = $cost;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getDescription(): ?string
    {
        return $this->description;
    }

    /**
     * @param string|null $description
     * @return $this
     */
    public function setDescription(?string $description): self
    {
        $this->description = $description;

        return $this;
    }


    /**
     * @return \DateTime
     */
    public function getCreatedAt(): ?\DateTime
    {
        return $this->createdAt;
    }

    /**
     * @param \DateTime $createdAt
     * @return $this
     */
    public function setCreatedAt(\DateTime $createdAt): self
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    /**
     * @return array
     */
    public function asArray(): array
    {
        return [
            "id" => $this->getId(),
            "slug" => $this->getSlug(),
            "title" => $this->getTitle(),
            "sku" => $this->getSku(),
            "description" => $this->getDescription(),
            "customFields" => "",
        ];
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
        return $this->id3pl;
    }

    public function setId3pl(int $id3pl): self
    {
        $this->id3pl = $id3pl;

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

    public function setSlug(string $slug): self
    {
        $this->slug = $slug;

        return $this;
    }

    public function getColor(): ?string
    {
        return $this->color;
    }

    public function setColor(?string $color): self
    {
        $this->color = $color;

        return $this;
    }

    public function getScreenResolution(): ?string
    {
        return $this->screen_resolution;
    }

    public function setScreenResolution(string $screen_resolution): self
    {
        $this->screen_resolution = $screen_resolution;

        return $this;
    }

    public function getCpu(): ?string
    {
        return $this->cpu;
    }

    public function setCpu(?string $cpu): self
    {
        $this->cpu = $cpu;

        return $this;
    }

    public function getGpu(): ?string
    {
        return $this->gpu;
    }

    public function setGpu(?string $gpu): self
    {
        $this->gpu = $gpu;

        return $this;
    }

    public function getRam(): ?string
    {
        return $this->ram;
    }

    public function setRam(?string $ram): self
    {
        $this->ram = $ram;

        return $this;
    }

    public function getMemory(): ?string
    {
        return $this->memory;
    }

    public function setMemory(?string $memory): self
    {
        $this->memory = $memory;

        return $this;
    }

    public function getScreenSize(): ?string
    {
        return $this->screen_size;
    }

    public function setScreenSize(?string $screen_size): self
    {
        $this->screen_size = $screen_size;

        return $this;
    }

    public function getOpSys(): ?string
    {
        return $this->op_sys;
    }

    public function setOpSys(?string $op_sys): self
    {
        $this->op_sys = $op_sys;

        return $this;
    }

    public function getModel(): ?string
    {
        return $this->model;
    }

    public function setModel(?string $model): self
    {
        $this->model = $model;

        return $this;
    }

    /**
     * @return Collection<int, Subcategory>
     */
    public function getSubcategory(): Collection
    {
        return $this->subcategory;
    }

    public function addSubcategory(Subcategory $subcategory): self
    {
        if (!$this->subcategory->contains($subcategory)) {
            $this->subcategory[] = $subcategory;
        }

        return $this;
    }

    public function removeSubcategory(Subcategory $subcategory): self
    {
        $this->subcategory->removeElement($subcategory);

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

    /**
     * @return Collection<int, tag>
     */
    public function getTag(): Collection
    {
        return $this->tag;
    }

    public function addTag(Tag $tag): self
    {
        if (!$this->tag->contains($tag)) {
            $this->tag[] = $tag;
        }

        return $this;
    }

    public function removeTag(Tag $tag): self
    {
        $this->tag->removeElement($tag);

        return $this;
    }

    public function getConditium(): ?string
    {
        return $this->conditium;
    }

    public function setConditium(?string $conditium): self
    {
        $this->conditium = $conditium;

        return $this;
    }

    /**
     * @return Collection<int, ImagesProducts>
     */
    public function getImagesProducts(): Collection
    {
        return $this->imagesProducts;
    }

    public function addImagesProduct(ImagesProducts $imagesProduct): self
    {
        if (!$this->imagesProducts->contains($imagesProduct)) {
            $this->imagesProducts[] = $imagesProduct;
            $imagesProduct->setProduct($this);
        }

        return $this;
    }

    public function removeImagesProduct(ImagesProducts $imagesProduct): self
    {
        if ($this->imagesProducts->removeElement($imagesProduct)) {
            // set the owning side to null (unless already changed)
            if ($imagesProduct->getProduct() === $this) {
                $imagesProduct->setProduct(null);
            }
        }

        return $this;
    }
}
