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
     * 
     */
    protected $id;

    /**
     * @var string|null
     *
     * @ORM\Column(name="sku", type="string", length=255, nullable=true)
     */
    protected $sku;


    /**
     * @var string|null
     *
     * @ORM\Column(name="short_name", type="string", nullable=true, length=255)
     */
    protected $shortName;

    /**
     * @var string|null
     *
     * @ORM\Column(name="long_name", type="string", nullable=true, length=255)
     */
    protected $longName;

    /**
     * @var string
     *
     * @ORM\Column(name="slug", type="string", length=255)
     */
    protected $slug;

    /**
     * @var float|null
     *
     * @ORM\Column(name="weight", type="float", nullable=true)
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
     * @ORM\Column(name="short_description", type="text", nullable=true)
     */
    protected $shortDescription;

    /**
     * @var string|null
     *
     * @ORM\Column(name="long_description", type="text", nullable=true)
     */
    protected $longDescription;


    /**
     * @var \DateTime
     *
     * @ORM\Column(name="created_at", type="datetime", nullable=true)
     */
    protected $createdAt;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="created_at_3pl", type="datetime", nullable=true)
     */
    protected $createdAt3PL;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="updated_at_3pl", type="datetime", nullable=true)
     */
    protected $updatedAt3PL;

    public function __construct()
    {
        $this->createdAt = new \DateTime();
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
    public function getShortName(): ?string
    {
        return $this->shortName;
    }

    /**
     * @param string|null $shortName
     * @return $this
     */
    public function setShortName(?string $shortName): self
    {
        $this->shortName = $shortName;

        $slugify = new Slugify();

        $this->slug = $slugify->slugify($shortName);

        return $this;
    }

    /**
     * @return string|null
     */
    public function getLongName(): ?string
    {
        return $this->longName;
    }

    /**
     * @param string|null $longName
     * @return $this
     */
    public function setLongName(?string $longName): self
    {
        $this->name = $longName;
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
    public function getLongDescription(): ?string
    {
        return $this->longDescription;
    }

    /**
     * @param string|null $longDescription
     * @return $this
     */
    public function setLongDescription(?string $longDescription): self
    {
        $this->longDescription = $longDescription;

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
     * @return \DateTime
     */
    public function getCreatedAt3PL(): ?\DateTime
    {
        return $this->createdAt3PL;
    }

    /**
     * @param \DateTime $createdAt3PL
     * @return $this
     */
    public function setCreatedAt3PL(\DateTime $createdAt3PL): self
    {
        $this->createdAt3PL = $createdAt3PL;

        return $this;
    }

    /**
     * @return \DateTime
     */
    public function getUpdatedAt3PL(): ?\DateTime
    {
        return $this->updatedAt3PL;
    }

    /**
     * @param \DateTime $updatedAt3PL
     * @return $this
     */
    public function setUpdatedAt3PL(\DateTime $updatedAt3PL): self
    {
        $this->updatedAt3PL = $updatedAt3PL;

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
            "shortName" => $this->getShortName(),
            "sku" => $this->getSku(),
            "longDescription" => $this->getLongDescription(),
            "shortDescription" => $this->getShortDescription(),
            "customFields" => "",
        ];
    }
}
