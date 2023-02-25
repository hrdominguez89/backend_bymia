<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\AdvertisementsRepository")
 * @ORM\Table("mia_advertisements")
 */
class Advertisements
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
     * @ORM\Column(name="src1", type="string", length=255, nullable=true)
     */
    private $src1;

    /**
     * @var string|null
     *
     * @ORM\Column(name="src_sm1", type="string", length=255, nullable=true)
     */
    private $srcSm1;

    /**
     * @var string|null
     *
     * @ORM\Column(name="src2", type="string", length=255, nullable=true)
     */
    private $src2;

    /**
     * @var string|null
     *
     * @ORM\Column(name="src_sm2", type="string", length=255, nullable=true)
     */
    private $srcSm2;

    /**
     * @var string|null
     *
     * @ORM\Column(name="src3", type="string", length=255, nullable=true)
     */
    private $src3;

    /**
     * @var string|null
     *
     * @ORM\Column(name="src_sm3", type="string", length=255, nullable=true)
     */
    private $srcSm3;

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
    public function getSrc1(): ?string
    {
        return $this->src1;
    }

    /**
     * @param string|null $src1
     * @return $this
     */
    public function setSrc1(?string $src1): Advertisements
    {
        $this->src1 = $src1;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getSrc2(): ?string
    {
        return $this->src2;
    }

    /**
     * @param string|null $src2
     * @return $this
     */
    public function setSrc2(?string $src2): Advertisements
    {
        $this->src2 = $src2;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getSrc3(): ?string
    {
        return $this->src3;
    }

    /**
     * @param string|null $src3
     * @return $this
     */
    public function setSrc3(?string $src3): Advertisements
    {
        $this->src3 = $src3;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getSrcSm1(): ?string
    {
        return $this->srcSm1;
    }

    /**
     * @param string|null $srcSm1
     * @return $this
     */
    public function setSrcSm1(?string $srcSm1): Advertisements
    {
        $this->srcSm1 = $srcSm1;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getSrcSm2(): ?string
    {
        return $this->srcSm2;
    }

    /**
     * @param string|null $srcSm2
     * @return $this
     */
    public function setSrcSm2(?string $srcSm2): Advertisements
    {
        $this->srcSm2 = $srcSm2;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getSrcSm3(): ?string
    {
        return $this->srcSm3;
    }

    /**
     * @param string|null $srcSm3
     * @return $this
     */
    public function setSrcSm3(?string $srcSm3): Advertisements
    {
        $this->srcSm3 = $srcSm3;

        return $this;
    }

    public function getBanners()
    {
        return [
            [
                "id" => 1,
                "format" => "desktop",
                "image" => $this->getSrc1()
            ],
            [
                "id" => 2,
                "format" => "mobile",
                "image" => $this->getSrcSm1()
            ],
            [
                "id" => 3,
                "format" => "desktop",
                "image" => $this->getSrc2()
            ],
            [
                "id" => 4,
                "format" => "mobile",
                "image" => $this->getSrcSm2()
            ],
            [
                "id" => 5,
                "format" => "desktop",
                "image" => $this->getSrc3()
            ],
            [
                "id" => 6,
                "format" => "mobile",
                "image" => $this->getSrcSm3()
            ],
        ];
    }
}
