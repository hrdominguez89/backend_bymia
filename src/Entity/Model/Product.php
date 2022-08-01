<?php

namespace App\Entity\Model;

use Cocur\Slugify\Slugify;
use Doctrine\ORM\Mapping as ORM;

abstract class Product
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="bigint")
     */
    protected $id;

    /**
     * @var string|null
     *
     * @ORM\Column(name="parent_id", type="string", length=255, nullable=true)
     */
    protected $parentId;

    /**
     * @var string|null
     *
     * @ORM\Column(name="sku", type="string", length=255, nullable=true)
     */
    protected $sku;

    /**
     * @var string|null
     *
     * @ORM\Column(name="badges", type="string", length=10, nullable=true)
     */
    protected $badges;

    /**
     * @var string|null
     *
     * @ORM\Column(name="availability", type="string", length=20, nullable=true)
     */
    protected $availability;

    /**
     * @var string|null
     *
     * @ORM\Column(name="name", type="string", length=100)
     */
    protected $name;

    /**
     * @var string
     *
     * @ORM\Column(name="slug", type="string", length=100)
     */
    protected $slug;

    /**
     * @var string|null
     *
     * @ORM\Column(name="image", type="text", nullable=true)
     */
    protected $image;

    /**
     * @var string|null
     *
     * @ORM\Column(name="new_image", type="text", nullable=true)
     */
    protected $newImage;

    /**
     * @var string|null
     *
     * @ORM\Column(name="description", type="text", nullable=true)
     */
    protected $description;

    /**
     * @var float|null
     *
     * @ORM\Column(name="stock", type="float", nullable=true)
     */
    protected $stock;

    /**
     * @var string|null
     *
     * @ORM\Column(name="url", type="string", length=255, nullable=true)
     */
    protected $url;

    /**
     * @var float|null
     *
     * @ORM\Column(name="weight", type="float", nullable=true)
     */
    protected $weight;

    /**
     * @var float|null
     *
     * @ORM\Column(name="price", type="float", nullable=true)
     */
    protected $price;

    /**
     * @var float|null
     *
     * @ORM\Column(name="offer_price", type="float", nullable=true)
     */
    protected $offerPrice;

    /**
     * @var \DateTime|null
     *
     * @ORM\Column(name="offer_start_date", type="datetime", nullable=true)
     */
    protected $offerStartDate;

    /**
     * @var \DateTime|null
     *
     * @ORM\Column(name="offer_end_date", type="datetime", nullable=true)
     */
    protected $offerEndDate;

    /**
     * @var string|null
     *
     * @ORM\Column(name="html_description", type="text", nullable=true)
     */
    protected $htmlDescription;

    /**
     * @var string|null
     *
     * @ORM\Column(name="short_description", type="text", nullable=true)
     */
    protected $shortDescription;

    /**
     * @var string|null
     *
     * @ORM\Column(name="color", type="string", length=255, nullable=true)
     */
    protected $color;

    /**
     * @var float|null
     *
     * @ORM\Column(name="length", type="float", nullable=true)
     */
    protected $length;

    /**
     * @var string|null
     *
     * @ORM\Column(name="dimensions", type="string", length=255, nullable=true)
     */
    protected $dimensions;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="date", type="datetime")
     */
    protected $date;

    /**
     * @var bool
     *
     * @ORM\Column(name="featured", type="boolean")
     */
    protected $featured;

    /**
     * @var float
     *
     * @ORM\Column(name="sales", type="float")
     */
    protected $sales;

    /**
     * @var float
     *
     * @ORM\Column(name="reviews", type="float")
     */
    protected $reviews;
    /**
     * @var float
     *
     * @ORM\Column(name="rating", type="float")
     */
    protected $rating;

    public function __construct()
    {
        $this->date = new \DateTime();
        $this->featured = false;
        $this->rating = 0;
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
    public function getParentId(): ?string
    {
        return $this->parentId;
    }

    /**
     * @param string|null $parentId
     * @return $this
     */
    public function setParentId(?string $parentId): self
    {
        $this->parentId = $parentId;

        return $this;
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
    public function getAvailability(): ?string
    {
        return $this->availability;
    }

    /**
     * @param string|null $availability
     * @return $this
     */
    public function setAvailability(?string $availability): self
    {
        $this->availability = $availability;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getBadges(): ?string
    {
        return $this->badges;
    }

    /**
     * @param string|null $badges
     * @return $this
     */
    public function setBadges(?string $badges): self
    {
        $this->badges = $badges;

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
     * @return string|null
     */
    public function getNewImage(): ?string
    {
        return $this->newImage;
    }

    /**
     * @param string|null $image
     * @return $this
     */
    public function setNewImage(?string $image): self
    {
        $this->newImage = $image;

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
     * @return float|null
     */
    public function getStock(): ?float
    {
        return $this->stock;
    }

    /**
     * @param float|null $stock
     * @return $this
     */
    public function setStock(?float $stock): self
    {
        $this->stock = $stock;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getUrl(): ?string
    {
        return $this->url;
    }

    /**
     * @param string|null $url
     * @return $this
     */
    public function setUrl(?string $url): self
    {
        $this->url = $url;

        return $this;
    }

    /**
     * @return float|null
     */
    public function getWeight(): ?float
    {
        return $this->weight;
    }

    /**
     * @param float|null $weight
     * @return $this
     */
    public function setWeight(?float $weight): self
    {
        $this->weight = $weight;

        return $this;
    }

    /**
     * @return float|null
     */
    public function getPrice(): ?float
    {
        return $this->price;
    }

    /**
     * @param float|null $price
     * @return $this
     */
    public function setPrice(?float $price): self
    {
        $this->price = $price;

        return $this;
    }

    /**
     * @return float|null
     */
    public function getOfferPrice(): ?float
    {
        return $this->offerPrice;
    }

    /**
     * @param float|null $offerPrice
     * @return $this
     */
    public function setOfferPrice(?float $offerPrice): self
    {
        $this->offerPrice = $offerPrice;

        return $this;
    }

    /**
     * @return \DateTime|null
     */
    public function getOfferStartDate(): ?\DateTime
    {
        return $this->offerStartDate;
    }

    /**
     * @param \DateTime|null $offerStartDate
     * @return $this
     */
    public function setOfferStartDate(?\DateTime $offerStartDate): self
    {
        $this->offerStartDate = $offerStartDate;

        return $this;
    }

    /**
     * @return \DateTime|null
     */
    public function getOfferEndDate(): ?\DateTime
    {
        return $this->offerEndDate;
    }

    /**
     * @param \DateTime|null $offerEndDate
     * @return $this
     */
    public function setOfferEndDate(?\DateTime $offerEndDate): self
    {
        $this->offerEndDate = $offerEndDate;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getHtmlDescription(): ?string
    {
        return $this->htmlDescription;
    }

    /**
     * @param string|null $htmlDescription
     * @return $this
     */
    public function setHtmlDescription(?string $htmlDescription): self
    {
        $this->htmlDescription = $htmlDescription;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getShortDescription(): ?string
    {
        return $this->shortDescription;
    }

    /**
     * @param string|null $shortDescription
     * @return $this
     */
    public function setShortDescription(?string $shortDescription): self
    {
        $this->shortDescription = $shortDescription;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getColor(): ?string
    {
        return $this->color;
    }

    /**
     * @param string|null $color
     * @return $this
     */
    public function setColor(?string $color): self
    {
        $this->color = $color;

        return $this;
    }

    /**
     * @return float|null
     */
    public function getLength(): ?float
    {
        return $this->length;
    }

    /**
     * @param float|null $length
     * @return $this
     */
    public function setLength(?float $length): self
    {
        $this->length = $length;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getDimensions(): ?string
    {
        return $this->dimensions;
    }

    /**
     * @param string|null $dimensions
     * @return $this
     */
    public function setDimensions(?string $dimensions): self
    {
        $this->dimensions = $dimensions;

        return $this;
    }

    /**
     * @return \DateTime
     */
    public function getDate(): \DateTime
    {
        return $this->date;
    }

    /**
     * @param \DateTime $date
     * @return $this
     */
    public function setDate(\DateTime $date): self
    {
        $this->date = $date;

        return $this;
    }

    /**
     * @return bool
     */
    public function isFeatured(): bool
    {
        return $this->featured;
    }

    /**
     * @param bool $featured
     * @return $this
     */
    public function setFeatured(bool $featured): self
    {
        $this->featured = $featured;

        return $this;
    }

    /**
     * @return float
     */
    public function getSales(): float
    {
        return $this->sales;
    }

    /**
     * @param float $sales
     * @return $this
     */
    public function setSales(float $sales): self
    {
        $this->sales = $sales;

        return $this;
    }

    /**
     * @return float
     */
    public function getReviews(): float
    {
        return $this->reviews;
    }

    /**
     * @param float $reviews
     * @return $this
     */
    public function setReviews(float $reviews): self
    {
        $this->reviews = $reviews;

        return $this;
    }

    /**
     * @return float
     */
    public function getRating(): float
    {
        return $this->rating;
    }

    /**
     * @param float $rating
     * @return $this
     */
    public function setRating(float $rating): self
    {
        $this->rating = $rating;

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
            "description" => $this->getDescription(),
            "shortDescription" => $this->getShortDescription(),
            "price" => $this->calcPrice(),
            "compareAtPrice" => $this->getOfferPrice() ? $this->getPrice() : null,
            "badges" => [$this->getBadges()],
            "rating" => $this->getRating(),
            "reviews" => $this->getReviews(),
            "stock" => $this->getStock(),
            "availability" => $this->getAvailability(),
            "customFields" => "",
        ];
    }

    /**
     * @return float|null
     */
    public function calcPrice(): ?float
    {
        return $this->getOfferPrice() ?? $this->getPrice();
    }

}
