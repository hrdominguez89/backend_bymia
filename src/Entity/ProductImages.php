<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\ProductImagesRepository")
 * @ORM\Table("mia_product_image")
 */
class ProductImages
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="bigint")
     */
    private $id;

    /**
     * @var string|null
     *
     * @ORM\Column(name="image", type="text", nullable=true)
     */
    private $image;


    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return string|null
     */
    public function getImage(): ?string
    {
        return $this->image;
    }

    /**
     * @param string|null $image
     * @return $this
     */
    public function setImage(?string $image): ProductImages
    {
        $this->image = $image;

        return $this;
    }

}
