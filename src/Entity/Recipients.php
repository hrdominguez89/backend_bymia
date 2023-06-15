<?php

namespace App\Entity;

use App\Repository\RecipientsRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=RecipientsRepository::class)
 */
class Recipients
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity=Customer::class, inversedBy="recipients")
     * @ORM\JoinColumn(nullable=false)
     */
    private $customer;

    /**
     * @ORM\ManyToOne(targetEntity=Countries::class, inversedBy="recipients")
     * @ORM\JoinColumn(nullable=false)
     */
    private $country;

    /**
     * @ORM\ManyToOne(targetEntity=States::class, inversedBy="recipients")
     * @ORM\JoinColumn(nullable=false)
     */
    private $state;

    /**
     * @ORM\ManyToOne(targetEntity=Cities::class, inversedBy="recipients")
     * @ORM\JoinColumn(nullable=false)
     */
    private $city;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $name;

    /**
     * @ORM\Column(type="string", length=50)
     */
    private $identity_type;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $identity_number;

    /**
     * @ORM\Column(type="text")
     */
    private $address;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $zip_code;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $phone;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $email;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $additional_info;

    /**
     * @ORM\OneToMany(targetEntity=Orders::class, mappedBy="recipient")
     */
    private $orders;

    public function __construct()
    {
        $this->orders = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCustomer(): ?Customer
    {
        return $this->customer;
    }

    public function setCustomer(?Customer $customer): self
    {
        $this->customer = $customer;

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

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getIdentityType(): ?string
    {
        return $this->identity_type;
    }

    public function setIdentityType(string $identity_type): self
    {
        $this->identity_type = $identity_type;

        return $this;
    }

    public function getIdentityNumber(): ?string
    {
        return $this->identity_number;
    }

    public function setIdentityNumber(string $identity_number): self
    {
        $this->identity_number = $identity_number;

        return $this;
    }

    public function getAddress(): ?string
    {
        return $this->address;
    }

    public function setAddress(string $address): self
    {
        $this->address = $address;

        return $this;
    }

    public function getZipCode(): ?string
    {
        return $this->zip_code;
    }

    public function setZipCode(string $zip_code): self
    {
        $this->zip_code = $zip_code;

        return $this;
    }

    public function getPhone(): ?string
    {
        return $this->phone;
    }

    public function setPhone(string $phone): self
    {
        $this->phone = $phone;

        return $this;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;

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
            $order->setRecipient($this);
        }

        return $this;
    }

    public function removeOrder(Orders $order): self
    {
        if ($this->orders->removeElement($order)) {
            // set the owning side to null (unless already changed)
            if ($order->getRecipient() === $this) {
                $order->setRecipient(null);
            }
        }

        return $this;
    }
}
