<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\CouponDiscountRepository")
 * @ORM\Table("mia_coupon_discount")
 */
class CouponDiscount
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="bigint")
     */
    private $id;

    /**
     * @var bool
     *
     * @ORM\Column(name="percent", type="boolean")
     */
    private $percent;

    /**
     * @var float
     *
     * @ORM\Column(name="value", type="float")
     */
    private $value;

    /**
     * @var integer
     *
     * @ORM\Column(name="number_of_uses", type="integer")
     */
    private $numberOfUses;

    /**
     * @var string|null
     *
     * @ORM\Column(name="nro", type="string", length=255, nullable=true)
     */
    private $nro;

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return bool
     */
    public function isPercent(): bool
    {
        return $this->percent;
    }

    /**
     * @param bool $percent
     * @return $this
     */
    public function setPercent(bool $percent): CouponDiscount
    {
        $this->percent = $percent;

        return $this;
    }

    /**
     * @return int
     */
    public function getNumberOfUses(): int
    {
        return $this->numberOfUses;
    }

    /**
     * @param int $numberOfUses
     * @return $this
     */
    public function setNumberOfUses(int $numberOfUses): CouponDiscount
    {
        $this->numberOfUses = $numberOfUses;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getNro(): ?string
    {
        return $this->nro;
    }

    /**
     * @param string|null $nro
     * @return $this
     */
    public function setNro(?string $nro): CouponDiscount
    {
        $this->nro = $nro;

        return $this;
    }

    /**
     * @return float
     */
    public function getValue(): float
    {
        return $this->value;
    }

    /**
     * @param float $value
     * @return $this
     */
    public function setValue(float $value): CouponDiscount
    {
        $this->value = $value;

        return $this;
    }

    public function getPercent(): ?bool
    {
        return $this->percent;
    }


}
