<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use ApiPlatform\Core\Annotation\ApiResource;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ORM\Entity(repositoryClass="App\Repository\AboutUsRepository")
 * @ORM\Table("mia_about_us")
 * 
 * @ApiResource(
 *      collectionOperations={"get"={"normalization_context"={"groups"="AboutUs:List"}}},
 *      itemOperations={"get"={"normalization_context"={"groups"="AboutUs:item"}}},
 *      paginationEnabled=false
 * )
 */
class AboutUs
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="bigint")
     * 
     */
    private $id;

    /**
     * @var string|null
     *
     * @ORM\Column(name="description", type="text", nullable=true)
     * @Groups({"AboutUs:List","AboutUs:item"})
     */
    private $description;

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
    public function getDescription(): ?string
    {
        return $this->description;
    }

    /**
     * @param string|null $description
     * @return $this
     */
    public function setDescription(?string $description): AboutUs
    {
        $this->description = $description;

        return $this;
    }
}
