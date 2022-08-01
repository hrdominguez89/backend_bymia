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
     * @var bool
     *
     * @ORM\Column(type="boolean")
     */
    private $main;

    /**
     * @var string
     *
     * @ORM\Column(name="image_lg", type="string", length=255)
     */
    private $imageLg;

    /**
     * @var string
     *
     * @ORM\Column(name="image_sm", type="string", length=255)
     */
    private $imageSm;

    public function __construct()
    {
        $this->main = false;
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

    /**
     * @return bool
     */
    public function isMain(): bool
    {
        return $this->main;
    }

    /**
     * @param bool $main
     * @return $this
     */
    public function setMain(bool $main): CoverImage
    {
        $this->main = $main;

        return $this;
    }

    /**
     * @return string
     */
    public function getImageLg(): string
    {
        return $this->imageLg;
    }

    /**
     * @param string $imageLg
     * @return $this
     */
    public function setImageLg(string $imageLg): CoverImage
    {
        $this->imageLg = $imageLg;

        return $this;
    }

    /**
     * @return string
     */
    public function getImageSm(): string
    {
        return $this->imageSm;
    }

    /**
     * @param string $imageSm
     * @return $this
     */
    public function setImageSm(string $imageSm): CoverImage
    {
        $this->imageSm = $imageSm;

        return $this;
    }

    public function getMain(): ?bool
    {
        return $this->main;
    }

}
