<?php

namespace App\Form\Model;

class ProductSearchDto
{
    private  $name;
    private  $category;
    private  $subcategory;
    private  $state;

    public function __construct()
    {
        $this->name = null;
        $this->category = null;
        $this->subcategory = null;
        $this->state = null;
    }

    /**
     * Get the value of name
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set the value of name
     *
     * @return  self
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get the value of category
     */
    public function getCategory()
    {
        return $this->category;
    }

    /**
     * Set the value of category
     *
     * @return  self
     */
    public function setCategory($category)
    {
        $this->category = $category;

        return $this;
    }

    /**
     * Get the value of subcategory
     */
    public function getSubcategory()
    {
        return $this->subcategory;
    }

    /**
     * Set the value of subcategory
     *
     * @return  self
     */
    public function setSubcategory($subcategory)
    {
        $this->subcategory = $subcategory;

        return $this;
    }

    /**
     * @return null
     */
    public function getState()
    {
        return $this->state;
    }

    /**
     * @param null $state
     */
    public function setState($state): void
    {
        $this->state = $state;
    }


}
