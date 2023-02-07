<?php

namespace App\Entity;

use App\Repository\CustomerAddressesRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=CustomerAddressesRepository::class)
 */
class CustomerAddresses
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity=Customer::class, inversedBy="customerAddresses")
     * @ORM\JoinColumn(nullable=false)
     */
    private $customer;

    /**
     * @ORM\ManyToOne(targetEntity=Countries::class, inversedBy="customerAddresses")
     * @ORM\JoinColumn(nullable=false)
     */
    private $country;

    /**
     * @ORM\ManyToOne(targetEntity=States::class, inversedBy="customerAddresses")
     */
    private $state;

    /**
     * @ORM\ManyToOne(targetEntity=Cities::class, inversedBy="customerAddresses")
     */
    private $city;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $street;

    /**
     * @ORM\Column(type="string", length=255)
     */
    public $number_street;

    /**
     * @ORM\Column(type="string", length=10)
     */
    private $floor;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $department;

    /**
     * @ORM\Column(type="string", length=255)
     */
    public $postal_code;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $additional_info;

    /**
     * @ORM\Column(type="boolean")
     */
    private $home_address;

    /**
     * @ORM\Column(type="boolean")
     */
    private $billing_address;

    /**
     * @ORM\Column(type="datetime", options={"default":"CURRENT_TIMESTAMP"})
     */
    private $registration_date;

    /**
     * @ORM\ManyToOne(targetEntity=RegistrationType::class, inversedBy="customerAddresses")
     */
    private $registration_type;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="customerAddresses")
     */
    private $registration_user;

    /**
     * @ORM\Column(type="boolean", options={"default":true})
     */
    private $active;

    /**
     * @ORM\OneToMany(targetEntity=Orders::class, mappedBy="bill_address")
     */
    private $orders;

    public function __construct()
    {
        $this->registration_date = new \DateTime();
        $this->active = true;
        $this->orders = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCustomer(): ?customer
    {
        return $this->customer;
    }

    public function setCustomer(?customer $customer): self
    {
        $this->customer = $customer;

        return $this;
    }

    public function getStreet(): ?string
    {
        return $this->street;
    }

    public function setStreet(string $street): self
    {
        $this->street = $street;

        return $this;
    }

    public function getNumberStreet(): ?string
    {
        return $this->number_street;
    }

    public function setNumberStreet(string $number_street): self
    {
        $this->number_street = $number_street;

        return $this;
    }

    public function getFloor(): ?string
    {
        return $this->floor;
    }

    public function setFloor(string $floor): self
    {
        $this->floor = $floor;

        return $this;
    }

    public function getDepartment(): ?string
    {
        return $this->department;
    }

    public function setDepartment(string $department): self
    {
        $this->department = $department;

        return $this;
    }

    public function getPostalCode(): ?string
    {
        return $this->postal_code;
    }

    public function setPostalCode(string $postal_code): self
    {
        $this->postal_code = $postal_code;

        return $this;
    }

    public function getAdditionalInfo(): ?string
    {
        return $this->additional_info;
    }

    public function setAdditionalInfo(?string $additional_info): self
    {
        $this->additional_info = $additional_info;

        return $this;
    }

    public function getHomeAddress(): ?bool
    {
        return $this->home_address;
    }

    public function setHomeAddress(bool $home_address): self
    {
        $this->home_address = $home_address;

        return $this;
    }

    public function getBillingAddress(): ?bool
    {
        return $this->billing_address;
    }

    public function setBillingAddress(bool $billing_address): self
    {
        $this->billing_address = $billing_address;

        return $this;
    }

    public function getRegistrationDate(): ?\DateTimeInterface
    {
        return $this->registration_date;
    }

    public function setRegistrationDate(\DateTimeInterface $registration_date): self
    {
        $this->registration_date = $registration_date;

        return $this;
    }

    public function getRegistrationType(): ?registrationType
    {
        return $this->registration_type;
    }

    public function setRegistrationType(?registrationType $registration_type): self
    {
        $this->registration_type = $registration_type;

        return $this;
    }

    public function getRegistrationUser(): ?user
    {
        return $this->registration_user;
    }

    public function setRegistrationUser(?user $registration_user): self
    {
        $this->registration_user = $registration_user;

        return $this;
    }

    public function getActive(): ?bool
    {
        return $this->active;
    }

    public function setActive(bool $active): self
    {
        $this->active = $active;

        return $this;
    }

    public function getCountry(): ?Countries
    {
        return $this->country;
    }

    public function setCountry(?Countries $country): self
    {
        $this->country = $country;

        return $this;
    }

    public function getState(): ?States
    {
        return $this->state;
    }

    public function setState(?States $state): self
    {
        $this->state = $state;

        return $this;
    }

    public function getCity(): ?Cities
    {
        return $this->city;
    }

    public function setCity(?Cities $city): self
    {
        $this->city = $city;

        return $this;
    }

    public function getTotalCustomerAddressInfo()
    {
        return [
            'customer_id' => $this->getCustomer()->getId(),
            'customer_address_id' => $this->getId(),
            'country_id' => $this->getCountry()->getId(),
            'country_name' => $this->getCountry()->getName(),
            'state_id' => $this->getState() ? $this->getState()->getId() : null,
            'state_name' => $this->getState() ? $this->getState()->getName() : null,
            'city_id' => $this->getCity() ? $this->getCity()->getId() : null,
            'city_name' => $this->getCity() ? $this->getCity()->getName() : null,
            'street' => $this->getStreet(),
            'number_street' => $this->getNumberStreet(),
            'floor' => $this->getFloor(),
            'department' => $this->getDepartment(),
            'postal_code' => $this->getPostalCode(),
            'additional_info' => $this->getAdditionalInfo(),
            'active' => $this->getActive(),
            'registration_date' => $this->getRegistrationDate()->format('Y-m-d H:m:s'),
            'home_address' => $this->getHomeAddress(),
            'billing_address' => $this->getBillingAddress()
        ];
    }

    /**
     * @return Collection<int, Orders>
     */
    public function getOrders(): Collection
    {
        return $this->orders;
    }

    public function addOrder(Orders $order): self
    {
        if (!$this->orders->contains($order)) {
            $this->orders[] = $order;
            $order->setBillAddress($this);
        }

        return $this;
    }

    public function removeOrder(Orders $order): self
    {
        if ($this->orders->removeElement($order)) {
            // set the owning side to null (unless already changed)
            if ($order->getBillAddress() === $this) {
                $order->setBillAddress(null);
            }
        }

        return $this;
    }
}
