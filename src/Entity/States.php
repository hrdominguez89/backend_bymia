<?php

namespace App\Entity;

use App\Repository\StatesRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=StatesRepository::class)
 */
class States
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
    private $name;

    /**
     * @ORM\ManyToOne(targetEntity=Countries::class, inversedBy="states")
     * @ORM\JoinColumn(nullable=false)
     */
    private $country;

    /**
     * @ORM\Column(type="string", length=2, nullable=true)
     */
    private $country_code;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $fips_code;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $iso2;

    /**
     * @ORM\Column(type="string", length=191, nullable=true)
     */
    private $type;

    /**
     * @ORM\Column(type="decimal", precision=10, scale=8, nullable=true)
     */
    private $latitude;

    /**
     * @ORM\Column(type="decimal", precision=11, scale=8, nullable=true)
     */
    private $longitude;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $created_at;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $updated_at;

    /**
     * @ORM\Column(type="smallint", nullable=true)
     */
    private $flag;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $wikiDataId;

    /**
     * @ORM\OneToMany(targetEntity=Cities::class, mappedBy="state")
     */
    private $cities;

    /**
     * @ORM\OneToMany(targetEntity=CustomerAddresses::class, mappedBy="state")
     */
    private $customerAddresses;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $visible;

    /**
     * @ORM\OneToMany(targetEntity=Orders::class, mappedBy="bill_state")
     */
    private $orders;

    /**
     * @ORM\OneToMany(targetEntity=Orders::class, mappedBy="receiver_state")
     */
    private $receiver_orders;

    /**
     * @ORM\OneToMany(targetEntity=Recipients::class, mappedBy="state")
     */
    private $recipients;

    public function __construct()
    {
        $this->cities = new ArrayCollection();
        $this->customerAddresses = new ArrayCollection();
        $this->orders = new ArrayCollection();
        $this->receiver_orders = new ArrayCollection();
        $this->recipients = new ArrayCollection();
    }

    public function __toString()
    {
        return $this->name;
    }

    public function getId(): ?int
    {
        return $this->id;
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

    public function getCountry(): ?Countries
    {
        return $this->country;
    }

    public function setCountry(?Countries $country): self
    {
        $this->country = $country;

        return $this;
    }

    public function getCountryCode(): ?string
    {
        return $this->country_code;
    }

    public function setCountryCode(string $country_code): self
    {
        $this->country_code = $country_code;

        return $this;
    }

    public function getFipsCode(): ?string
    {
        return $this->fips_code;
    }

    public function setFipsCode(?string $fips_code): self
    {
        $this->fips_code = $fips_code;

        return $this;
    }

    public function getIso2(): ?string
    {
        return $this->iso2;
    }

    public function setIso2(?string $iso2): self
    {
        $this->iso2 = $iso2;

        return $this;
    }

    public function getType(): ?string
    {
        return $this->type;
    }

    public function setType(?string $type): self
    {
        $this->type = $type;

        return $this;
    }

    public function getLatitude(): ?string
    {
        return $this->latitude;
    }

    public function setLatitude(?string $latitude): self
    {
        $this->latitude = $latitude;

        return $this;
    }

    public function getLongitude(): ?string
    {
        return $this->longitude;
    }

    public function setLongitude(?string $longitude): self
    {
        $this->longitude = $longitude;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeInterface
    {
        return $this->created_at;
    }

    public function setCreatedAt(?\DateTimeInterface $created_at): self
    {
        $this->created_at = $created_at;

        return $this;
    }

    public function getUpdatedAt(): ?\DateTimeInterface
    {
        return $this->updated_at;
    }

    public function setUpdatedAt(?\DateTimeInterface $updated_at): self
    {
        $this->updated_at = $updated_at;

        return $this;
    }

    public function getFlag(): ?int
    {
        return $this->flag;
    }

    public function setFlag(int $flag): self
    {
        $this->flag = $flag;

        return $this;
    }

    public function getWikiDataId(): ?string
    {
        return $this->wikiDataId;
    }

    public function setWikiDataId(?string $wikiDataId): self
    {
        $this->wikiDataId = $wikiDataId;

        return $this;
    }

    /**
     * @return Collection|Cities[]
     */
    public function getCities(): Collection
    {
        return $this->cities;
    }

    public function addCity(Cities $city): self
    {
        if (!$this->cities->contains($city)) {
            $this->cities[] = $city;
            $city->setState($this);
        }

        return $this;
    }

    public function removeCity(Cities $city): self
    {
        if ($this->cities->removeElement($city)) {
            // set the owning side to null (unless already changed)
            if ($city->getState() === $this) {
                $city->setState(null);
            }
        }

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
            $customerAddress->setState($this);
        }

        return $this;
    }

    public function removeCustomerAddress(CustomerAddresses $customerAddress): self
    {
        if ($this->customerAddresses->removeElement($customerAddress)) {
            // set the owning side to null (unless already changed)
            if ($customerAddress->getState() === $this) {
                $customerAddress->setState(null);
            }
        }

        return $this;
    }

    public function getVisible(): ?bool
    {
        return $this->visible;
    }

    public function setVisible(?bool $visible): self
    {
        $this->visible = $visible;

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
            $order->setBillState($this);
        }

        return $this;
    }

    public function removeOrder(Orders $order): self
    {
        if ($this->orders->removeElement($order)) {
            // set the owning side to null (unless already changed)
            if ($order->getBillState() === $this) {
                $order->setBillState(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Orders>
     */
    public function getReceiverOrders(): Collection
    {
        return $this->receiver_orders;
    }

    public function addReceiverOrder(Orders $receiverOrder): self
    {
        if (!$this->receiver_orders->contains($receiverOrder)) {
            $this->receiver_orders[] = $receiverOrder;
            $receiverOrder->setReceiverState($this);
        }

        return $this;
    }

    public function removeReceiverOrder(Orders $receiverOrder): self
    {
        if ($this->receiver_orders->removeElement($receiverOrder)) {
            // set the owning side to null (unless already changed)
            if ($receiverOrder->getReceiverState() === $this) {
                $receiverOrder->setReceiverState(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Recipients>
     */
    public function getRecipients(): Collection
    {
        return $this->recipients;
    }

    public function addRecipient(Recipients $recipient): self
    {
        if (!$this->recipients->contains($recipient)) {
            $this->recipients[] = $recipient;
            $recipient->setState($this);
        }

        return $this;
    }

    public function removeRecipient(Recipients $recipient): self
    {
        if ($this->recipients->removeElement($recipient)) {
            // set the owning side to null (unless already changed)
            if ($recipient->getState() === $this) {
                $recipient->setState(null);
            }
        }

        return $this;
    }
}
