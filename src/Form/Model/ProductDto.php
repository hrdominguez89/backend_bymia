<?php

namespace App\Form\Model;

use App\Entity\Brand;
use App\Entity\Product;
use DateTimeInterface;
use Ramsey\Uuid\UuidInterface;

class ProductDto
{
    public ?int $id = null;
    public ?string $sku;
    public ?string $productId;
    public ?string $productIdParent;
    public ?string $base64Image = "";
    public ?string $name;
    public ?string $description;
    public ?float $stock;
    public ?string $url;
    public ?int $brand;
    public ?float $weight;
    public ?float $price;
    public ?float $offerPrice;
    public ?DateTimeInterface $offerStartDate;
    public ?DateTimeInterface $offerEndDate;
    public ?string $htmlDescription;
    public ?string $shortDescription;
    public ?string $color;
    public ?float $length;
    public ?string $dimentions;
    public ?bool $destacado;
    public ?float $sales;

    public static function createEmpty(): self
    {
        return new self();
    }

    public static function createFromProduct(Product $product): self
    {
        $dto = new self();
        $dto->id = $product->getId();
        $dto->sku = $product->getSku();
        $dto->productId = $product->getId();
        $dto->productIdParent = $product->getParentId();
        $dto->base64Image = "";
        $dto->name = $product->getName();
        $dto->description = $product->getDescription();
        $dto->stock = $product->getStock();
        $dto->url = $product->getUrl();
        $dto->brand = ($product->getBrandId()) ? $product->getBrandId()->getId() : null;
        $dto->weight = $product->getWeight();
        $dto->price = $product->getPrice();
        $dto->offerPrice = $product->getOfferPrice();
        $dto->offerStartDate = $product->getOfferStartDate();
        $dto->offerEndDate = $product->getOfferEndDate();
        $dto->htmlDescription = $product->getHtmlDescription();
        $dto->shortDescription = $product->getShortDescription();
        $dto->color = $product->getColor();
        $dto->length = $product->getLength();
        $dto->dimentions = $product->getDimensions();
        $dto->destacado = $product->isFeatured();
        $dto->sales = $product->getSales();
        return $dto;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getSku(): ?string
    {
        return $this->sku;
    }

    public function getProductId(): ?string
    {
        return $this->productIdParent;
    }

    public function getProductIdParent(): ?string
    {
        return $this->productIdParent;
    }

    public function getBase64Image(): ?string
    {
        return $this->base64Image;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function getStock(): ?float
    {
        return $this->stock;
    }

    public function getUrl(): ?string
    {
        return $this->url;
    }

    public function getBrand(): ?int
    {
        return $this->brand;
    }

    public function getWeight(): ?float
    {
        return $this->weight;
    }

    public function getPrice(): ?float
    {
        return $this->price;
    }

    public function getOfferPrice(): ?float
    {
        return $this->offerPrice;
    }

    public function getOfferStartDate(): ?\DateTimeInterface
    {
        return $this->offerStartDate;
    }

    public function getOfferEndDate(): ?\DateTimeInterface
    {
        return $this->offerEndDate;
    }

    public function getHtmlDescription(): ?string
    {
        return $this->htmlDescription;
    }

    public function getShortDescription(): ?string
    {
        return $this->shortDescription;
    }

    public function getColor(): ?string
    {
        return $this->color;
    }

    public function getLength(): ?float
    {
        return $this->length;
    }

    public function getDimentions(): ?string
    {
        return $this->dimentions;
    }

    public function getDestacado(): ?bool
    {
        return $this->destacado;
    }

    public function getSales(): ?float
    {
        return $this->sales;
    }
}
