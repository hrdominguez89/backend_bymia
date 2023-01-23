<?php

namespace App\Entity;

use App\Repository\OrdersRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=OrdersRepository::class)
 */
class Orders
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity=Customer::class, inversedBy="orders")
     * @ORM\JoinColumn(nullable=false)
     */
    private $customer;

    /**
     * @ORM\ManyToOne(targetEntity=CustomersTypesRoles::class, inversedBy="orders")
     * @ORM\JoinColumn(nullable=false)
     */
    private $customer_type;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $customer_name;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $customer_email;

    /**
     * @ORM\ManyToOne(targetEntity=Countries::class, inversedBy="orders")
     * @ORM\JoinColumn(nullable=false)
     */
    private $customer_phone_code;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $cel_phone_customer;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $phone_customer;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $customer_identity_type;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $customer_identity_number;

    /**
     * @ORM\Column(type="boolean")
     */
    private $international_shipping;

    /**
     * @ORM\Column(type="boolean")
     */
    private $shipping;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $bill_file;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $payment_file;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $payment_received_file;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $debit_credit_note_file;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $paypal_transaction_code;

    /**
     * @ORM\ManyToOne(targetEntity=CustomerAddresses::class, inversedBy="orders")
     * @ORM\JoinColumn(nullable=false)
     */
    private $bill_address_id;

    /**
     * @ORM\ManyToOne(targetEntity=Countries::class, inversedBy="orders")
     * @ORM\JoinColumn(nullable=false)
     */
    private $biil_country;

    /**
     * @ORM\ManyToOne(targetEntity=States::class, inversedBy="orders")
     */
    private $bill_state;

    /**
     * @ORM\ManyToOne(targetEntity=Cities::class, inversedBy="orders")
     */
    private $bill_city;

    /**
     * @ORM\Column(type="text")
     */
    private $bill_address;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $bill_postal_code;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $bill_additional_info;

    /**
     * @ORM\Column(type="float")
     */
    private $subtotal;

    /**
     * @ORM\Column(type="float")
     */
    private $product_discount;

    /**
     * @ORM\Column(type="float")
     */
    private $promotional_code_discount;

    /**
     * @ORM\Column(type="float")
     */
    private $tax;

    /**
     * @ORM\Column(type="float")
     */
    private $shipping_cost;

    /**
     * @ORM\Column(type="float")
     */
    private $shipping_discount;

    /**
     * @ORM\Column(type="float")
     */
    private $paypal_service_cost;

    /**
     * @ORM\Column(type="float")
     */
    private $total_order;

    /**
     * @ORM\ManyToOne(targetEntity=StatusOrderType::class, inversedBy="orders")
     * @ORM\JoinColumn(nullable=false)
     */
    private $status;

    /**
     * @ORM\OneToMany(targetEntity=GuideNumbers::class, mappedBy="number_order")
     */
    private $guideNumbers;

    /**
     * @ORM\OneToMany(targetEntity=OrdersProducts::class, mappedBy="number_order")
     */
    private $ordersProducts;

    /**
     * @ORM\Column(type="datetime")
     */
    private $created_at;

    public function __construct()
    {
        $this->guideNumbers = new ArrayCollection();
        $this->ordersProducts = new ArrayCollection();
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

    public function getCustomerType(): ?CustomersTypesRoles
    {
        return $this->customer_type;
    }

    public function setCustomerType(?CustomersTypesRoles $customer_type): self
    {
        $this->customer_type = $customer_type;

        return $this;
    }

    public function getCustomerName(): ?string
    {
        return $this->customer_name;
    }

    public function setCustomerName(string $customer_name): self
    {
        $this->customer_name = $customer_name;

        return $this;
    }

    public function getCustomerEmail(): ?string
    {
        return $this->customer_email;
    }

    public function setCustomerEmail(string $customer_email): self
    {
        $this->customer_email = $customer_email;

        return $this;
    }

    public function getCustomerPhoneCode(): ?Countries
    {
        return $this->customer_phone_code;
    }

    public function setCustomerPhoneCode(?Countries $customer_phone_code): self
    {
        $this->customer_phone_code = $customer_phone_code;

        return $this;
    }

    public function getCelPhoneCustomer(): ?string
    {
        return $this->cel_phone_customer;
    }

    public function setCelPhoneCustomer(string $cel_phone_customer): self
    {
        $this->cel_phone_customer = $cel_phone_customer;

        return $this;
    }

    public function getPhoneCustomer(): ?string
    {
        return $this->phone_customer;
    }

    public function setPhoneCustomer(?string $phone_customer): self
    {
        $this->phone_customer = $phone_customer;

        return $this;
    }

    public function getCustomerIdentityType(): ?string
    {
        return $this->customer_identity_type;
    }

    public function setCustomerIdentityType(string $customer_identity_type): self
    {
        $this->customer_identity_type = $customer_identity_type;

        return $this;
    }

    public function getCustomerIdentityNumber(): ?string
    {
        return $this->customer_identity_number;
    }

    public function setCustomerIdentityNumber(string $customer_identity_number): self
    {
        $this->customer_identity_number = $customer_identity_number;

        return $this;
    }

    public function getInternationalShipping(): ?bool
    {
        return $this->international_shipping;
    }

    public function setInternationalShipping(bool $international_shipping): self
    {
        $this->international_shipping = $international_shipping;

        return $this;
    }

    public function getShipping(): ?bool
    {
        return $this->shipping;
    }

    public function setShipping(bool $shipping): self
    {
        $this->shipping = $shipping;

        return $this;
    }

    public function getBillFile(): ?string
    {
        return $this->bill_file;
    }

    public function setBillFile(?string $bill_file): self
    {
        $this->bill_file = $bill_file;

        return $this;
    }

    public function getPaymentFile(): ?string
    {
        return $this->payment_file;
    }

    public function setPaymentFile(?string $payment_file): self
    {
        $this->payment_file = $payment_file;

        return $this;
    }

    public function getPaymentReceivedFile(): ?string
    {
        return $this->payment_received_file;
    }

    public function setPaymentReceivedFile(?string $payment_received_file): self
    {
        $this->payment_received_file = $payment_received_file;

        return $this;
    }

    public function getDebitCreditNoteFile(): ?string
    {
        return $this->debit_credit_note_file;
    }

    public function setDebitCreditNoteFile(?string $debit_credit_note_file): self
    {
        $this->debit_credit_note_file = $debit_credit_note_file;

        return $this;
    }

    public function getPaypalTransactionCode(): ?string
    {
        return $this->paypal_transaction_code;
    }

    public function setPaypalTransactionCode(?string $paypal_transaction_code): self
    {
        $this->paypal_transaction_code = $paypal_transaction_code;

        return $this;
    }

    public function getBillAddressId(): ?CustomerAddresses
    {
        return $this->bill_address_id;
    }

    public function setBillAddressId(?CustomerAddresses $bill_address_id): self
    {
        $this->bill_address_id = $bill_address_id;

        return $this;
    }

    public function getBillCountry(): ?Countries
    {
        return $this->biil_country;
    }

    public function setBillCountry(?Countries $biil_country): self
    {
        $this->biil_country = $biil_country;

        return $this;
    }

    public function getBillState(): ?States
    {
        return $this->bill_state;
    }

    public function setBillState(?States $bill_state): self
    {
        $this->bill_state = $bill_state;

        return $this;
    }

    public function getBillCity(): ?Cities
    {
        return $this->bill_city;
    }

    public function setBillCity(?Cities $bill_city): self
    {
        $this->bill_city = $bill_city;

        return $this;
    }

    public function getBillAddress(): ?string
    {
        return $this->bill_address;
    }

    public function setBillAddress(string $bill_address): self
    {
        $this->bill_address = $bill_address;

        return $this;
    }

    public function getBillPostalCode(): ?string
    {
        return $this->bill_postal_code;
    }

    public function setBillPostalCode(string $bill_postal_code): self
    {
        $this->bill_postal_code = $bill_postal_code;

        return $this;
    }

    public function getBillAdditionalInfo(): ?string
    {
        return $this->bill_additional_info;
    }

    public function setBillAdditionalInfo(?string $bill_additional_info): self
    {
        $this->bill_additional_info = $bill_additional_info;

        return $this;
    }

    public function getSubtotal(): ?float
    {
        return $this->subtotal;
    }

    public function setSubtotal(float $subtotal): self
    {
        $this->subtotal = $subtotal;

        return $this;
    }

    public function getProductDiscount(): ?float
    {
        return $this->product_discount;
    }

    public function setProductDiscount(float $product_discount): self
    {
        $this->product_discount = $product_discount;

        return $this;
    }

    public function getPromotionalCodeDiscount(): ?float
    {
        return $this->promotional_code_discount;
    }

    public function setPromotionalCodeDiscount(float $promotional_code_discount): self
    {
        $this->promotional_code_discount = $promotional_code_discount;

        return $this;
    }

    public function getTax(): ?float
    {
        return $this->tax;
    }

    public function setTax(float $tax): self
    {
        $this->tax = $tax;

        return $this;
    }

    public function getShippingCost(): ?float
    {
        return $this->shipping_cost;
    }

    public function setShippingCost(float $shipping_cost): self
    {
        $this->shipping_cost = $shipping_cost;

        return $this;
    }

    public function getShippingDiscount(): ?float
    {
        return $this->shipping_discount;
    }

    public function setShippingDiscount(float $shipping_discount): self
    {
        $this->shipping_discount = $shipping_discount;

        return $this;
    }

    public function getPaypalServiceCost(): ?float
    {
        return $this->paypal_service_cost;
    }

    public function setPaypalServiceCost(float $paypal_service_cost): self
    {
        $this->paypal_service_cost = $paypal_service_cost;

        return $this;
    }

    public function getTotalOrder(): ?float
    {
        return $this->total_order;
    }

    public function setTotalOrder(float $total_order): self
    {
        $this->total_order = $total_order;

        return $this;
    }

    public function getStatus(): ?StatusOrderType
    {
        return $this->status;
    }

    public function setStatus(?StatusOrderType $status): self
    {
        $this->status = $status;

        return $this;
    }

    /**
     * @return Collection<int, GuideNumbers>
     */
    public function getGuideNumbers(): Collection
    {
        return $this->guideNumbers;
    }

    public function addGuideNumber(GuideNumbers $guideNumber): self
    {
        if (!$this->guideNumbers->contains($guideNumber)) {
            $this->guideNumbers[] = $guideNumber;
            $guideNumber->setNumberOrder($this);
        }

        return $this;
    }

    public function removeGuideNumber(GuideNumbers $guideNumber): self
    {
        if ($this->guideNumbers->removeElement($guideNumber)) {
            // set the owning side to null (unless already changed)
            if ($guideNumber->getNumberOrder() === $this) {
                $guideNumber->setNumberOrder(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, OrdersProducts>
     */
    public function getOrdersProducts(): Collection
    {
        return $this->ordersProducts;
    }

    public function addOrdersProduct(OrdersProducts $ordersProduct): self
    {
        if (!$this->ordersProducts->contains($ordersProduct)) {
            $this->ordersProducts[] = $ordersProduct;
            $ordersProduct->setNumberOrder($this);
        }

        return $this;
    }

    public function removeOrdersProduct(OrdersProducts $ordersProduct): self
    {
        if ($this->ordersProducts->removeElement($ordersProduct)) {
            // set the owning side to null (unless already changed)
            if ($ordersProduct->getNumberOrder() === $this) {
                $ordersProduct->setNumberOrder(null);
            }
        }

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeInterface
    {
        return $this->created_at;
    }

    public function setCreatedAt(\DateTimeInterface $created_at): self
    {
        $this->created_at = $created_at;

        return $this;
    }
}
