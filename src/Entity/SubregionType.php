<?php

namespace App\Entity;

use App\Repository\SubregionTypeRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=SubregionTypeRepository::class)
 */
class SubregionType
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity=RegionType::class, inversedBy="subregionTypes")
     * @ORM\JoinColumn(nullable=false)
     */
    private $region_type;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $name;

    /**
     * @ORM\OneToMany(targetEntity=Countries::class, mappedBy="subregion_type")
     */
    private $countries;

    public function __construct()
    {
        $this->countries = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getRegionType(): ?RegionType
    {
        return $this->region_type;
    }

    public function setRegionType(?RegionType $region_type): self
    {
        $this->region_type = $region_type;

        return $this;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    /**
     * @return Collection|Countries[]
     */
    public function getCountries(): Collection
    {
        return $this->countries;
    }

    public function addCountry(Countries $country): self
    {
        if (!$this->countries->contains($country)) {
            $this->countries[] = $country;
            $country->setSubregionType($this);
        }

        return $this;
    }

    public function removeCountry(Countries $country): self
    {
        if ($this->countries->removeElement($country)) {
            // set the owning side to null (unless already changed)
            if ($country->getSubregionType() === $this) {
                $country->setSubregionType(null);
            }
        }

        return $this;
    }
}
