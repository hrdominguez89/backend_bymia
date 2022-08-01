<?php

namespace App\Form\Model;

class CustomerSearchDto
{
    private ?string $name;

    public function __construct()
    {
        $this->name = null;
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
}
