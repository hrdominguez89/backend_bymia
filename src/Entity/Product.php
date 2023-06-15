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
     * @var string
     *
     * @ORM\Column(name="sku", type="string", length=255, nullable=false, unique=true)
     * @Assert\Length(min=28, max=36)
     * @Assert\Regex(
     *     pattern="/^[A-Za-z0-9]{3}-[A-Za-z0-9]{3}-[A-Za-z0-9]{12}-[A-Za-z0-9]{3}-[A-Za-z0-9]{3}(?:-[A-Za-z0-9]{3}(?:-[A-Za-z0-9]{3})?)?$/",
     *     message="El sku no cumple con el formato requerido"
     * )
     */
    protected $sku;

    /**
     * @var string
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
     * @var string
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
     * 
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
     * @ORM\Column(type="boolean", nullable=true, options={"default":False})
     */
    private $visible;

    /**
     * @ORM\OneToMany(targetEntity=ProductImages::class, mappedBy="product", orphanRemoval=true)
     */
    private $image;

    /**
     * @ORM\Column(name="description_en", nullable=true ,type="text")
     * 
     */
    private $descriptionEn;

    /**
     * @ORM\ManyToOne(targetEntity=Inventory::class, inversedBy="products")
     * @ORM\JoinColumn(nullable=false)
     */
    private $inventory;

    /**
     * @ORM\ManyToOne(targetEntity=Subcategory::class, inversedBy="products")
     * 
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

    /**
     * @ORM\Column(type="text", nullable=true)
     * 
     */
    private $long_description_es;

    /**
     * @ORM\Column(type="text", nullable=true)
     * 
     */
    private $long_description_en;

    /**
     * @ORM\Column(type="float")
     */
    private $weight;

    /**
     * @ORM\ManyToOne(targetEntity=Specification::class, inversedBy="products_model")
     * @ORM\JoinColumn(nullable=false)
     */
    private $model;

    /**
     * @ORM\ManyToOne(targetEntity=Specification::class, inversedBy="products_color")
     * @ORM\JoinColumn(nullable=false)
     */
    private $color;

    /**
     * @ORM\ManyToOne(targetEntity=Specification::class, inversedBy="products_screen_resolution")
     */
    private $screen_resolution;

    /**
     * @ORM\ManyToOne(targetEntity=Specification::class, inversedBy="products_screen_size")
     */
    private $screen_size;

    /**
     * @ORM\ManyToOne(targetEntity=Specification::class, inversedBy="products_cpu")
     */
    private $cpu;

    /**
     * @ORM\ManyToOne(targetEntity=Specification::class, inversedBy="products_gpu")
     */
    private $gpu;

    /**
     * @ORM\ManyToOne(targetEntity=Specification::class, inversedBy="products_memory")
     */
    private $memory;

    /**
     * @ORM\ManyToOne(targetEntity=Specification::class, inversedBy="products_storage")
     */
    private $storage;

    /**
     * @ORM\ManyToOne(targetEntity=Specification::class, inversedBy="products_op_sys")
     */
    private $op_sys;

    /**
     * @ORM\ManyToOne(targetEntity=Specification::class, inversedBy="products_conditium")
     * @ORM\JoinColumn(nullable=false)
     */
    private $conditium;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $tag_expires;

    /**
     * @ORM\ManyToOne(targetEntity=Tag::class, inversedBy="products")
     */
    private $tag;

    /**
     * @ORM\Column(type="date", nullable=true)
     */
    private $tag_expiration_date;

    /**
     * @ORM\OneToMany(targetEntity=HistoricalPriceCost::class, mappedBy="product", cascade={"persist"})
     */
    private $historicalPriceCosts;

    private $price;

    private $cost;

    /**
     * @ORM\Column(type="smallint", options={"default":5})
     */
    private $rating;

    /**
     * @ORM\Column(type="bigint")
     */
    private $reviews;

    /**
     * @ORM\OneToMany(targetEntity=ProductDiscount::class, mappedBy="product")
     */
    private $productDiscounts;

    /**
     * @ORM\OneToMany(targetEntity=FavoriteProduct::class, mappedBy="product")
     */
    private $favoriteProducts;

    /**
     * @ORM\OneToMany(targetEntity=ShoppingCart::class, mappedBy="product")
     */
    private $shoppingCarts;

    public function __construct()
    {
        $this->created_at = new \DateTime();
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
        $this->historicalPriceCosts = new ArrayCollection();
        $this->rating = 5;
        $this->reviews = rand(0, 100);
        $this->productDiscounts = new ArrayCollection();
        $this->favoriteProducts = new ArrayCollection();
        $this->shoppingCarts = new ArrayCollection();
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


    public function getCod(): ?string
    {
        return $this->cod;
    }

    public function setCod(?string $cod): self
    {
        $this->cod = $cod;

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

    public function getPrincipalImage()
    {
        $principalImage = $this->getImage()->filter(function (ProductImages $productImages) {
            return $productImages->getPrincipal();
        });
        return $principalImage->first() ? $principalImage->first()->getImage() : null;
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

    /**
     * @param string|null $descriptionEn
     * @return $this
     */
    public function setDescriptionEn(?string $descriptionEn): self
    {
        $this->descriptionEn = $descriptionEn;

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
            'conditium' => $this->getConditium()->getName(),
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
            'conditium' => $this->getConditium()->getName(),
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

    public function getLongDescriptionEs(): ?string
    {
        return $this->long_description_es;
    }

    public function setLongDescriptionEs(?string $long_description_es): self
    {
        $this->long_description_es = $long_description_es;

        return $this;
    }

    public function getLongDescriptionEn(): ?string
    {
        return $this->long_description_en;
    }

    public function setLongDescriptionEn(?string $long_description_en): self
    {
        $this->long_description_en = $long_description_en;

        return $this;
    }

    public function getWeight(): ?float
    {
        return $this->weight;
    }

    public function setWeight(float $weight): self
    {
        $this->weight = $weight;

        return $this;
    }

    public function getModel(): ?Specification
    {
        return $this->model;
    }

    public function setModel(?Specification $model): self
    {
        $this->model = $model;

        return $this;
    }

    public function getColor(): ?Specification
    {
        return $this->color;
    }

    public function setColor(?Specification $color): self
    {
        $this->color = $color;

        return $this;
    }

    public function getScreenResolution(): ?Specification
    {
        return $this->screen_resolution;
    }

    public function setScreenResolution(?Specification $screen_resolution): self
    {
        $this->screen_resolution = $screen_resolution;

        return $this;
    }

    public function getScreenSize(): ?Specification
    {
        return $this->screen_size;
    }

    public function setScreenSize(?Specification $screen_size): self
    {
        $this->screen_size = $screen_size;

        return $this;
    }

    public function getCpu(): ?Specification
    {
        return $this->cpu;
    }

    public function setCpu(?Specification $cpu): self
    {
        $this->cpu = $cpu;

        return $this;
    }

    public function getGpu(): ?Specification
    {
        return $this->gpu;
    }

    public function setGpu(?Specification $gpu): self
    {
        $this->gpu = $gpu;

        return $this;
    }

    public function getMemory(): ?Specification
    {
        return $this->memory;
    }

    public function setMemory(?Specification $memory): self
    {
        $this->memory = $memory;

        return $this;
    }

    public function getStorage(): ?Specification
    {
        return $this->storage;
    }

    public function setStorage(?Specification $storage): self
    {
        $this->storage = $storage;

        return $this;
    }

    public function getOpSys(): ?Specification
    {
        return $this->op_sys;
    }

    public function setOpSys(?Specification $op_sys): self
    {
        $this->op_sys = $op_sys;

        return $this;
    }

    public function getConditium(): ?Specification
    {
        return $this->conditium;
    }

    public function setConditium(?Specification $conditium): self
    {
        $this->conditium = $conditium;

        return $this;
    }

    public function getTagExpires(): ?bool
    {
        return $this->tag_expires;
    }

    public function setTagExpires(?bool $tag_expires): self
    {
        $this->tag_expires = $tag_expires;

        return $this;
    }

    public function getTag(): ?Tag
    {
        return $this->tag;
    }

    public function setTag(?Tag $tag): self
    {
        $this->tag = $tag;

        return $this;
    }

    public function getTagExpirationDate(): ?\DateTimeInterface
    {
        return $this->tag_expiration_date;
    }

    public function setTagExpirationDate(?\DateTimeInterface $tag_expiration_date): self
    {
        $this->tag_expiration_date = $tag_expiration_date;

        return $this;
    }

    /**
     * @return Collection<int, HistoricalPriceCost>
     */
    public function getHistoricalPriceCosts(): Collection
    {
        return $this->historicalPriceCosts;
    }

    public function addHistoricalPriceCost(HistoricalPriceCost $historicalPriceCost): self
    {
        if (!$this->historicalPriceCosts->contains($historicalPriceCost)) {
            $this->historicalPriceCosts[] = $historicalPriceCost;
            $historicalPriceCost->setProduct($this);
        }

        return $this;
    }

    public function removeHistoricalPriceCost(HistoricalPriceCost $historicalPriceCost): self
    {
        if ($this->historicalPriceCosts->removeElement($historicalPriceCost)) {
            // set the owning side to null (unless already changed)
            if ($historicalPriceCost->getProduct() === $this) {
                $historicalPriceCost->setProduct(null);
            }
        }

        return $this;
    }

    public function getPrice()
    {
        $price = $this->getHistoricalPriceCosts()->filter(function (HistoricalPriceCost $historicalPriceCost) {
            return $historicalPriceCost->getPrice();
        });
        return $price->last() ? $price->last()->getPrice() : null;
    }

    public function setPrice(float $price): self
    {
        $this->price = $price;
        return $this;
    }

    public function getCost()
    {
        $cost = $this->getHistoricalPriceCosts()->filter(function (HistoricalPriceCost $historicalPriceCost) {
            return $historicalPriceCost->getCost();
        });
        return $cost->last() ? $cost->last()->getCost() : null;
    }

    public function setCost(float $cost): self
    {
        $this->cost = $cost;
        return $this;
    }

    public function getRating(): ?int
    {
        return $this->rating;
    }

    public function setRating(int $rating): self
    {
        $this->rating = $rating;

        return $this;
    }

    public function getReviews(): ?string
    {
        return $this->reviews;
    }

    public function setReviews(string $reviews): self
    {
        $this->reviews = $reviews;

        return $this;
    }

    /**
     * @return Collection<int, ProductDiscount>
     */
    public function getProductDiscounts(): Collection
    {
        return $this->productDiscounts;
    }

    public function addProductDiscount(ProductDiscount $productDiscount): self
    {
        if (!$this->productDiscounts->contains($productDiscount)) {
            $this->productDiscounts[] = $productDiscount;
            $productDiscount->setProduct($this);
        }

        return $this;
    }

    public function removeProductDiscount(ProductDiscount $productDiscount): self
    {
        if ($this->productDiscounts->removeElement($productDiscount)) {
            // set the owning side to null (unless already changed)
            if ($productDiscount->getProduct() === $this) {
                $productDiscount->setProduct(null);
            }
        }

        return $this;
    }

    public function getDiscountActive()
    {
        $discountActive = $this->getProductDiscounts()->filter(function (ProductDiscount $productDiscount) {
            return $productDiscount->getActive();
        });
        return $discountActive->first() ? $discountActive->first()->getPercentageDiscount() : null;
    }

    public function getBasicDataProduct()
    {
        return [
            "id" => (int)$this->getId(),
            "name" => $this->getName(),
            "image" => $this->getPrincipalImage(),
            "rating" => (int)$this->getRating(),
            "reviews" => (int)$this->getReviews(),
            "old_price" => $this->getDiscountActive() ? $this->getPrice() : null,
            "price" => $this->getDiscountActive() ?  ($this->getPrice() - (($this->getPrice() / 100) * $this->getDiscountActive())) : $this->getPrice(),
            "available" => $this->getAvailable(),
        ];
    }

    /**
     * @return Collection<int, FavoriteProduct>
     */
    public function getFavoriteProducts(): Collection
    {
        return $this->favoriteProducts;
    }

    public function addFavoriteProduct(FavoriteProduct $favoriteProduct): self
    {
        if (!$this->favoriteProducts->contains($favoriteProduct)) {
            $this->favoriteProducts[] = $favoriteProduct;
            $favoriteProduct->setProduct($this);
        }

        return $this;
    }

    public function removeFavoriteProduct(FavoriteProduct $favoriteProduct): self
    {
        if ($this->favoriteProducts->removeElement($favoriteProduct)) {
            // set the owning side to null (unless already changed)
            if ($favoriteProduct->getProduct() === $this) {
                $favoriteProduct->setProduct(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, ShoppingCart>
     */
    public function getShoppingCarts(): Collection
    {
        return $this->shoppingCarts;
    }

    public function addShoppingCart(ShoppingCart $shoppingCart): self
    {
        if (!$this->shoppingCarts->contains($shoppingCart)) {
            $this->shoppingCarts[] = $shoppingCart;
            $shoppingCart->setProduct($this);
        }

        return $this;
    }

    public function removeShoppingCart(ShoppingCart $shoppingCart): self
    {
        if ($this->shoppingCarts->removeElement($shoppingCart)) {
            // set the owning side to null (unless already changed)
            if ($shoppingCart->getProduct() === $this) {
                $shoppingCart->setProduct(null);
            }
        }

        return $this;
    }
}
