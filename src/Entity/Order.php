<?php /** @noinspection SpellCheckingInspection */

namespace App\Entity;

use App\Entity\Model\Order as BaseOrder;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\HttpFoundation\Request;

/**
 * @ORM\Entity(repositoryClass="App\Repository\OrderRepository")
 * @ORM\Table("mia_order")
 */
class Order extends BaseOrder
{
    /**
     * @var ArrayCollection|OrderItems[]
     *
     * @ORM\OneToMany(targetEntity="App\Entity\OrderItems", mappedBy="orderId", cascade={"remove"})
     */
    private $orderItems;

    /**
     * @var Customer
     *
     * @ORM\ManyToOne(targetEntity="App\Entity\Customer", inversedBy="shoppingOrders")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="customer_id", referencedColumnName="id", nullable=false)
     * })
     */
    private $customerId;

    /**
     * @var string|null
     *
     * @ORM\Column(name="checkout_id", type="string", length=255, nullable=true)
     */
    private $checkoutId;

    /**
     * @var string|null
     *
     * @ORM\Column(name="checkout_status", type="string", length=255, nullable=true)
     */
    private $checkoutStatus;

    /**
     * @var string|null
     *
     * @ORM\Column(name="status", type="string", length=255, nullable=true)
     */
    private $status;

    /**
     * @var float|null
     *
     * @ORM\Column(name="sub_total", type="float", nullable=true)
     */
    private $subTotal;

    /**
     * @var float|null
     *
     * @ORM\Column(name="total", type="float", nullable=true)
     */
    private $total;

    /**
     * @var float|null
     *
     * @ORM\Column(name="shipping", type="float", nullable=true)
     */
    private $shipping;
    /**
     * @var float|null
     *
     * @ORM\Column(name="handling", type="float", nullable=true)
     */
    private $handling;

    /**
     * @var float|null
     *
     * @ORM\Column(name="insurance", type="float", nullable=true)
     */
    private $insurance;
    /**
     * @var float|null
     *
     * @ORM\Column(name="tax_total", type="float", nullable=true)
     */
    private $taxTotal;
    /**
     * @var float|null
     *
     * @ORM\Column(name="shipping_discount", type="float", nullable=true)
     */
    private $shippingDiscount;
    /**
     * @var float|null
     *
     * @ORM\Column(name="discount", type="float", nullable=true)
     */
    private $discount;

    /**
     * @var integer|null
     *
     * @ORM\Column(name="quantity", type="integer", nullable=true)
     */
    private $quantity;

    /**
     * @var string|null
     *
     * @ORM\Column(name="payment_method", type="string", length=100, nullable=true)
     */
    private $paymentMethod;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="date", type="datetime")
     */
    private $date;

    /**
     * @param Request $request
     * @param Customer $customer
     * @param string $paymentMethod
     */
    public function __construct(
        Request $request,
        Customer $customer,
        string $paymentMethod
    ) {
        parent::__construct($request);

        $this->orderItems = new ArrayCollection();

        $this->paymentMethod = $paymentMethod;
        $this->checkoutStatus = 'CREATED';
        $this->status = 'NEW';

        $this->shipping = 0.00;
        $this->handling = 0.00;
        $this->insurance = 0.00;
        $this->taxTotal = 0.00;
        $this->shippingDiscount = 0.00;
        $this->discount = 0.00;

        $this->date = new \DateTime();

        $this->setCustomerId($customer);

    }

    /**
     * @return OrderItems[]|ArrayCollection
     */
    public function getOrderItems()
    {
        return $this->orderItems;
    }

    /**
     * @param OrderItems $orderItems
     * @return $this
     */
    public function addOrderItem(OrderItems $orderItems): Order
    {
        if (!$this->orderItems->contains($orderItems)) {
            $this->orderItems[] = $orderItems;
        }

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
    public function setCustomerId(Customer $customerId): Order
    {
        $this->customerId = $customerId;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getCheckoutId(): ?string
    {
        return $this->checkoutId;
    }

    /**
     * @param string|null $checkoutId
     * @return $this
     */
    public function setCheckoutId(?string $checkoutId): Order
    {
        $this->checkoutId = $checkoutId;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getStatus(): ?string
    {
        return $this->status;
    }

    /**
     * @param string|null $status
     * @return $this
     */
    public function setStatus(?string $status): Order
    {
        $this->status = $status;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getCheckoutStatus(): ?string
    {
        return $this->checkoutStatus;
    }

    /**
     * @param string|null $checkoutStatus
     * @return $this
     */
    public function setCheckoutStatus(?string $checkoutStatus): Order
    {
        $this->checkoutStatus = $checkoutStatus;

        return $this;
    }

    /**
     * @return float|null
     */
    public function getSubTotal(): ?float
    {
        return $this->subTotal;
    }

    /**
     * @param float|null $subTotal
     * @return $this
     */
    public function setSubTotal(?float $subTotal): Order
    {
        $this->subTotal = $subTotal;

        return $this;
    }

    /**
     * @return float|null
     */
    public function getTotal(): ?float
    {
        return $this->total;
    }

    /**
     * @param float|null $total
     * @return $this
     */
    public function setTotal(?float $total): Order
    {
        $this->total = $total;

        return $this;
    }

    /**
     * @return int
     */
    public function getQuantity(): ?int
    {
        return $this->quantity;
    }

    /**
     * @param int|null $quantity
     * @return $this
     */
    public function setQuantity(?int $quantity): Order
    {
        $this->quantity = $quantity;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getPaymentMethod(): ?string
    {
        return $this->paymentMethod;
    }

    /**
     * @param string|null $paymentMethod
     * @return $this
     */
    public function setPaymentMethod(?string $paymentMethod): Order
    {
        $this->paymentMethod = $paymentMethod;

        return $this;
    }

    /**
     * @return \DateTime
     */
    public function getDate(): \DateTime
    {
        return $this->date;
    }

    /**
     * @param \DateTime $date
     * @return $this
     */
    public function setDate(\DateTime $date): Order
    {
        $this->date = $date;

        return $this;
    }

    /**
     * @return float|null
     */
    public function getShipping(): ?float
    {
        return $this->shipping;
    }

    /**
     * @param float|null $shipping
     * @return $this
     */
    public function setShipping(?float $shipping): Order
    {
        $this->shipping = $shipping;

        return $this;
    }

    /**
     * @return float|null
     */
    public function getHandling(): ?float
    {
        return $this->handling;
    }

    /**
     * @param float|null $handling
     * @return $this
     */
    public function setHandling(?float $handling): Order
    {
        $this->handling = $handling;

        return $this;
    }

    /**
     * @return float|null
     */
    public function getTaxTotal(): ?float
    {
        return $this->taxTotal;
    }

    /**
     * @param float|null $taxTotal
     * @return $this
     */
    public function setTaxTotal(?float $taxTotal): Order
    {
        $this->taxTotal = $taxTotal;

        return $this;
    }

    /**
     * @return float|null
     */
    public function getShippingDiscount(): ?float
    {
        return $this->shippingDiscount;
    }

    /**
     * @param float|null $shippingDiscount
     * @return $this
     */
    public function setShippingDiscount(?float $shippingDiscount): Order
    {
        $this->shippingDiscount = $shippingDiscount;

        return $this;
    }

    /**
     * @return float|null
     */
    public function getDiscount(): ?float
    {
        return $this->discount;
    }

    /**
     * @param float|null $discount
     * @return $this
     */
    public function setDiscount(?float $discount): Order
    {
        $this->discount = $discount;

        return $this;
    }

    /**
     * @return float|null
     */
    public function getInsurance(): ?float
    {
        return $this->insurance;
    }

    /**
     * @param float|null $insurance
     * @return $this
     */
    public function setInsurance(?float $insurance): Order
    {
        $this->insurance = $insurance;

        return $this;
    }

    /**
     * @return array
     */
    public function asArray(): array
    {
        return [
            'id' => $this->getId(),
            'checkoutId' => $this->getCheckoutId(),
            'date' => $this->getDate(),
            'status' => $this->getStatus(),
            'checkoutStatus' => $this->getCheckoutStatus(),
            'items' => $this->itemsArray(),
            'additionalLines' => $this->additionalLines(),
            'quantity' => $this->getQuantity(),
            'subtotal' => $this->getSubTotal(),
            'total' => $this->getTotal(),
            'paymentMethod' => $this->getPaymentMethod(),
            'shippingAddress' => [
                "default" => $this->differentAddress,
                "firstName" => $this->getCheckoutShippingFirstName(),
                "lastName" => $this->getCheckoutShippingLastName(),
                "email" => $this->getCheckoutShippingEmail(),
                "phone" => $this->getCheckoutShippingPhone(),
                "country" => $this->getCheckoutShippingCountry(),
                "city" => $this->getCheckoutShippingCity(),
                "postcode" => $this->getCheckoutShippingPostcode(),
                "address" => $this->getCheckoutShippingAddress(),
            ],
            'billingAddress' => [
                "default" => !$this->differentAddress,
                "firstName" => $this->getCheckoutBillingFirstName(),
                "lastName" => $this->getCheckoutBillingLastName(),
                "email" => $this->getCheckoutBillingEmail(),
                "phone" => $this->getCheckoutBillingPhone(),
                "country" => $this->getCheckoutBillingCountry(),
                "city" => $this->getCheckoutBillingCity(),
                "postcode" => $this->getCheckoutBillingPostcode(),
                "address" => $this->getCheckoutBillingAddress(),
            ],
        ];
    }

    /**
     * @return array
     */
    private function itemsArray(): array
    {
        $items = [];
        foreach ($this->getOrderItems() as $item) {
            $items[] = $item->asArray();
        }

        return $items;
    }

    /**
     * @return array
     */
    private function additionalLines(): array
    {
        $addLines = [];
        if ($this->getDiscount() > 0) {
            $addLines[] = [
                "label" => 'Descuento (cupón)',
                "total" => $this->getDiscount(),
            ];
        }
        if ($this->getShipping() > 0) {
            $addLines[] = [
                "label" => 'Envío',
                "total" => $this->getShipping(),
            ];
        }
        if ($this->getHandling() > 0) {
            $addLines[] = [
                "label" => 'Manejo',
                "total" => $this->getHandling(),
            ];
        }
        if ($this->getInsurance() > 0) {
            $addLines[] = [
                "label" => 'Seguro',
                "total" => $this->getInsurance(),
            ];
        }
        if ($this->getShippingDiscount() > 0) {
            $addLines[] = [
                "label" => 'Descuento de envío',
                "total" => $this->getShippingDiscount(),
            ];
        }
        if ($this->getTaxTotal() > 0) {
            $addLines[] = [
                "label" => 'Impuesto',
                "total" => $this->getTaxTotal(),
            ];
        }

        return $addLines;
    }

    public function removeOrderItem(OrderItems $orderItem): self
    {
        if ($this->orderItems->removeElement($orderItem)) {
            // set the owning side to null (unless already changed)
            if ($orderItem->getOrderId() === $this) {
                $orderItem->setOrderId(null);
            }
        }

        return $this;
    }
}
