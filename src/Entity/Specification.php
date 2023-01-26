<?php

namespace App\Entity;

use Cocur\Slugify\Slugify;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;



/**
 * @ORM\Entity(repositoryClass="App\Repository\SpecificationRepository")
 * @ORM\Table("mia_specification")
 * @UniqueEntity(
 *      fields={"name","specification_type"},
 *      errorPath="name",
 *      message="La especificación indicada ya existe."
 * )
 */
class Specification
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="bigint")
     */
    private $id;


    /**
     * @var string
     *
     * @ORM\Column(name="name",nullable=false, type="string", length=255)
     */
    private $name;

    /**
     * @var string
     *
     * @ORM\Column(name="slug", type="string", length=255)
     */
    private $slug;

    /**
     * @var bool
     *
     * @ORM\Column(name="active", type="boolean")
     */
    private $active;

    /**
     * @ORM\ManyToOne(targetEntity=SpecificationTypes::class, inversedBy="specifications")
     * @ORM\JoinColumn(nullable=false)
     */
    private $specification_type;

    /**
     * @ORM\Column(type="string", length=7, nullable=true)
     */
    private $colorHexadecimal;


    public function __construct()
    {
        $this->active = true;
    }

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
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
    public function setName(string $name): Specification
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

    public function setSlug(string $slug): self
    {
        $this->slug = $slug;

        return $this;
    }

    /**
     * @return bool
     */
    public function isActive(): bool
    {
        return $this->active;
    }

    /**
     * @param bool $active
     * @return $this
     */
    public function setActive(bool $active): Specification
    {
        $this->active = $active;

        return $this;
    }

    public function getActive(): ?bool
    {
        return $this->active;
    }

    public function getSpecificationType(): ?SpecificationTypes
    {
        return $this->specification_type;
    }

    public function setSpecificationType(?SpecificationTypes $specification_type): self
    {
        $this->specification_type = $specification_type;

        return $this;
    }

    public function getColorHexadecimal(): ?string
    {
        return $this->colorHexadecimal;
    }

    public function setColorHexadecimal(?string $colorHexadecimal): self
    {
        $this->colorHexadecimal = $colorHexadecimal;

        return $this;
    }
}
