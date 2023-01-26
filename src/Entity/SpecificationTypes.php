<?php

namespace App\Entity;

use App\Repository\SpecificationTypesRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;


/**
 * @ORM\Entity(repositoryClass=SpecificationTypesRepository::class)
 * @UniqueEntity(fields="name", message="El tipo de especificación indicado ya se encuentra registrado.")
 */
class SpecificationTypes
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=50, unique=true)
     */
    private $name;

    /**
     * @ORM\OneToMany(targetEntity=Specification::class, mappedBy="specification_type")
     */
    private $specifications;

    public function __construct()
    {
        $this->specifications = new ArrayCollection();
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
     * @return Collection<int, Specification>
     */
    public function getSpecifications(): Collection
    {
        return $this->specifications;
    }

    public function addSpecification(Specification $specification): self
    {
        if (!$this->specifications->contains($specification)) {
            $this->specifications[] = $specification;
            $specification->setSpecificationType($this);
        }

        return $this;
    }

    public function removeSpecification(Specification $specification): self
    {
        if ($this->specifications->removeElement($specification)) {
            // set the owning side to null (unless already changed)
            if ($specification->getSpecificationType() === $this) {
                $specification->setSpecificationType(null);
            }
        }

        return $this;
    }
}
