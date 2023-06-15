<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\ProductImagesRepository")
 * @ORM\Table("mia_product_image")
 */
class ProductImages
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="bigint")
     */
    private $id;

    /**
     * @var string|null
     *
     * @ORM\Column(name="image", type="text", nullable=true)
     */
    private $image;

    /**
     * @ORM\ManyToOne(targetEntity=Product::class, inversedBy="image")
     * @ORM\JoinColumn(nullable=false)
     */
    private $product;

    /**
     * @ORM\Column(type="boolean", nullable=true, options={"default":false})
     */
    private $principal;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $img_thumbnail;

    public function __construct()
    {
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
    public function setImage(?string $image): ProductImages
    {
        $this->image = $image;

        return $this;
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

    public function getPrincipal(): ?bool
    {
        return $this->principal;
    }

    public function setPrincipal(?bool $principal): self
    {
        $this->principal = $principal;

        return $this;
    }

    public function getImgThumbnail(): ?string
    {
        return $this->img_thumbnail;
    }

    public function setImgThumbnail(?string $img_thumbnail): self
    {
        $this->img_thumbnail = $img_thumbnail;

        return $this;
    }
}
