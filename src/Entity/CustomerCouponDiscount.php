<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\CustomerCouponDiscountRepository")
 * @ORM\Table("mia_customer_coupon_discount")
 */
class CustomerCouponDiscount
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="bigint")
     */
    private $id;

    /**
     * @var Customer
     *
     * @ORM\ManyToOne(targetEntity="App\Entity\Customer", inversedBy="couponDiscounts", cascade={"persist"})
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="customer_id", referencedColumnName="id", nullable=false)
     * })
     */
    private $customerId;

    /**
     * @var bool
     *
     * @ORM\Column(name="percent", type="boolean")
     */
    private $percent;

    /**
     * @var float
     *
     * @ORM\Column(name="discount", type="float")
     */
    private $discount;

    /**
     * @ORM\Column(name="coupon", type="string", length=255)
     */
    private $coupon;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="date_apply", type="datetime")
     */
    private $dateApply;

    /**
     * @var bool
     *
     * @ORM\Column(name="applied", type="boolean")
     */
    private $applied;

    /**
     * @param Customer $customerId
     * @param CouponDiscount $couponDiscount
     */
    public function __construct(Customer $customerId, CouponDiscount $couponDiscount)
    {
        $this->setCustomerId($customerId);

        $this->percent = $couponDiscount->isPercent();
        $this->discount = $couponDiscount->getValue();
        $this->coupon = $couponDiscount->getNro();

        $this->dateApply = new \DateTime();
        $this->applied = false;
    }


    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @return string|null
     */
    public function getCoupon(): ?string
    {
        return $this->coupon;
    }

    /**
     * @param string $coupon
     * @return $this
     */
    public function setCoupon(string $coupon): CustomerCouponDiscount
    {
        $this->coupon = $coupon;

        return $this;
    }

    /**
     * @return Customer
     */
    public function getCustomerId(): Customer
    {
        return $this->customerId;
    }

    /**
     * @param Customer $customerId
     * @return $this
     */
    public function setCustomerId(Customer $customerId): CustomerCouponDiscount
    {
        $this->customerId = $customerId;

        $customerId->addCustomerCouponDiscount($this);

        return $this;
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
    public function setPercent(bool $percent): CustomerCouponDiscount
    {
        $this->percent = $percent;

        return $this;
    }

    /**
     * @return float
     */
    public function getDiscount(): float
    {
        return $this->discount;
    }

    /**
     * @param float $discount
     * @return $this
     */
    public function setDiscount(float $discount): CustomerCouponDiscount
    {
        $this->discount = $discount;

        return $this;
    }

    /**
     * @return \DateTime
     */
    public function getDateApply(): \DateTime
    {
        return $this->dateApply;
    }

    /**
     * @param \DateTime $dateApply
     * @return $this
     */
    public function setDateApply(\DateTime $dateApply): CustomerCouponDiscount
    {
        $this->dateApply = $dateApply;

        return $this;
    }

    /**
     * @return bool
     */
    public function isApplied(): bool
    {
        return $this->applied;
    }

    /**
     * @param bool $applied
     * @return $this
     */
    public function setApplied(bool $applied): CustomerCouponDiscount
    {
        $this->applied = $applied;

        return $this;
    }

    /**
     * @return array
     */
    public function asArray(): array
    {
        return [
            "title" => "Cupón de descuento",
            "price" => $this->getDiscount(),
            "type" => $this->isPercent() ? "percent" : "discount",
        ];
    }

    public function getPercent(): ?bool
    {
        return $this->percent;
    }

    public function getApplied(): ?bool
    {
        return $this->applied;
    }
}
