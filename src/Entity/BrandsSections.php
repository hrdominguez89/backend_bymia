<?php

namespace App\Entity;

use App\Repository\BrandsSectionsRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=BrandsSectionsRepository::class)
 */
class BrandsSections
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $brandName1;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $brandImage1;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $brandName2;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $brandImage2;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $brandName3;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $brandImage3;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $brandName4;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $brandImage4;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $brandName5;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $brandImage5;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $brandName6;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $brandImage6;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getBrandName1(): ?string
    {
        return $this->brandName1;
    }

    public function setBrandName1(string $brandName1): self
    {
        $this->brandName1 = $brandName1;

        return $this;
    }

    public function getBrandImage1(): ?string
    {
        return $this->brandImage1;
    }

    public function setBrandImage1(string $brandImage1): self
    {
        $this->brandImage1 = $brandImage1;

        return $this;
    }

    public function getBrandName2(): ?string
    {
        return $this->brandName2;
    }

    public function setBrandName2(string $brandName2): self
    {
        $this->brandName2 = $brandName2;

        return $this;
    }

    public function getBrandImage2(): ?string
    {
        return $this->brandImage2;
    }

    public function setBrandImage2(string $brandImage2): self
    {
        $this->brandImage2 = $brandImage2;

        return $this;
    }

    public function getBrandName3(): ?string
    {
        return $this->brandName3;
    }

    public function setBrandName3(string $brandName3): self
    {
        $this->brandName3 = $brandName3;

        return $this;
    }

    public function getBrandImage3(): ?string
    {
        return $this->brandImage3;
    }

    public function setBrandImage3(string $brandImage3): self
    {
        $this->brandImage3 = $brandImage3;

        return $this;
    }

    public function getBrandName4(): ?string
    {
        return $this->brandName4;
    }

    public function setBrandName4(string $brandName4): self
    {
        $this->brandName4 = $brandName4;

        return $this;
    }

    public function getBrandImage4(): ?string
    {
        return $this->brandImage4;
    }

    public function setBrandImage4(string $brandImage4): self
    {
        $this->brandImage4 = $brandImage4;

        return $this;
    }

    public function getBrandName5(): ?string
    {
        return $this->brandName5;
    }

    public function setBrandName5(string $brandName5): self
    {
        $this->brandName5 = $brandName5;

        return $this;
    }

    public function getBrandImage5(): ?string
    {
        return $this->brandImage5;
    }

    public function setBrandImage5(string $brandImage5): self
    {
        $this->brandImage5 = $brandImage5;

        return $this;
    }

    public function getBrandName6(): ?string
    {
        return $this->brandName6;
    }

    public function setBrandName6(string $brandName6): self
    {
        $this->brandName6 = $brandName6;

        return $this;
    }

    public function getBrandImage6(): ?string
    {
        return $this->brandImage6;
    }

    public function setBrandImage6(string $brandImage6): self
    {
        $this->brandImage6 = $brandImage6;

        return $this;
    }

    public function getBrands()
    {
        return [
            [
                "id" => 1,
                "name" => $this->getBrandName1(),
                "image" => $this->getBrandImage1()
            ],
            [
                "id" => 2,
                "name" => $this->getBrandName2(),
                "image" => $this->getBrandImage2()
            ],
            [
                "id" => 3,
                "name" => $this->getBrandName3(),
                "image" => $this->getBrandImage3()
            ],
            [
                "id" => 4,
                "name" => $this->getBrandName4(),
                "image" => $this->getBrandImage4()
            ],
            [
                "id" => 5,
                "name" => $this->getBrandName5(),
                "image" => $this->getBrandImage5()
            ],
            [
                "id" => 6,
                "name" => $this->getBrandName6(),
                "image" => $this->getBrandImage6()
            ]
        ];
    }
}
