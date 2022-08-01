<?php

namespace App\Entity\Model;

use App\Helpers\CountriesTrait;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Validator\Constraints as Assert;

abstract class Order
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="bigint")
     */
    protected $id;

    /** BILLING INFO */

    /**
     * @var string
     *
     * @ORM\Column(name="checkout_b_first_name", type="string", length=100)
     * @Assert\Length(min=2, max=100)
     * @Assert\NotBlank()
     */
    protected $checkoutBillingFirstName;

    /**
     * @var string
     *
     * @ORM\Column(name="checkout_b_last_name", type="string", length=100)
     * @Assert\Length(min=2, max=100)
     * @Assert\NotBlank()
     */
    protected $checkoutBillingLastName;

    /**
     * @var string|null
     *
     * @ORM\Column(name="checkout_b_company_name", type="string", length=255, nullable=true)
     * @Assert\Length(max=100)
     */
    protected $checkoutBillingCompanyName;

    /**
     * @var string
     *
     * @ORM\Column(name="checkout_b_country_iso", type="string", length=10)
     * @Assert\Length(min=2, max=10)
     * @Assert\NotBlank()
     */
    protected $checkoutBillingCountryIso;

    /**
     * @var string
     *
     * @ORM\Column(name="checkout_b_country", type="string", length=100)
     * @Assert\Length(min=2, max=100)
     * @Assert\NotBlank()
     */
    protected $checkoutBillingCountry;

    /**
     * @var string
     *
     * @ORM\Column(name="checkout_b_street_address", type="string", length=500)
     * @Assert\Length(min=2, max=500)
     * @Assert\NotBlank()
     */
    protected $checkoutBillingStreetAddress;

    /**
     * @var string|null
     *
     * @ORM\Column(name="checkout_b_address", type="string", length=255, nullable=true)
     * @Assert\Length(max=255)
     */
    protected $checkoutBillingAddress;

    /**
     * @var string
     *
     * @ORM\Column(name="checkout_b_city", type="string", length=100)
     * @Assert\Length(min=2, max=100)
     * @Assert\NotBlank()
     */
    protected $checkoutBillingCity;

    /**
     * @var string|null
     *
     * @ORM\Column(name="checkout_b_state", type="string", length=100)
     * @Assert\Length(min=2, max=100)
     * @Assert\NotBlank()
     */
    protected $checkoutBillingState;

    /**
     * @var string
     *
     * @ORM\Column(name="checkout_b_postcode", type="string", length=50)
     * @Assert\Length(min=2, max=50)
     * @Assert\NotBlank()
     */
    protected $checkoutBillingPostcode;

    /**
     * @var string
     *
     * @ORM\Column(name="checkout_b_email", type="string", length=100)
     * @Assert\Length(min=5, max=100)
     * @Assert\Email(mode="strict")
     * @Assert\NotBlank()
     */
    protected $checkoutBillingEmail;

    /**
     * @var string
     *
     * @ORM\Column(name="checkout_b_phone", type="string", length=100)
     * @Assert\Length(min=2, max=100)
     * @Assert\NotBlank()
     */
    protected $checkoutBillingPhone;

    /** END BILLING INFO */

    /** SHIPPING INFO */

    /**
     * @var string
     *
     * @ORM\Column(name="checkout_s_first_name", type="string", length=100)
     * @Assert\Length(min=2, max=100)
     * @Assert\NotBlank()
     */
    protected $checkoutShippingFirstName;

    /**
     * @var string
     *
     * @ORM\Column(name="checkout_s_last_name", type="string", length=100)
     * @Assert\Length(min=2, max=100)
     * @Assert\NotBlank()
     */
    protected $checkoutShippingLastName;

    /**
     * @var string|null
     *
     * @ORM\Column(name="checkout_s_company_name", type="string", length=255, nullable=true)
     * @Assert\Length(max=100)
     */
    protected $checkoutShippingCompanyName;

    /**
     * @var string
     *
     * @ORM\Column(name="checkout_s_country_iso", type="string", length=10)
     * @Assert\Length(min=2, max=10)
     * @Assert\NotBlank()
     */
    protected $checkoutShippingCountryIso;

    /**
     * @var string
     *
     * @ORM\Column(name="checkout_s_country", type="string", length=100)
     * @Assert\Length(min=2, max=100)
     * @Assert\NotBlank()
     */
    protected $checkoutShippingCountry;

    /**
     * @var string
     *
     * @ORM\Column(name="checkout_s_street_address", type="string", length=500)
     * @Assert\Length(min=2, max=500)
     * @Assert\NotBlank()
     */
    protected $checkoutShippingStreetAddress;

    /**
     * @var string|null
     *
     * @ORM\Column(name="checkout_s_address", type="string", length=255, nullable=true)
     * @Assert\Length(max=255)
     */
    protected $checkoutShippingAddress;

    /**
     * @var string
     *
     * @ORM\Column(name="checkout_s_city", type="string", length=100)
     * @Assert\Length(min=2, max=100)
     * @Assert\NotBlank()
     */
    protected $checkoutShippingCity;

    /**
     * @var string|null
     *
     * @ORM\Column(name="checkout_s_state", type="string", length=100)
     * @Assert\Length(min=2, max=100)
     * @Assert\NotBlank()
     */
    protected $checkoutShippingState;

    /**
     * @var string
     *
     * @ORM\Column(name="checkout_s_postcode", type="string", length=50)
     * @Assert\Length(min=2, max=50)
     * @Assert\NotBlank()
     */
    protected $checkoutShippingPostcode;

    /**
     * @var string
     *
     * @ORM\Column(name="checkout_s_email", type="string", length=100)
     * @Assert\Length(min=5, max=100)
     * @Assert\Email(mode="strict")
     * @Assert\NotBlank()
     */
    protected $checkoutShippingEmail;

    /**
     * @var string
     *
     * @ORM\Column(name="checkout_s_phone", type="string", length=100)
     * @Assert\Length(min=2, max=100)
     * @Assert\NotBlank()
     */
    protected $checkoutShippingPhone;

    /**
     * @var string|null
     *
     * @ORM\Column(name="checkout_comment", type="string", length=255, nullable=true)
     * @Assert\Length(max=255)
     */
    protected $checkoutComment;

    /**
     * @var bool
     *
     * @ORM\Column(name="different_address", type="boolean")
     */
    protected $differentAddress;

    /** END SHIPPING INFO */

    public function __construct(Request $request)
    {

        $this->differentAddress = filter_var($request->get('checkout_different_address', false), FILTER_VALIDATE_BOOLEAN);
        $this->checkoutComment = $request->get('checkout_comment', '');

        $this->setCheckoutBillingFirstName($request->get('checkout_b_first_name', ''));
        $this->setCheckoutBillingLastName($request->get('checkout_b_last_name', ''));
        $this->setCheckoutBillingCompanyName($request->get('checkout_b_company_name', ''));
        $this->setCheckoutBillingCountryIso($request->get('checkout_b_country', ''));
        $this->setCheckoutBillingStreetAddress($request->get('checkout_b_street_address', ''));
        $this->setCheckoutBillingAddress($request->get('checkout_b_address', ''));
        $this->setCheckoutBillingCity($request->get('checkout_b_city', ''));
        $this->setCheckoutBillingState($request->get('checkout_b_state', ''));
        $this->setCheckoutBillingPostcode($request->get('checkout_b_postcode', ''));
        $this->setCheckoutBillingEmail($request->get('checkout_b_email', ''));
        $this->setCheckoutBillingPhone($request->get('checkout_b_phone', ''));

        if ($this->differentAddress) {

            $this->setCheckoutShippingFirstName($request->get('checkout_s_first_name', ''));
            $this->setCheckoutShippingLastName($request->get('checkout_s_last_name', ''));
            $this->setCheckoutShippingCompanyName($request->get('checkout_s_company_name', ''));
            $this->setCheckoutShippingCountryIso($request->get('checkout_s_country', ''));
            $this->setCheckoutShippingStreetAddress($request->get('checkout_s_street_address', ''));
            $this->setCheckoutShippingAddress($request->get('checkout_s_address', ''));
            $this->setCheckoutShippingCity($request->get('checkout_s_city', ''));
            $this->setCheckoutShippingState($request->get('checkout_s_state', ''));
            $this->setCheckoutShippingPostcode($request->get('checkout_s_postcode', ''));
            $this->setCheckoutShippingEmail($request->get('checkout_s_email', ''));
            $this->setCheckoutShippingPhone($request->get('checkout_s_phone', ''));

        }

    }


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
    public function isDifferentAddress(): bool
    {
        return $this->differentAddress;
    }

    /**
     * @param bool $differentAddress
     * @return $this
     */
    public function setDifferentAddress(bool $differentAddress): Order
    {
        $this->differentAddress = $differentAddress;

        return $this;
    }

    /**
     * @return string
     */
    public function getCheckoutBillingFirstName(): string
    {
        return $this->checkoutBillingFirstName;
    }

    /**
     * @param string $checkoutBillingFirstName
     * @return $this
     */
    public function setCheckoutBillingFirstName(string $checkoutBillingFirstName): Order
    {
        $this->checkoutBillingFirstName = $checkoutBillingFirstName;

        if (!$this->differentAddress) {
            $this->checkoutShippingFirstName = $checkoutBillingFirstName;
        }

        return $this;
    }

    /**
     * @return string
     */
    public function getCheckoutBillingLastName(): string
    {
        return $this->checkoutBillingLastName;
    }

    /**
     * @param string $checkoutBillingLastName
     * @return $this
     */
    public function setCheckoutBillingLastName(string $checkoutBillingLastName): Order
    {
        $this->checkoutBillingLastName = $checkoutBillingLastName;

        if (!$this->differentAddress) {
            $this->checkoutShippingLastName = $checkoutBillingLastName;
        }

        return $this;
    }

    /**
     * @return string|null
     */
    public function getCheckoutBillingCompanyName(): ?string
    {
        return $this->checkoutBillingCompanyName;
    }

    /**
     * @param string|null $checkoutBillingCompanyName
     * @return $this
     */
    public function setCheckoutBillingCompanyName(?string $checkoutBillingCompanyName): Order
    {
        $this->checkoutBillingCompanyName = $checkoutBillingCompanyName;

        if (!$this->differentAddress) {
            $this->checkoutShippingCompanyName = $checkoutBillingCompanyName;
        }

        return $this;
    }

    /**
     * @return string
     */
    public function getCheckoutBillingCountryIso(): string
    {
        return $this->checkoutBillingCountryIso;
    }

    // /**
    //  * @param string $checkoutBillingCountryIso
    //  * @return $this
    //  */
    // public function setCheckoutBillingCountryIso(string $checkoutBillingCountryIso): Order
    // {
    //     [$this->checkoutBillingCountryIso, $this->checkoutBillingCountry] = $this->findCountryByISO($checkoutBillingCountryIso);

    //     if (!$this->differentAddress) {
    //         $this->setCheckoutShippingCountryIso($checkoutBillingCountryIso);
    //     }

    //     return $this;
    // }

    /**
     * @return string
     */
    public function getCheckoutBillingCountry(): string
    {
        return $this->checkoutBillingCountry;
    }

    /**
     * @param $checkoutBillingCountry
     * @return $this
     */
    public function setCheckoutBillingCountry($checkoutBillingCountry): Order
    {
        $this->checkoutBillingCountry = $checkoutBillingCountry;

        if (!$this->differentAddress) {
            $this->checkoutShippingCountry = $checkoutBillingCountry;
        }

        return $this;
    }

    /**
     * @return string
     */
    public function getCheckoutBillingStreetAddress(): string
    {
        return $this->checkoutBillingStreetAddress;
    }

    /**
     * @param string $checkoutBillingStreetAddress
     * @return $this
     */
    public function setCheckoutBillingStreetAddress(string $checkoutBillingStreetAddress): Order
    {
        $this->checkoutBillingStreetAddress = $checkoutBillingStreetAddress;

        if (!$this->differentAddress) {
            $this->checkoutShippingStreetAddress = $checkoutBillingStreetAddress;
        }

        return $this;
    }

    /**
     * @return string|null
     */
    public function getCheckoutBillingAddress(): ?string
    {
        return $this->checkoutBillingAddress;
    }

    /**
     * @param string|null $checkoutBillingAddress
     * @return $this
     */
    public function setCheckoutBillingAddress(?string $checkoutBillingAddress): Order
    {
        $this->checkoutBillingAddress = $checkoutBillingAddress;

        if (!$this->differentAddress) {
            $this->checkoutShippingAddress = $checkoutBillingAddress;
        }

        return $this;
    }

    /**
     * @return string
     */
    public function getCheckoutBillingCity(): string
    {
        return $this->checkoutBillingCity;
    }

    /**
     * @param string $checkoutBillingCity
     * @return $this
     */
    public function setCheckoutBillingCity(string $checkoutBillingCity): Order
    {
        $this->checkoutBillingCity = $checkoutBillingCity;

        if (!$this->differentAddress) {
            $this->checkoutShippingCity = $checkoutBillingCity;
        }

        return $this;
    }

    /**
     * @return string|null
     */
    public function getCheckoutBillingState(): ?string
    {
        return $this->checkoutBillingState;
    }

    /**
     * @param string|null $checkoutBillingState
     * @return $this
     */
    public function setCheckoutBillingState(?string $checkoutBillingState): Order
    {
        $this->checkoutBillingState = $checkoutBillingState;

        if (!$this->differentAddress) {
            $this->checkoutShippingState = $checkoutBillingState;
        }

        return $this;
    }

    /**
     * @return string
     */
    public function getCheckoutBillingPostcode(): string
    {
        return $this->checkoutBillingPostcode;
    }

    /**
     * @param string $checkoutBillingPostcode
     * @return $this
     */
    public function setCheckoutBillingPostcode(string $checkoutBillingPostcode): Order
    {
        $this->checkoutBillingPostcode = $checkoutBillingPostcode;

        if (!$this->differentAddress) {
            $this->checkoutShippingPostcode = $checkoutBillingPostcode;
        }

        return $this;
    }

    /**
     * @return string
     */
    public function getCheckoutBillingEmail(): string
    {
        return $this->checkoutBillingEmail;
    }

    /**
     * @param string $checkoutBillingEmail
     * @return $this
     */
    public function setCheckoutBillingEmail(string $checkoutBillingEmail): Order
    {
        $this->checkoutBillingEmail = $checkoutBillingEmail;

        if (!$this->differentAddress) {
            $this->checkoutShippingEmail = $checkoutBillingEmail;
        }

        return $this;
    }

    /**
     * @return string
     */
    public function getCheckoutBillingPhone(): string
    {
        return $this->checkoutBillingPhone;
    }

    /**
     * @param string $checkoutBillingPhone
     * @return $this
     */
    public function setCheckoutBillingPhone(string $checkoutBillingPhone): Order
    {
        $this->checkoutBillingPhone = $checkoutBillingPhone;

        if (!$this->differentAddress) {
            $this->checkoutShippingPhone = $checkoutBillingPhone;
        }

        return $this;
    }

    /**
     * @return string|null
     */
    public function getCheckoutComment(): ?string
    {
        return $this->checkoutComment;
    }

    /**
     * @param string|null $checkoutComment
     * @return $this
     */
    public function setCheckoutComment(?string $checkoutComment): Order
    {
        $this->checkoutComment = $checkoutComment;

        return $this;
    }

    /**
     * @return string
     */
    public function getCheckoutShippingFirstName(): string
    {
        return $this->checkoutShippingFirstName;
    }

    /**
     * @param string $checkoutShippingFirstName
     * @return $this
     */
    public function setCheckoutShippingFirstName(string $checkoutShippingFirstName): Order
    {
        $this->checkoutShippingFirstName = $checkoutShippingFirstName;

        return $this;
    }

    /**
     * @return string
     */
    public function getCheckoutShippingLastName(): string
    {
        return $this->checkoutShippingLastName;
    }

    /**
     * @param string $checkoutShippingLastName
     * @return $this
     */
    public function setCheckoutShippingLastName(string $checkoutShippingLastName): Order
    {
        $this->checkoutShippingLastName = $checkoutShippingLastName;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getCheckoutShippingCompanyName(): ?string
    {
        return $this->checkoutShippingCompanyName;
    }

    /**
     * @param string|null $checkoutShippingCompanyName
     * @return $this
     */
    public function setCheckoutShippingCompanyName(?string $checkoutShippingCompanyName): Order
    {
        $this->checkoutShippingCompanyName = $checkoutShippingCompanyName;

        return $this;
    }

    /**
     * @return string
     */
    public function getCheckoutShippingCountryIso(): string
    {
        return $this->checkoutShippingCountryIso;
    }

    // /**
    //  * @param string $checkoutShippingCountryIso
    //  * @return $this
    //  */
    // public function setCheckoutShippingCountryIso(string $checkoutShippingCountryIso): Order
    // {
    //     [$this->checkoutShippingCountryIso, $this->checkoutShippingCountry] = $this->findCountryByISO($checkoutShippingCountryIso);

    //     return $this;
    // }

    /**
     * @return string
     */
    public function getCheckoutShippingCountry(): string
    {
        return $this->checkoutShippingCountry;
    }

    /**
     * @param $checkoutShippingCountry
     * @return $this
     */
    public function setCheckoutShippingCountry($checkoutShippingCountry): Order
    {
        $this->checkoutShippingCountry = $checkoutShippingCountry;

        return $this;
    }

    /**
     * @return string
     */
    public function getCheckoutShippingStreetAddress(): string
    {
        return $this->checkoutShippingStreetAddress;
    }

    /**
     * @param string $checkoutShippingStreetAddress
     * @return $this
     */
    public function setCheckoutShippingStreetAddress(string $checkoutShippingStreetAddress): Order
    {
        $this->checkoutShippingStreetAddress = $checkoutShippingStreetAddress;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getCheckoutShippingAddress(): ?string
    {
        return $this->checkoutShippingAddress;
    }

    /**
     * @param string|null $checkoutShippingAddress
     * @return $this
     */
    public function setCheckoutShippingAddress(?string $checkoutShippingAddress): Order
    {
        $this->checkoutShippingAddress = $checkoutShippingAddress;

        return $this;
    }

    /**
     * @return string
     */
    public function getCheckoutShippingCity(): string
    {
        return $this->checkoutShippingCity;
    }

    /**
     * @param string $checkoutShippingCity
     * @return $this
     */
    public function setCheckoutShippingCity(string $checkoutShippingCity): Order
    {
        $this->checkoutShippingCity = $checkoutShippingCity;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getCheckoutShippingState(): ?string
    {
        return $this->checkoutShippingState;
    }

    /**
     * @param string|null $checkoutShippingState
     * @return $this
     */
    public function setCheckoutShippingState(?string $checkoutShippingState): Order
    {
        $this->checkoutShippingState = $checkoutShippingState;

        return $this;
    }

    /**
     * @return string
     */
    public function getCheckoutShippingPostcode(): string
    {
        return $this->checkoutShippingPostcode;
    }

    /**
     * @param string $checkoutShippingPostcode
     * @return $this
     */
    public function setCheckoutShippingPostcode(string $checkoutShippingPostcode): Order
    {
        $this->checkoutShippingPostcode = $checkoutShippingPostcode;

        return $this;
    }

    /**
     * @return string
     */
    public function getCheckoutShippingEmail(): string
    {
        return $this->checkoutShippingEmail;
    }

    /**
     * @param string $checkoutShippingEmail
     * @return $this
     */
    public function setCheckoutShippingEmail(string $checkoutShippingEmail): Order
    {
        $this->checkoutShippingEmail = $checkoutShippingEmail;

        return $this;
    }

    /**
     * @return string
     */
    public function getCheckoutShippingPhone(): string
    {
        return $this->checkoutShippingPhone;
    }

    /**
     * @param string $checkoutShippingPhone
     * @return $this
     */
    public function setCheckoutShippingPhone(string $checkoutShippingPhone): Order
    {
        $this->checkoutShippingPhone = $checkoutShippingPhone;

        return $this;
    }

}
