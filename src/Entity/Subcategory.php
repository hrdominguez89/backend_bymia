<?php

namespace App\Entity;

use App\Entity\Model\Category as BaseCategory;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\SubcategoryRepository")
 * @ORM\Table("mia_sub_category")
 */
class Subcategory extends BaseCategory
{
    /**
     * @ORM\ManyToMany(targetEntity=Category::class, inversedBy="subcategories")
     */
    private $category;

    public function __construct()
    {
        $this->category = new ArrayCollection();
    }

    /**
     * @return Collection|Category[]
     */
    public function getCategory(): Collection
    {
        return $this->category;
    }

    public function addCategory(Category $category): self
    {
        if (!$this->category->contains($category)) {
            $this->category[] = $category;
        }

        return $this;
    }

    public function removeCategory(Category $category): self
    {
        $this->category->removeElement($category);

        return $this;
    }
}
