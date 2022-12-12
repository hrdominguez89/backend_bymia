<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\CoverImageRepository")
 * @ORM\Table("mia_cover_image")
 *  
 * 
 */
class CoverImage
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
     * @ORM\Column(name="title", type="string", length=255)
     */
    private $title;

    /**
     * @var string|null
     *
     * @ORM\Column(name="subtitle", type="text", nullable=true)
     */
    private $subtitle;

    /**
     * @var string|null
     *
     * @ORM\Column(name="name_btn", type="string", length=255, nullable=true)
     */
    private $nameBtn;

    /**
     * @var string|null
     *
     * @ORM\Column(name="link_btn", type="string", length=255, nullable=true)
     */
    private $linkBtn;

    /**
     * @ORM\Column(name="volanta", type="string", length=255, nullable=true)
     */
    private $volanta;

    /**
     * @ORM\Column(name="number_order",type="smallint", nullable=true)
     */
    private $numberOrder;

    /**
     * @ORM\Column(type="boolean")
     */
    private $visible;

    public function __construct()
    {
        $this->visible = false;
        $this->numberOrder = null;
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
    public function getTitle(): string
    {
        return $this->title;
    }

    /**
     * @param string $title
     * @return $this
     */
    public function setTitle(string $title): CoverImage
    {
        $this->title = $title;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getSubtitle(): ?string
    {
        return $this->subtitle;
    }

    /**
     * @param string|null $subtitle
     * @return $this
     */
    public function setSubtitle(?string $subtitle): CoverImage
    {
        $this->subtitle = $subtitle;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getNameBtn(): ?string
    {
        return $this->nameBtn;
    }

    /**
     * @param string|null $nameBtn
     * @return $this
     */
    public function setNameBtn(?string $nameBtn): CoverImage
    {
        $this->nameBtn = $nameBtn;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getLinkBtn(): ?string
    {
        return $this->linkBtn;
    }

    /**
     * @param string|null $linkBtn
     * @return $this
     */
    public function setLinkBtn(?string $linkBtn): CoverImage
    {
        $this->linkBtn = $linkBtn;

        return $this;
    }

    public function getVolanta(): ?string
    {
        return $this->volanta;
    }

    public function setVolanta(?string $volanta): self
    {
        $this->volanta = $volanta;

        return $this;
    }

    public function getNumberOrder(): ?int
    {
        return $this->numberOrder;
    }

    public function setNumberOrder(?int $numberOrder): self
    {
        $this->numberOrder = $numberOrder;

        return $this;
    }

    public function getVisible(): ?bool
    {
        return $this->visible;
    }

    public function setVisible(bool $visible): self
    {
        $this->visible = $visible;

        return $this;
    }
}
