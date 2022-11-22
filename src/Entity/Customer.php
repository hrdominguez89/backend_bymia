<?php

namespace App\Entity;

use App\Entity\Model\User as BaseUser;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use ApiPlatform\Core\Annotation\ApiResource;


/**
 * @ORM\Entity(repositoryClass="App\Repository\CustomerRepository")
 * @ORM\Table("mia_customer")
 * 
 * @ApiResource()
 */
class Customer extends BaseUser
{
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
     * @var Order[]|ArrayCollection
     *
     * @ORM\OneToMany(targetEntity="Order", mappedBy="customerId")
     */
    private $shoppingOrders;

    
    /**
     * @var string|null
     *
     * @ORM\Column(name="lastname", type="string", length=100, nullable=true)
     * @Assert\Length(min=2, max=100)
     */
    private $lastname;

    /**
     * @var string|null
     *
     * @ORM\Column(name="country_code_cel_phone", type="string", length=100, nullable=true)
     * @Assert\Length(min=2, max=100)
     */
    public $country_code_cel_phone;

    /**
     * @var string|null
     *
     * @ORM\Column(name="state_code_cel_phone", type="string", length=100, nullable=true)
     * @Assert\Length(min=2, max=100)
     */
    public $state_code_cel_phone;

    /**
     * @var string|null
     *
     * @ORM\Column(name="cel_phone", type="string", length=100, nullable=true)
     * @Assert\Length(min=2, max=100)
     */
    public $cel_phone;

    /**
     * @var string|null
     *
     * @ORM\Column(name="country_code_phone", type="string", length=100, nullable=true)
     * @Assert\Length(min=2, max=100)
     */
    public $country_code_phone;

    /**
     * @var string|null
     *
     * @ORM\Column(name="state_code_phone", type="string", length=100, nullable=true)
     * @Assert\Length(min=2, max=100)
     */
    public $state_code_phone;

    /**
     * @var string|null
     *
     * @ORM\Column(name="phone", type="string", length=100, nullable=true)
     * @Assert\Length(min=2, max=100)
     */
    public $phone;

    /**
     * @ORM\ManyToOne(targetEntity=CustomersTypesRoles::class, inversedBy="customers")
     */
    public $customer_type_role;

    /**
     * @ORM\ManyToOne(targetEntity=RegistrationType::class, inversedBy="customers")
     */
    public $registration_type;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="customers")
     */
    public $registration_user;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    public $registration_date;

    /**
     * @ORM\OneToMany(targetEntity=CustomerAddresses::class, mappedBy="customer")
     */
    public $customerAddresses;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $status;

    /**
     * @ORM\ManyToOne(targetEntity=GenderType::class, inversedBy="customers")
     */
    private $gender_type;

    /**
     * @ORM\Column(type="date", nullable=true)
     */
    private $date_of_birth;

    /**
     * @ORM\Column(type="bigint", nullable=true)
     */
    private $google_oauth_uid;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $url_facebook;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $url_instagram;


    public function __construct()
    {
        parent::__construct();

        $this->favoriteProducts = new ArrayCollection();
        $this->couponDiscounts = new ArrayCollection();
        $this->shoppingOrders = new ArrayCollection();
        $this->customerAddresses = new ArrayCollection();
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

    public function removeFavoriteProduct(FavoriteProduct $favoriteProduct): self
    {
        if ($this->favoriteProducts->removeElement($favoriteProduct)) {
            // set the owning side to null (unless already changed)
            if ($favoriteProduct->getCustomerId() === $this) {
            }
        }

        return $this;
    }

    public function addCouponDiscount(CustomerCouponDiscount $couponDiscount): self
    {
        if (!$this->couponDiscounts->contains($couponDiscount)) {
            $this->couponDiscounts[] = $couponDiscount;
            $couponDiscount->setCustomerId($this);
        }

        return $this;
    }

    public function removeCouponDiscount(CustomerCouponDiscount $couponDiscount): self
    {
        if ($this->couponDiscounts->removeElement($couponDiscount)) {
            // set the owning side to null (unless already changed)
            if ($couponDiscount->getCustomerId() === $this) {
            }
        }

        return $this;
    }

    public function getLastname(): ?string
    {
        return $this->lastname;
    }

    public function setLastname(?string $lastname): self
    {
        $this->lastname = $lastname;

        return $this;
    }

    public function getCountryCodeCelPhone(): ?string
    {
        return $this->country_code_cel_phone;
    }

    public function setCountryCodeCelPhone(?string $country_code_cel_phone): self
    {
        $this->country_code_cel_phone = $country_code_cel_phone;

        return $this;
    }

    public function getStateCodeCelPhone(): ?string
    {
        return $this->state_code_cel_phone;
    }

    public function setStateCodeCelPhone(?string $state_code_cel_phone): self
    {
        $this->state_code_cel_phone = $state_code_cel_phone;

        return $this;
    }

    public function getCelPhone(): ?string
    {
        return $this->cel_phone;
    }

    public function setCelPhone(?string $cel_phone): self
    {
        $this->cel_phone = $cel_phone;

        return $this;
    }

    public function getCountryCodePhone(): ?string
    {
        return $this->country_code_phone;
    }

    public function setCountryCodePhone(?string $country_code_phone): self
    {
        $this->country_code_phone = $country_code_phone;

        return $this;
    }

    public function getStateCodePhone(): ?string
    {
        return $this->state_code_phone;
    }

    public function setStateCodePhone(?string $state_code_phone): self
    {
        $this->state_code_phone = $state_code_phone;

        return $this;
    }

    public function getPhone(): ?string
    {
        return $this->phone;
    }

    public function setPhone(?string $phone): self
    {
        $this->phone = $phone;

        return $this;
    }

    public function getCustomerTypeRole(): ?CustomersTypesRoles
    {
        return $this->customer_type_role;
    }

    public function setCustomerTypeRole(?CustomersTypesRoles $customer_type_role): self
    {
        $this->customer_type_role = $customer_type_role;

        return $this;
    }

    public function getRegistrationType(): ?RegistrationType
    {
        return $this->registration_type;
    }

    public function setRegistrationType(?RegistrationType $registration_type): self
    {
        $this->registration_type = $registration_type;

        return $this;
    }

    public function getRegistrationUser(): ?User
    {
        return $this->registration_user;
    }

    public function setRegistrationUser(?User $registration_user): self
    {
        $this->registration_user = $registration_user;

        return $this;
    }

    public function getRegistrationDate(): ?\DateTimeInterface
    {
        return $this->registration_date;
    }

    public function setRegistrationDate(?\DateTimeInterface $registration_date): self
    {
        $this->registration_date = $registration_date;

        return $this;
    }

    /**
     * @return Collection|CustomerAddresses[]
     */
    public function getCustomerAddresses(): Collection
    {
        return $this->customerAddresses;
    }

    public function addCustomerAddress(CustomerAddresses $customerAddress): self
    {
        if (!$this->customerAddresses->contains($customerAddress)) {
            $this->customerAddresses[] = $customerAddress;
            $customerAddress->setCustomer($this);
        }

        return $this;
    }

    public function removeCustomerAddress(CustomerAddresses $customerAddress): self
    {
        if ($this->customerAddresses->removeElement($customerAddress)) {
            // set the owning side to null (unless already changed)
            if ($customerAddress->getCustomer() === $this) {
                $customerAddress->setCustomer(null);
            }
        }

        return $this;
    }

    public function getStatus(): ?bool
    {
        return $this->status;
    }

    public function setStatus(?bool $status = true): self
    {
        $this->status = $status;

        return $this;
    }

    public function getGenderType(): ?GenderType
    {
        return $this->gender_type;
    }

    public function setGenderType(?GenderType $gender_type): self
    {
        $this->gender_type = $gender_type;

        return $this;
    }

    public function getDateOfBirth(): ?\DateTimeInterface
    {
        return $this->date_of_birth;
    }

    public function setDateOfBirth(?\DateTimeInterface $date_of_birth): self
    {
        $this->date_of_birth = $date_of_birth;

        return $this;
    }

    public function getGoogleOauthUid(): ?string
    {
        return $this->google_oauth_uid;
    }

    public function setGoogleOauthUid(?string $google_oauth_uid): self
    {
        $this->google_oauth_uid = $google_oauth_uid;

        return $this;
    }

    public function getUrlFacebook(): ?string
    {
        return $this->url_facebook;
    }

    public function setUrlFacebook(?string $url_facebook): self
    {
        $this->url_facebook = $url_facebook;

        return $this;
    }

    public function getUrlInstagram(): ?string
    {
        return $this->url_instagram;
    }

    public function setUrlInstagram(?string $url_instagram): self
    {
        $this->url_instagram = $url_instagram;

        return $this;
    }
}
