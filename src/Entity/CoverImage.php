<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\CoverImageRepository")
 * @ORM\Table("mia_cover_image")
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
     * @ORM\Column(name="description", type="text", nullable=true)
     */
    private $description;

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
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $pre_title;

    /**
     * @ORM\Column(type="smallint", nullable=true)
     */
    private $number_order;

    /**
     * @ORM\Column(type="boolean")
     */
    private $visible;

    public function __construct()
    {
        $this->visible = false;
        $this->number_order = null;
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
    public function getDescription(): ?string
    {
        return $this->description;
    }

    /**
     * @param string|null $description
     * @return $this
     */
    public function setDescription(?string $description): CoverImage
    {
        $this->description = $description;

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

    public function getPreTitle(): ?string
    {
        return $this->pre_title;
    }

    public function setPreTitle(?string $pre_title): self
    {
        $this->pre_title = $pre_title;

        return $this;
    }

    public function getNumberOrder(): ?int
    {
        return $this->number_order;
    }

    public function setNumberOrder(?int $number_order): self
    {
        $this->number_order = $number_order;

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
