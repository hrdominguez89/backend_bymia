<?php

namespace App\Entity;

use App\Entity\Model\User as BaseUser;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\UserRepository")
 * @ORM\Table("mia_user")
 */
class User extends BaseUser
{
    /**
     * @ORM\Column(type="string")
     */
    protected $roles = [];

    /**
     * @var string|null
     *
     * @ORM\Column(name="password", type="string", length=512, nullable=true)
     */
    protected $password;

    /**
     * @ORM\ManyToOne(targetEntity=Roles::class, inversedBy="users")
     */
    private $role;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $lastname;

    /**
     * @ORM\OneToMany(targetEntity=Customer::class, mappedBy="registration_user")
     */
    private $customers;

    /**
     * @ORM\OneToMany(targetEntity=CustomerAddresses::class, mappedBy="registration_user")
     */
    private $customerAddresses;

    /**
     * @ORM\OneToMany(targetEntity=HistoricalPriceCost::class, mappedBy="created_by_user")
     */
    private $historicalPriceCosts;

    /**
     * @ORM\OneToMany(targetEntity=ProductDiscount::class, mappedBy="created_by_user")
     */
    private $productDiscounts;

    public function __construct()
    {
        parent::__construct();
        $this->customers = new ArrayCollection();
        $this->customerAddresses = new ArrayCollection();
        $this->historicalPriceCosts = new ArrayCollection();
        $this->productDiscounts = new ArrayCollection();
    }

    /**
     * @return array
     */
    public function getRoles(): array
    {
        return array_unique([$this->role->getRole()]);
    }

    public function getRole(): ?Roles
    {
        return $this->role;
    }

    public function setRole(?Roles $role): self
    {
        $this->role = $role;

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

    public function setRoles(array $roles): self
    {
        $this->roles = $roles;

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
     * @return Collection|Customer[]
     */
    public function getCustomers(): Collection
    {
        return $this->customers;
    }

    public function addCustomer(Customer $customer): self
    {
        if (!$this->customers->contains($customer)) {
            $this->customers[] = $customer;
            $customer->setRegistrationUser($this);
        }

        return $this;
    }

    public function removeCustomer(Customer $customer): self
    {
        if ($this->customers->removeElement($customer)) {
            // set the owning side to null (unless already changed)
            if ($customer->getRegistrationUser() === $this) {
                $customer->setRegistrationUser(null);
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
            $customerAddress->setRegistrationUser($this);
        }

        return $this;
    }

    public function removeCustomerAddress(CustomerAddresses $customerAddress): self
    {
        if ($this->customerAddresses->removeElement($customerAddress)) {
            // set the owning side to null (unless already changed)
            if ($customerAddress->getRegistrationUser() === $this) {
                $customerAddress->setRegistrationUser(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, HistoricalPriceCost>
     */
    public function getHistoricalPriceCosts(): Collection
    {
        return $this->historicalPriceCosts;
    }

    public function addHistoricalPriceCost(HistoricalPriceCost $historicalPriceCost): self
    {
        if (!$this->historicalPriceCosts->contains($historicalPriceCost)) {
            $this->historicalPriceCosts[] = $historicalPriceCost;
            $historicalPriceCost->setCreatedByUser($this);
        }

        return $this;
    }

    public function removeHistoricalPriceCost(HistoricalPriceCost $historicalPriceCost): self
    {
        if ($this->historicalPriceCosts->removeElement($historicalPriceCost)) {
            // set the owning side to null (unless already changed)
            if ($historicalPriceCost->getCreatedByUser() === $this) {
                $historicalPriceCost->setCreatedByUser(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, ProductDiscount>
     */
    public function getProductDiscounts(): Collection
    {
        return $this->productDiscounts;
    }

    public function addProductDiscount(ProductDiscount $productDiscount): self
    {
        if (!$this->productDiscounts->contains($productDiscount)) {
            $this->productDiscounts[] = $productDiscount;
            $productDiscount->setCreatedByUser($this);
        }

        return $this;
    }

    public function removeProductDiscount(ProductDiscount $productDiscount): self
    {
        if ($this->productDiscounts->removeElement($productDiscount)) {
            // set the owning side to null (unless already changed)
            if ($productDiscount->getCreatedByUser() === $this) {
                $productDiscount->setCreatedByUser(null);
            }
        }

        return $this;
    }
}
