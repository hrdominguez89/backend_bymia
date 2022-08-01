<?php

namespace App\Entity;

use App\Entity\Model\User as BaseUser;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass="App\Repository\CustomerRepository")
 * @ORM\Table("mia_customer")
 */
class Customer extends BaseUser
{
    /**
     * @var string|null
     *
     * @ORM\Column(name="api_id", type="string", length=255, nullable=true)
     */
    private $apiId;

    /**
     * @var FavoriteProduct[]|ArrayCollection
     *
     * @ORM\OneToMany(targetEntity="App\Entity\FavoriteProduct", mappedBy="customerId")
     */
    private $favoriteProducts;

    /**
     * @var CustomerCouponDiscount[]|ArrayCollection
     *
     * @ORM\OneToMany(targetEntity="App\Entity\CustomerCouponDiscount", mappedBy="customerId", cascade={"remove"})
     */
    private $couponDiscounts;

    /**
     * @var Shopping[]|ArrayCollection
     *
     * @ORM\OneToMany(targetEntity="Shopping", mappedBy="customerId")
     */
    private $shoppingCarts;

    /**
     * @var Order[]|ArrayCollection
     *
     * @ORM\OneToMany(targetEntity="Order", mappedBy="customerId")
     */
    private $shoppingOrders;

    /**
     * @var string|null
     *
     * @ORM\Column(name="identification", type="string", length=255, nullable=true)
     */
    private $identification;

    /**
     * @var string|null
     *
     * @ORM\Column(name="billing_first_name", type="string", length=100, nullable=true)
     * @Assert\Length(min=2, max=100)
     */
    private $billingFirstName;

    /**
     * @var string|null
     *
     * @ORM\Column(name="billing_last_name", type="string", length=100, nullable=true)
     * @Assert\Length(min=2, max=100)
     */
    private $billingLastName;

    /**
     * @var string|null
     *
     * @ORM\Column(name="billing_company_name", type="string", length=255, nullable=true)
     * @Assert\Length(max=100)
     */
    private $billingCompanyName;

    /**
     * @var string|null
     *
     * @ORM\Column(name="billing_country", type="string", length=100, nullable=true)
     * @Assert\Length(min=2, max=100)
     */
    private $billingCountry;

    /**
     * @var string|null
     *
     * @ORM\Column(name="billing_street_address", type="string", length=500, nullable=true)
     * @Assert\Length(min=2, max=500)
     */
    private $billingStreetAddress;

    /**
     * @var string|null
     *
     * @ORM\Column(name="billing_address", type="string", length=255, nullable=true)
     * @Assert\Length(max=255)
     */
    private $billingAddress;

    /**
     * @var string|null
     *
     * @ORM\Column(name="billing_city", type="string", length=100, nullable=true)
     * @Assert\Length(min=2, max=100)
     */
    private $billingCity;

    /**
     * @var string|null
     *
     * @ORM\Column(name="billing_state", type="string", length=100, nullable=true)
     * @Assert\Length(min=2, max=100)
     */
    private $billingState;

    /**
     * @var string|null
     *
     * @ORM\Column(name="billing_postcode", type="string", length=50, nullable=true)
     * @Assert\Length(min=2, max=50)
     */
    private $billingPostcode;

    /**
     * @var string|null
     *
     * @ORM\Column(name="billing_email", type="string", length=100, nullable=true)
     * @Assert\Length(min=5, max=100)
     * @Assert\Email(mode="strict")
     */
    private $billingEmail;

    /**
     * @var string|null
     *
     * @ORM\Column(name="billing_phone", type="string", length=100, nullable=true)
     * @Assert\Length(min=2, max=100)
     */
    private $billingPhone;


    public function __construct()
    {
        parent::__construct();

        $this->roles = json_encode(['ROLE_CUSTOMER']);
    }

    /**
     * @return FavoriteProduct[]|ArrayCollection
     */
    public function getFavoriteProducts()
    {
        return $this->favoriteProducts;
    }

    /**
     * @param FavoriteProduct $favoriteProduct
     * @return $this
     */
    public function addFavoriteProduct(FavoriteProduct $favoriteProduct): Customer
    {
        if (!$this->favoriteProducts->contains($favoriteProduct)) {
            $this->favoriteProducts[] = $favoriteProduct;
        }

        return $this;
    }

    /**
     * @param FavoriteProduct $favoriteProduct
     * @return $this
     */
    public function removeSubcategory(FavoriteProduct $favoriteProduct): Customer
    {
        if ($this->favoriteProducts->contains($favoriteProduct)) {
            $this->favoriteProducts->removeElement($favoriteProduct);
        }

        return $this;
    }

    /**
     * @return CustomerCouponDiscount[]|ArrayCollection
     */
    public function getCouponDiscounts()
    {
        return $this->couponDiscounts;
    }

    /**
     * @param CustomerCouponDiscount $customerCouponDiscount
     * @return $this
     */
    public function addCustomerCouponDiscount(CustomerCouponDiscount $customerCouponDiscount): Customer
    {
        if (!$this->couponDiscounts->contains($customerCouponDiscount)) {
            $this->couponDiscounts[] = $customerCouponDiscount;
        }

        return $this;
    }

    /**
     * @param CustomerCouponDiscount $customerCouponDiscount
     * @return $this
     */
    public function removeCustomerCouponDiscount(CustomerCouponDiscount $customerCouponDiscount): Customer
    {
        if ($this->couponDiscounts->contains($customerCouponDiscount)) {
            $this->couponDiscounts->removeElement($customerCouponDiscount);
        }

        return $this;
    }

    /**
     * @param $subTotal
     * @return float
     */
    public function getDiscount($subTotal): float
    {
        foreach ($this->getCouponDiscounts() as $couponDiscount) {
            if (!$couponDiscount->isApplied()) {
                return $couponDiscount->isPercent()
                    ? ($subTotal * $couponDiscount->getDiscount() / 100)
                    : $couponDiscount->getDiscount();
            }
        }

        return 0.00;
    }

    /**
     * @param $nro
     * @return bool
     */
    public function existCouponDiscount($nro): bool
    {
        foreach ($this->getCouponDiscounts() as $couponDiscount) {
            if ($couponDiscount->getCoupon() == $nro || !$couponDiscount->isApplied()) {
                return true;
            }
        }

        return false;
    }

    /**
     * @return Shopping[]|ArrayCollection
     */
    public function getShoppingCarts()
    {
        return $this->shoppingCarts;
    }

    /**
     * @param Shopping $shoppingCart
     * @return $this
     */
    public function addShoppingCart(Shopping $shoppingCart): Customer
    {
        if (!$this->shoppingCarts->contains($shoppingCart)) {
            $this->shoppingCarts[] = $shoppingCart;
        }

        return $this;
    }

    /**
     * @param Shopping $shoppingCart
     * @return $this
     */
    public function removeShoppingCart(Shopping $shoppingCart): Customer
    {
        if ($this->shoppingCarts->contains($shoppingCart)) {
            $this->shoppingCarts->removeElement($shoppingCart);
        }

        return $this;
    }

    /**
     * @return Order[]|ArrayCollection
     */
    public function getShoppingOrders()
    {
        return $this->shoppingOrders;
    }

    /**
     * @param Order $shoppingOrder
     * @return $this
     */
    public function addShoppingOrder(Order $shoppingOrder): Customer
    {
        if (!$this->shoppingOrders->contains($shoppingOrder)) {
            $this->shoppingOrders[] = $shoppingOrder;
        }

        return $this;
    }

    /**
     * @param Order $shoppingOrder
     * @return $this
     */
    public function removeShoppingOrder(Order $shoppingOrder): Customer
    {
        if ($this->shoppingOrders->contains($shoppingOrder)) {
            $this->shoppingOrders->removeElement($shoppingOrder);
        }

        return $this;
    }

    /**
     * @return string|null
     */
    public function getApiId(): ?string
    {
        return $this->apiId;
    }

    /**
     * @param string|null $apiId
     * @return $this
     */
    public function setApiId(?string $apiId): Customer
    {
        $this->apiId = $apiId;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getIdentification(): ?string
    {
        return $this->identification;
    }

    /**
     * @param string|null $identification
     * @return $this
     */
    public function setIdentification(?string $identification): Customer
    {
        $this->identification = $identification;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getBillingFirstName(): ?string
    {
        return $this->billingFirstName;
    }

    /**
     * @param string|null $billingFirstName
     * @return $this
     */
    public function setBillingFirstName(?string $billingFirstName): Customer
    {
        $this->billingFirstName = $billingFirstName;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getBillingLastName(): ?string
    {
        return $this->billingLastName;
    }

    /**
     * @param string|null $billingLastName
     * @return $this
     */
    public function setBillingLastName(?string $billingLastName): Customer
    {
        $this->billingLastName = $billingLastName;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getBillingCompanyName(): ?string
    {
        return $this->billingCompanyName;
    }

    /**
     * @param string|null $billingCompanyName
     * @return $this
     */
    public function setBillingCompanyName(?string $billingCompanyName): Customer
    {
        $this->billingCompanyName = $billingCompanyName;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getBillingCountry(): ?string
    {
        return $this->billingCountry;
    }

    /**
     * @param string|null $billingCountry
     * @return $this
     */
    public function setBillingCountry(?string $billingCountry): Customer
    {
        $this->billingCountry = $billingCountry;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getBillingStreetAddress(): ?string
    {
        return $this->billingStreetAddress;
    }

    /**
     * @param string|null $billingStreetAddress
     * @return $this
     */
    public function setBillingStreetAddress(?string $billingStreetAddress): Customer
    {
        $this->billingStreetAddress = $billingStreetAddress;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getBillingAddress(): ?string
    {
        return $this->billingAddress;
    }

    /**
     * @param string|null $billingAddress
     * @return $this
     */
    public function setBillingAddress(?string $billingAddress): Customer
    {
        $this->billingAddress = $billingAddress;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getBillingCity(): ?string
    {
        return $this->billingCity;
    }

    /**
     * @param string|null $billingCity
     * @return $this
     */
    public function setBillingCity(?string $billingCity): Customer
    {
        $this->billingCity = $billingCity;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getBillingState(): ?string
    {
        return $this->billingState;
    }

    /**
     * @param string|null $billingState
     * @return $this
     */
    public function setBillingState(?string $billingState): Customer
    {
        $this->billingState = $billingState;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getBillingPostcode(): ?string
    {
        return $this->billingPostcode;
    }

    /**
     * @param string|null $billingPostcode
     * @return $this
     */
    public function setBillingPostcode(?string $billingPostcode): Customer
    {
        $this->billingPostcode = $billingPostcode;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getBillingEmail(): ?string
    {
        return $this->billingEmail;
    }

    /**
     * @param string|null $billingEmail
     * @return $this
     */
    public function setBillingEmail(?string $billingEmail): Customer
    {
        $this->billingEmail = $billingEmail;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getBillingPhone(): ?string
    {
        return $this->billingPhone;
    }

    /**
     * @param string|null $billingPhone
     * @return $this
     */
    public function setBillingPhone(?string $billingPhone): Customer
    {
        $this->billingPhone = $billingPhone;

        return $this;
    }


    /**
     * @return array
     */
    public function getRoles(): array
    {
        return array_unique(['ROLE_CUSTOMER']);
    }

    /**
     * @return array
     */
    public function asArray(): array
    {
        $coupon = null;
        foreach ($this->getCouponDiscounts() as $couponDiscount) {
            if (!$couponDiscount->isApplied()) {
                $coupon = $couponDiscount->asArray();
            }
        }

        return array_merge(parent::asArray(), [
            "address" => [
                "default" => true,
                "firstName" => $this->getBillingFirstName(),
                "lastName" => $this->getBillingLastName(),
                "email" => $this->getBillingEmail(),
                "phone" => $this->getBillingPhone(),
                "country" => $this->getBillingCountry(),
                "city" => $this->getBillingCity(),
                "postcode" => $this->getBillingPostcode(),
                "address" => $this->getBillingAddress(),
            ],
            "coupon" => $coupon,
        ]);
    }
}
