<?php

namespace App\Entity;

use App\Repository\GuideNumbersRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=GuideNumbersRepository::class)
 */
class GuideNumbers
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $number;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $courier_name;

    /**
     * @ORM\OneToMany(targetEntity=ItemsGuideNumber::class, mappedBy="guide_number")
     */
    private $itemsGuideNumbers;

    /**
     * @ORM\ManyToOne(targetEntity=Orders::class, inversedBy="guideNumbers")
     * @ORM\JoinColumn(nullable=false)
     */
    private $number_order;

    /**
     * @ORM\Column(type="integer")
     */
    private $courier_id;

    /**
     * @ORM\Column(type="float", nullable=true)
     */
    private $lb;

    /**
     * @ORM\Column(type="float", nullable=true)
     */
    private $height;

    /**
     * @ORM\Column(type="float", nullable=true)
     */
    private $width;

    /**
     * @ORM\Column(type="float", nullable=true)
     */
    private $depth;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $service_id;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $service_name;

    /**
     * @ORM\Column(type="datetime", nullable=false, options={"default":"CURRENT_TIMESTAMP"})
     */
    private $created_at;

    public function __construct()
    {
        $this->itemsGuideNumbers = new ArrayCollection();
        $this->created_at = new \DateTime();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNumber(): ?string
    {
        return $this->number;
    }

    public function setNumber(string $number): self
    {
        $this->number = $number;

        return $this;
    }

    public function getCourierName(): ?string
    {
        return $this->courier_name;
    }

    public function setCourierName(string $courier_name): self
    {
        $this->courier_name = $courier_name;

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
            $itemsGuideNumber->setGuideNumber($this);
        }

        return $this;
    }

    public function removeItemsGuideNumber(ItemsGuideNumber $itemsGuideNumber): self
    {
        if ($this->itemsGuideNumbers->removeElement($itemsGuideNumber)) {
            // set the owning side to null (unless already changed)
            if ($itemsGuideNumber->getGuideNumber() === $this) {
                $itemsGuideNumber->setGuideNumber(null);
            }
        }

        return $this;
    }

    public function getNumberOrder(): ?Orders
    {
        return $this->number_order;
    }

    public function setNumberOrder(?Orders $number_order): self
    {
        $this->number_order = $number_order;

        return $this;
    }

    public function getCourierId(): ?int
    {
        return $this->courier_id;
    }

    public function setCourierId(int $courier_id): self
    {
        $this->courier_id = $courier_id;

        return $this;
    }

    public function getLb(): ?float
    {
        return $this->lb;
    }

    public function setLb(?float $lb): self
    {
        $this->lb = $lb;

        return $this;
    }

    public function getHeight(): ?float
    {
        return $this->height;
    }

    public function setHeight(?float $height): self
    {
        $this->height = $height;

        return $this;
    }

    public function getWidth(): ?float
    {
        return $this->width;
    }

    public function setWidth(?float $width): self
    {
        $this->width = $width;

        return $this;
    }

    public function getDepth(): ?float
    {
        return $this->depth;
    }

    public function setDepth(?float $depth): self
    {
        $this->depth = $depth;

        return $this;
    }

    public function getServiceId(): ?int
    {
        return $this->service_id;
    }

    public function setServiceId(?int $service_id): self
    {
        $this->service_id = $service_id;

        return $this;
    }

    public function getServiceName(): ?string
    {
        return $this->service_name;
    }

    public function setServiceName(?string $service_name): self
    {
        $this->service_name = $service_name;

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
