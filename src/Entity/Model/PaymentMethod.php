<?php

namespace App\Entity\Model;

use Cocur\Slugify\Slugify;
use Doctrine\ORM\Mapping as ORM;

abstract class PaymentMethod
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="bigint")
     */
    protected $id;

    /**
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=100)
     */
    protected $name;

    /**
     * @ORM\Column(name="slug", type="string", length=100)
     */
    protected $slug;

    /**
     * @var bool
     *
     * @ORM\Column(name="active", type="boolean")
     */
    protected $active;

    /**
     * @var bool
     *
     * @ORM\Column(name="sand_box", type="boolean")
     */
    protected $sandBox;

    public function __construct()
    {
        $this->active = true;
        $this->sandBox = true;
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
    public function setName(string $name): self
    {
        $this->name = $name;

        $slugify = new Slugify();

        $this->slug = $slugify->slugify($name);

        return $this;
    }

    /**
     * @return mixed
     */
    public function getSlug()
    {
        return $this->slug;
    }

    /**
     * @param mixed $slug
     */
    public function setSlug($slug): self
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
    public function setActive(bool $active): self
    {
        $this->active = $active;

        return $this;
    }

    /**
     * @return bool
     */
    public function isSandBox(): bool
    {
        return $this->sandBox;
    }

    /**
     * @param bool $sandBox
     * @return $this
     */
    public function setSandBox(bool $sandBox): self
    {
        $this->sandBox = $sandBox;

        return $this;
    }


}
