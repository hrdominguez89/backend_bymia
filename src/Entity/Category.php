<?php

namespace App\Entity;

use App\Entity\Model\Category as BaseCategory;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\CategoryRepository")
 * @ORM\Table("mia_category")
 */
class Category extends BaseCategory
{
    /**
     * @ORM\ManyToMany(targetEntity=Subcategory::class, mappedBy="category")
     */
    private $subcategories;

    public function __construct()
    {
        $this->subcategories = new ArrayCollection();
    }

    /**
     * @return Collection|Subcategory[]
     */
    public function getSubcategories(): Collection
    {
        return $this->subcategories;
    }

    public function addSubcategory(Subcategory $subcategory): self
    {
        if (!$this->subcategories->contains($subcategory)) {
            $this->subcategories[] = $subcategory;
            $subcategory->addCategory($this);
        }

        return $this;
    }

    public function removeSubcategory(Subcategory $subcategory): self
    {
        if ($this->subcategories->removeElement($subcategory)) {
            $subcategory->removeCategory($this);
        }

        return $this;
    }
}
