<?php

namespace App\Entity;

use Cocur\Slugify\Slugify;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;



/**
 * @ORM\Entity(repositoryClass="App\Repository\ProductRepository")
 * @ORM\Table("mia_product")
 * 
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
     * @ORM\Column(name="sku", type="string", length=255, nullable=false, unique=true)
     * @Assert\Length(min=20, max=28)
     * @Assert\Regex(
     *     pattern="/^[A-Za-z0-9]{2}-[A-Za-z0-9]{3}-[A-Za-z0-9]{6}-[A-Za-z0-9]{2}-[A-Za-z0-9]{3}(?:-[A-Za-z0-9]{3}(?:-[A-Za-z0-9]{3})?)?$/",
     *     message="El sku no cumple con el formato requerido"
     * )
     */
    protected $sku;


    /**
     * @var string|null
     *
     * @ORM\Column(name="name", type="string", nullable=false, length=255)
     */
    protected $name;

    /**
     * @var string
     *
     * @ORM\Column(name="slug", type="string", nullable=false, length=255)
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
     * @ORM\Column(name="cost", type="float", nullable=false)
     */
    protected $cost;

    /**
     * @var string|null
     *
     * @ORM\Column(name="description_es", type="text", nullable=true)
     */
    protected $descriptionEs;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="created_at", type="datetime", nullable=false, options={"default":"CURRENT_TIMESTAMP"})
     */
    protected $created_at;

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
     * @ORM\Column(type="integer", nullable=false, options={"default":0})
     */
    private $onhand;

    /**
     * @ORM\Column(type="integer", nullable=false, options={"default":0})
     */
    private $commited;

    /**
     * @ORM\Column(type="integer", nullable=false, options={"default":0})
     * 
     * 
     */
    private $incomming;

    /**
     * @ORM\Column(type="integer", nullable=false, options={"default":0})
     */
    private $available;

    /**
     * @ORM\Column(name="id3pl",type="integer", nullable="true")
     */
    private $id3pl;

    /**
     * @ORM\ManyToOne(targetEntity=Category::class, inversedBy="products")
     */
    private $category;

    /**
     * @ORM\ManyToOne(targetEntity=Brand::class, inversedBy="products")
     */
    private $brand;

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
     * @ORM\OneToMany(targetEntity=ProductImages::class, mappedBy="product", orphanRemoval=true)
     */
    private $image;

    /**
     * @ORM\Column(name="description_en",type="text")
     */
    private $descriptionEn;

    /**
     * @ORM\Column(type="float")
     */
    private $price;

    /**
     * @ORM\ManyToOne(targetEntity=Inventory::class, inversedBy="products")
     * @ORM\JoinColumn(nullable=false)
     */
    private $inventory;

    /**
     * @ORM\ManyToOne(targetEntity=Subcategory::class, inversedBy="products")
     */
    private $subcategory;

    /**
     * @ORM\ManyToOne(targetEntity=CommunicationStatesBetweenPlatforms::class, inversedBy="products")
     * @ORM\JoinColumn(nullable=false, options={"default":1})
     */
    private $status_sent_3pl;

    /**
     * @ORM\Column(type="smallint", options={"default":0})
     */
    private $attempts_send_3pl;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $error_message_3pl;

    /**
     * @ORM\OneToMany(targetEntity=HistoryProductStockUpdated::class, mappedBy="product")
     */
    private $historyProductStockUpdateds;

    /**
     * @ORM\OneToMany(targetEntity=ItemsGuideNumber::class, mappedBy="product")
     */
    private $itemsGuideNumbers;

    /**
     * @ORM\OneToMany(targetEntity=OrdersProducts::class, mappedBy="product")
     */
    private $ordersProducts;

    public function __construct()
    {
        $this->created_at = new \DateTime();
        $this->tag = new ArrayCollection();
        $this->visible = false;
        $this->image = new ArrayCollection();
        $this->onhand = 0;
        $this->commited = 0;
        $this->incomming = 0;
        $this->available = 0;
        $this->attempts_send_3pl = 0;
        $this->historyProductStockUpdateds = new ArrayCollection();
        $this->itemsGuideNumbers = new ArrayCollection();
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
    public function getName(): ?string
    {
        return $this->name;
    }

    /**
     * @param string|null $name
     * @return $this
     */
    public function setName(?string $name): self
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
    public function getDescriptionEs(): ?string
    {
        return $this->descriptionEs;
    }

    /**
     * @param string|null $descriptionEs
     * @return $this
     */
    public function setDescriptionEs(?string $descriptionEs): self
    {
        $this->descriptionEs = $descriptionEs;

        return $this;
    }


    /**
     * @return \DateTime
     */
    public function getCreatedAt(): ?\DateTime
    {
        return $this->created_at;
    }

    /**
     * @param \DateTime $created_at
     * @return $this
     */
    public function setCreatedAt(\DateTime $created_at): self
    {
        $this->created_at = $created_at;

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
            "name" => $this->getName(),
            "sku" => $this->getSku(),
            "descriptionEs" => $this->getDescriptionEs(),
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
     * @return Collection<int, ProductImages>
     */
    public function getImage(): Collection
    {
        return $this->image;
    }

    public function addImage(ProductImages $image): self
    {
        if (!$this->image->contains($image)) {
            $this->image[] = $image;
            $image->setProduct($this);
        }

        return $this;
    }

    public function removeImage(ProductImages $image): self
    {
        if ($this->image->removeElement($image)) {
            // set the owning side to null (unless already changed)
            if ($image->getProduct() === $this) {
                $image->setProduct(null);
            }
        }

        return $this;
    }

    public function getDescriptionEn(): ?string
    {
        return $this->descriptionEn;
    }

    public function setDescriptionEn(string $descriptionEn): self
    {
        $this->descriptionEn = $descriptionEn;

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

    public function getInventory(): ?Inventory
    {
        return $this->inventory;
    }

    public function setInventory(?Inventory $inventory): self
    {
        $this->inventory = $inventory;

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

    public function getStatusSent3pl(): ?CommunicationStatesBetweenPlatforms
    {
        return $this->status_sent_3pl;
    }

    public function setStatusSent3pl(?CommunicationStatesBetweenPlatforms $status_sent_3pl): self
    {
        $this->status_sent_3pl = $status_sent_3pl;

        return $this;
    }

    public function getAttemptsSend3pl(): ?int
    {
        return $this->attempts_send_3pl;
    }

    public function setAttemptsSend3pl(int $attempts_send_3pl): self
    {
        $this->attempts_send_3pl = $attempts_send_3pl;

        return $this;
    }

    public function getErrorMessage3pl(): ?string
    {
        return $this->error_message_3pl;
    }

    public function setErrorMessage3pl(?string $error_message_3pl): self
    {
        $this->error_message_3pl = $error_message_3pl;

        return $this;
    }

    public function incrementAttemptsToSendProductTo3pl()
    {
        $this->setAttemptsSend3pl($this->attempts_send_3pl + 1); //you can access your entity values directly
    }

    public function getProductTo3pl($edit = false)
    {

        $product = [
            'inventory_id' => $this->getInventory()->getId3pl(),
            'category_id' => $this->getCategory()->getId3pl(),
            'subcategory_id' => $this->getSubcategory() ? $this->getSubcategory()->getId3pl() : '',
            'brand_id' => $this->getBrand()->getId3pl(),
            'sku' => $this->getSku(),
            'cod' => $this->getCod(),
            'part_number' => $this->getPartNumber(),
            'name' => $this->getName(),
            'description' => $this->getDescriptionEs(),
            'weight' => $this->getWeight(),
            'conditium' => $this->getConditium(),
            'cost' => $this->getCost(),
            'price' => $this->getPrice()
        ];
        if ($edit) {
            $product['id'] = $this->getId3pl();
        }
        return $product;
    }

    public function getFullDataProduct()
    {
        return [
            'id' => $this->getId3pl(),
            'inventory_id' => $this->getInventory()->getId3pl(),
            'category_id' => $this->getCategory()->getId3pl(),
            'subcategory_id' => $this->getSubcategory() ? $this->getSubcategory()->getId3pl() : '',
            'brand_id' => $this->getBrand()->getId3pl(),
            'sku' => $this->getSku(),
            'cod' => $this->getCod(),
            'part_number' => $this->getPartNumber(),
            'name' => $this->getName(),
            'description' => $this->getDescriptionEs(),
            'weight' => $this->getWeight(),
            'conditium' => $this->getConditium(),
            'cost' => $this->getCost(),
            'price' => $this->getPrice(),
            'onhand' => $this->getOnhand(),
            'commited' => $this->getCommited(),
            'incomming' => $this->getIncomming(),
            'available' => $this->getAvailable()
        ];
    }

    /**
     * @return Collection<int, HistoryProductStockUpdated>
     */
    public function getHistoryProductStockUpdateds(): Collection
    {
        return $this->historyProductStockUpdateds;
    }

    public function addHistoryProductStockUpdated(HistoryProductStockUpdated $historyProductStockUpdated): self
    {
        if (!$this->historyProductStockUpdateds->contains($historyProductStockUpdated)) {
            $this->historyProductStockUpdateds[] = $historyProductStockUpdated;
            $historyProductStockUpdated->setProduct($this);
        }

        return $this;
    }

    public function removeHistoryProductStockUpdated(HistoryProductStockUpdated $historyProductStockUpdated): self
    {
        if ($this->historyProductStockUpdateds->removeElement($historyProductStockUpdated)) {
            // set the owning side to null (unless already changed)
            if ($historyProductStockUpdated->getProduct() === $this) {
                $historyProductStockUpdated->setProduct(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, ItemsGuideNumber>
     */
    public function getItemsGuideNumbers(): Collection
    {
        return $this->itemsGuideNumbers;
    }

    public function addItemsGuideNumber(ItemsGuideNumber $itemsGuideNumber): self
    {
        if (!$this->itemsGuideNumbers->contains($itemsGuideNumber)) {
            $this->itemsGuideNumbers[] = $itemsGuideNumber;
            $itemsGuideNumber->setProduct($this);
        }

        return $this;
    }

    public function removeItemsGuideNumber(ItemsGuideNumber $itemsGuideNumber): self
    {
        if ($this->itemsGuideNumbers->removeElement($itemsGuideNumber)) {
            // set the owning side to null (unless already changed)
            if ($itemsGuideNumber->getProduct() === $this) {
                $itemsGuideNumber->setProduct(null);
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
            $ordersProduct->setProduct($this);
        }

        return $this;
    }

    public function removeOrdersProduct(OrdersProducts $ordersProduct): self
    {
        if ($this->ordersProducts->removeElement($ordersProduct)) {
            // set the owning side to null (unless already changed)
            if ($ordersProduct->getProduct() === $this) {
                $ordersProduct->setProduct(null);
            }
        }

        return $this;
    }
}
