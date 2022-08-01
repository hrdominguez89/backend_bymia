<?php

namespace App\Entity;

use App\Entity\Model\Category as BaseCategory;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\SubcategoryRepository")
 * @ORM\Table("mia_sub_category")
 */
class Subcategory extends BaseCategory
{
    /**
     * @var Category
     *
     * @ORM\ManyToOne(targetEntity="App\Entity\Category", inversedBy="subcategories")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="categoria_id", referencedColumnName="id", nullable=false)
     * })
     */
    private $categoryId;

    public function __construct()
    {
        parent::__construct();
    }

    /**
     * @return Category
     */
    public function getCategoryId(): Category
    {
        return $this->categoryId;
    }

    /**
     * @param Category $categoryId
     * @return $this
     */
    public function setCategoryId(Category $categoryId): Subcategory
    {
        $this->categoryId = $categoryId;

        $categoryId->addSubcategory($this);

        return $this;
    }

    /**
     * @param bool $withParents
     * @return array
     */
    public function asArray(bool $withParents = true): array
    {
        return array_merge(parent::asArray(), [
            'parents' => $withParents ? [$this->categoryId->asArray()] : [],
            'children' => [],
        ]);
    }

}
