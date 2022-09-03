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
     * @var Subcategory[]|ArrayCollection
     *
     * @ORM\OneToMany(targetEntity="App\Entity\Subcategory", mappedBy="categoryId", orphanRemoval=true)
     */
    private $subcategories;

    public function __construct()
    {
        $this->subcategories = new ArrayCollection();
    }

    /**
     * @return Subcategory[]|ArrayCollection
     */
    public function getSubcategories()
    {
        return $this->subcategories;
    }

    /**
     * @param Subcategory $subcategory
     * @return $this
     */
    public function addSubcategory(Subcategory $subcategory): Category
    {
        if (!$this->subcategories->contains($subcategory)) {
            $this->subcategories[] = $subcategory;
        }

        return $this;
    }

    /**
     * @param Subcategory $subcategory
     * @return $this
     */
    public function removeSubcategory(Subcategory $subcategory): Category
    {
        if ($this->subcategories->contains($subcategory)) {
            $this->subcategories->removeElement($subcategory);
        }

        return $this;
    }

    /**
     * @return array
     */
    public function asArray(): array
    {
        $children = [];

        foreach ($this->getSubcategories() as $subcategory) {
            $children[] = $subcategory->asArray(false);
        }

        return array_merge(parent::asArray(), [
            'parents' => [],
            'children' => $children,
        ]);
    }

    /**
     * @return array
     */
    public function asMenu(): array
    {
        $children = [];

        foreach ($this->getSubcategories() as $subcategory) {
            $children[] = $subcategory->asMenu();
        }

        return array_merge(parent::asMenu(), [
            'children' => $children,
        ]);
    }


}
