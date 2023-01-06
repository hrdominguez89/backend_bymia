<?php

namespace App\Entity;

use Cocur\Slugify\Slugify;
use DateTime;
use App\Constants\Constants;
use App\Repository\CommunicationStatesBetweenPlatformsRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * @ORM\Entity(repositoryClass="App\Repository\BrandRepository")
 * @ORM\Table("mia_brand")
 * 
 * @UniqueEntity(fields="name", message="El nombre indicado ya se encuentra registrado.")
 * @UniqueEntity(fields="nomenclature", message="La nomenclatura indicada ya se encuentra registrada, por favor intente con otra.")
 * 
 */
class Brand
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="bigint")
     * 
     */
    private $id;

    /**
     * @var string|null
     *
     * @ORM\Column(name="id3pl", type="bigint", nullable=true)
     * 
     */
    private $id3pl;

    /**
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=255, unique=true)
     * 
     * 
     */
    private $name;

    /**
     * @var string
     *
     * @ORM\Column(name="slug", type="string", length=255, nullable=false)
     * 
     * 
     * 
     */
    private $slug;

    /**
     * @var string|null
     *
     * @ORM\Column(name="image", type="text", nullable=true)
     * 
     * 
     * 
     */
    private $image;

    /**
     * @ORM\Column(name="description_es",type="text", nullable=true)
     * 
     * 
     */
    private $descriptionEs;

    /**
     * @ORM\OneToMany(targetEntity=Product::class, mappedBy="brand")
     * 
     */
    private $products;

    /**
     * @ORM\Column(type="boolean", nullable=false, options={"default":false})
     */
    private $visible = false;

    /**
     * @ORM\Column(type="datetime", nullable=false, options={"default":"CURRENT_TIMESTAMP"})
     * 
     */
    private $created_at;

    /**
     * @ORM\Column(type="string", length=3, nullable=false, unique=true)
     */
    private $nomenclature;

    /**
     * @ORM\Column(name="description_en",type="text", nullable=true)
     */
    private $descriptionEn;

    /**
     * @ORM\Column(type="boolean",nullable=false, options={"default":false})
     */
    private $principal = false;

    /**
     * @ORM\ManyToOne(targetEntity=CommunicationStatesBetweenPlatforms::class, inversedBy="brands")
     * @ORM\JoinColumn(nullable=false, options={"default":1})
     * 
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

    public function __construct()
    {
        $this->products = new ArrayCollection();
        $this->created_at = new \DateTime();
        $this->visible = false;
        $this->principal = false;
        $this->attempts_send_3pl = 0;
    }

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
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
    public function setId3pl(?int $id3pl): Brand
    {
        $this->id3pl = $id3pl;

        return $this;
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
    public function setName(string $name): Brand
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
    public function setImage(?string $image): Brand
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
        ];
    }

    public function setSlug(string $slug): self
    {
        $this->slug = $slug;

        return $this;
    }

    public function getDescriptionEs(): ?string
    {
        return $this->descriptionEs;
    }

    public function setDescriptionEs(?string $descriptionEs): self
    {
        $this->descriptionEs = $descriptionEs;

        return $this;
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
            $product->setBrand($this);
        }

        return $this;
    }

    public function removeProduct(Product $product): self
    {
        if ($this->products->removeElement($product)) {
            // set the owning side to null (unless already changed)
            if ($product->getBrand() === $this) {
                $product->setBrand(null);
            }
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

    public function getNomenclature(): ?string
    {
        return strtoupper($this->nomenclature);
    }

    public function setNomenclature(string $nomenclature): self
    {
        $this->nomenclature = strtoupper($nomenclature);

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

    public function getPrincipal(): ?bool
    {
        return $this->principal;
    }

    public function setPrincipal(bool $principal): self
    {
        $this->principal = $principal;

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
    public function incrementAttemptsToSendBrandTo3pl()
    {
        $this->setAttemptsSend3pl($this->attempts_send_3pl + 1); //you can access your entity values directly
    }
}
