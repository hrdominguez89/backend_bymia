<?php

namespace App\Entity;

use App\Repository\RegionTypeRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=RegionTypeRepository::class)
 */
class RegionType
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
    private $name;

    /**
     * @ORM\OneToMany(targetEntity=SubregionType::class, mappedBy="region_type")
     */
    private $subregionTypes;

    /**
     * @ORM\OneToMany(targetEntity=Countries::class, mappedBy="region_type")
     */
    private $countries;

    public function __construct()
    {
        $this->subregionTypes = new ArrayCollection();
        $this->countries = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
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
     * @return Collection|SubregionType[]
     */
    public function getSubregionTypes(): Collection
    {
        return $this->subregionTypes;
    }

    public function addSubregionType(SubregionType $subregionType): self
    {
        if (!$this->subregionTypes->contains($subregionType)) {
            $this->subregionTypes[] = $subregionType;
            $subregionType->setRegionType($this);
        }

        return $this;
    }

    public function removeSubregionType(SubregionType $subregionType): self
    {
        if ($this->subregionTypes->removeElement($subregionType)) {
            // set the owning side to null (unless already changed)
            if ($subregionType->getRegionType() === $this) {
                $subregionType->setRegionType(null);
            }
        }

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
            $country->setRegionType($this);
        }

        return $this;
    }

    public function removeCountry(Countries $country): self
    {
        if ($this->countries->removeElement($country)) {
            // set the owning side to null (unless already changed)
            if ($country->getRegionType() === $this) {
                $country->setRegionType(null);
            }
        }

        return $this;
    }
}
