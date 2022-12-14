<?php

namespace App\Entity;

use App\Entity\Model\User as BaseUser;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;



/**
 * @ORM\Entity(repositoryClass="App\Repository\CustomerRepository")
 * @ORM\Table("mia_customer")
 * @UniqueEntity("email")
 */
class Customer extends BaseUser
{
    const ROLE_DEFAULT = 'ROLE_CUSTOMER';

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
     * @var \DateTime
     * @ORM\Column(type="datetime", nullable=false, options={"default":"CURRENT_TIMESTAMP"})
     */

    public $registration_date;

    /**
     * @ORM\OneToMany(targetEntity=CustomerAddresses::class, mappedBy="customer")
     */
    public $customerAddresses;

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


    /**
     * @var string|null
     *
     * @ORM\Column(name="password", type="string", length=512, nullable=false)
     */
    protected $password;

    /**
     * @var string|array
     *
     * @ORM\Column(name="roles", type="string", length=255, nullable=false)
     */
    protected $roles;

    /**
     * @ORM\Column(type="integer", options={"default":0})
     */
    private $attempts_send_crm;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $error_message_crm;

    /**
     * @ORM\ManyToOne(targetEntity=CommunicationStatesBetweenPlatforms::class, inversedBy="customers")
     * @ORM\JoinColumn(nullable=false,options={"default":0})
     */
    private $status_sent_crm;

    /**
     * @ORM\Column(type="uuid", nullable=true)
     */
    private $verification_code;

    /**
     * @ORM\Column(type="boolean", options={"default":0})
     */
    private $change_password;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $change_password_date;

    /**
     * @ORM\ManyToOne(targetEntity=CustomerStatusType::class, inversedBy="customers")
     * @ORM\JoinColumn(nullable=false)
     */
    private $status;

    /**
     * @ORM\ManyToOne(targetEntity=Countries::class, inversedBy="customers")
     * @ORM\JoinColumn(nullable=false)
     */
    private $country_phone_code;


    public function __construct()
    {
        parent::__construct();

        $this->favoriteProducts = new ArrayCollection();
        $this->couponDiscounts = new ArrayCollection();
        $this->shoppingOrders = new ArrayCollection();
        $this->customerAddresses = new ArrayCollection();
        $this->roles = json_encode([self::ROLE_DEFAULT]);
        $this->registration_date = new \DateTime();
        $this->attempts_send_crm = 0;
        $this->change_password = false;
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
     * @param string|null $password
     * @return $this
     */
    public function setPassword(?string $password): self
    {
        $this->password = password_hash($password, PASSWORD_BCRYPT);

        return $this;
    }

    public function getPassword(): ?string
    {
        return $this->password;
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
     * @return array
     */
    public function getRoles(): array
    {
        return array_unique(['ROLE_CUSTOMER']);
    }

    /**
     * @return array
     */
    public function getCustomerTotalInfo(): array
    {
        $addresses = $this->getCustomerAddresses();
        foreach ($addresses as $address) {
            if ($address->getActive()) {

                if ($address->getFavoriteAddress()) {
                    $home = [
                        'home_address_id' => $address->getId(),
                        'Country' => $address->getCountry() ? $address->getCountry()->getName() : '',
                        'State' => $address->getState() ? $address->getState()->getName() : '',
                        'City' => $address->getCity() ? $address->getCity()->getName() : '',
                        'address' => $address->getStreet() . ' ' . $address->getNumberStreet() . ', ' . $address->getFloor() . ' ' . $address->getDepartment(),
                        'postal_code' => $address->getPostalCode(),
                        'aditional_info' => $address->getAdditionalInfo(),
                    ];
                }

                if ($address->getBillingAddress()) {
                    $bill = [
                        'bill_address_id' => $address->getId(),
                        'Country' => $address->getCountry() ? $address->getCountry()->getName() : '',
                        'State' => $address->getState() ? $address->getState()->getName() : '',
                        'City' => $address->getCity() ? $address->getCity()->getName() : '',
                        'address' => $address->getStreet() . ' ' . $address->getNumberStreet() . ', ' . $address->getFloor() . ' ' . $address->getDepartment(),
                        'postal_code' => $address->getPostalCode(),
                        'aditional_info' => $address->getAdditionalInfo(),
                    ];
                }
            }
        }


        return [
            'id' => (int) $this->getId(),
            'email' => $this->getEmail(),
            'name' => $this->getName(),
            'phone' => $this->getPhone() ? $this->getCountryPhoneCode()->getPhonecode() . ($this->getStateCodePhone() ? $this->getStateCodePhone() : '') . $this->getPhone() : '',
            'cel_phone' => $this->getCountryPhoneCode()->getPhonecode() . ($this->getStateCodePhone() ? $this->getStateCodePhone() : '') . $this->getCelPhone(),
            'customer_type' => $this->getCustomerTypeRole()->getName(),
            'status' => $this->getStatus()->getName(),
            'gender' => $this->getGenderType() ? $this->getGenderType()->getInitials() : '',
            'birth_day' => $this->getDateOfBirth() ? $this->getDateOfBirth()->format('Y-m-d') : '',
            'created_at' => $this->getRegistrationDate()->format('Y-m-d H:m:s'),
            'home_address' => @$home ? $home : '',
            'bill_address' => @$bill ? $bill : '',
        ];
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

    public function setRoles(string $roles): self
    {
        $this->roles = $roles;

        return $this;
    }

    public function getAttemptsSendCrm(): ?int
    {
        return $this->attempts_send_crm;
    }

    public function setAttemptsSendCrm(int $attempts_send_crm): self
    {
        $this->attempts_send_crm = $attempts_send_crm;

        return $this;
    }

    public function getErrorMessageCrm(): ?string
    {
        return $this->error_message_crm;
    }

    public function setErrorMessageCrm(?string $error_message_crm): self
    {
        $this->error_message_crm = $error_message_crm;

        return $this;
    }

    public function getStatusSentCrm(): ?CommunicationStatesBetweenPlatforms
    {
        return $this->status_sent_crm;
    }

    public function setStatusSentCrm(?CommunicationStatesBetweenPlatforms $status_sent_crm): self
    {
        $this->status_sent_crm = $status_sent_crm;

        return $this;
    }

    public function getVerificationCode()
    {
        return $this->verification_code;
    }

    public function setVerificationCode($verification_code): self
    {
        $this->verification_code = $verification_code;

        return $this;
    }

    public function getChangePassword(): ?bool
    {
        return $this->change_password;
    }

    public function setChangePassword(bool $change_password): self
    {
        $this->change_password = $change_password;

        return $this;
    }

    public function getChangePasswordDate(): ?\DateTimeInterface
    {
        return $this->change_password_date;
    }

    public function setChangePasswordDate(?\DateTimeInterface $change_password_date): self
    {
        $this->change_password_date = $change_password_date;

        return $this;
    }

    public function getStatus(): ?CustomerStatusType
    {
        return $this->status;
    }

    public function setStatus(?CustomerStatusType $status): self
    {
        $this->status = $status;

        return $this;
    }

    public function getCountryPhoneCode(): ?Countries
    {
        return $this->country_phone_code;
    }

    public function setCountryPhoneCode(?Countries $country_phone_code): self
    {
        $this->country_phone_code = $country_phone_code;

        return $this;
    }

    public function incrementAttemptsToSendCustomerToCrm()
    {
        $this->setAttemptsSendCrm($this->attempts_send_crm + 1); //you can access your entity values directly
    }
}
