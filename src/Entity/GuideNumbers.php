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

    public function __construct()
    {
        $this->itemsGuideNumbers = new ArrayCollection();
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
}
