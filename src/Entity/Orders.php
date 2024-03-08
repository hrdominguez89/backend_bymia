<?php

namespace App\Entity;

use App\Constants\Constants;
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
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $customer_identity_type;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $customer_identity_number;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $international_shipping;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $shipping;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $bill_file;

    /**
     * @ORM\ManyToOne(targetEntity=CustomerAddresses::class, inversedBy="orders")
     * @ORM\JoinColumn(nullable=true)
     */
    private $bill_address;

    /**
     * @ORM\ManyToOne(targetEntity=Countries::class, inversedBy="orders")
     * @ORM\JoinColumn(nullable=true)
     */
    private $biil_country;

    /**
     * @ORM\ManyToOne(targetEntity=States::class, inversedBy="orders")
     * @ORM\JoinColumn(nullable=true)
     */
    private $bill_state;

    /**
     * @ORM\ManyToOne(targetEntity=Cities::class, inversedBy="orders")
     * @ORM\JoinColumn(nullable=true)
     */
    private $bill_city;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $bill_address_order;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $bill_postal_code;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $bill_additional_info;

    /**
     * @ORM\Column(type="float", nullable=true)
     */
    private $subtotal_rd;

    /**
     * @ORM\Column(type="float", nullable=true)
     */
    private $total_product_discount_rd;

    /**
     * @ORM\Column(type="float", nullable=true)
     */
    private $tax_rd;

    /**
     * @ORM\Column(type="float", nullable=true)
     */
    private $shipping_cost;

    /**
     * @ORM\Column(type="float", nullable=true)
     */
    private $shipping_discount;

    /**
     * @ORM\Column(type="float", nullable=true)
     */
    private $paypal_service_cost;

    /**
     * @ORM\Column(type="float", nullable=true)
     */
    private $total_order_rd;

    /**
     * @ORM\ManyToOne(targetEntity=StatusOrderType::class, inversedBy="orders")
     * @ORM\JoinColumn(nullable=false)
     */
    private $status;

    /**
     * @ORM\OneToMany(targetEntity=GuideNumbers::class, mappedBy="number_order", cascade={"remove"})
     */
    private $guideNumbers;

    /**
     * @ORM\OneToMany(targetEntity=OrdersProducts::class, mappedBy="number_order", cascade={"remove"})
     */
    private $ordersProducts;

    /**
     * @ORM\Column(type="datetime", nullable=false, options={"default":"CURRENT_TIMESTAMP"})
     */
    private $created_at;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $receiver_name;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $receiver_document_type;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $receiver_document;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $receiver_phone_cell;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $receiver_phone_home;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $receiver_email;

    /**
     * @ORM\ManyToOne(targetEntity=Countries::class, inversedBy="receiver_orders")
     * @ORM\JoinColumn(nullable=true)
     */
    private $receiver_country;

    /**
     * @ORM\ManyToOne(targetEntity=States::class, inversedBy="receiver_orders")
     * @ORM\JoinColumn(nullable=true)
     */
    private $receiver_state;

    /**
     * @ORM\ManyToOne(targetEntity=Cities::class, inversedBy="receiver_orders")
     * @ORM\JoinColumn(nullable=true)
     */
    private $receiver_city;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $receiver_address_order;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $receiver_cod_zip;

    /**
     * @ORM\Column(type="text", nullable=true, nullable=true)
     */
    private $receiver_additional_info;

    /**
     * @ORM\ManyToOne(targetEntity=Warehouses::class, inversedBy="orders")
     * @ORM\JoinColumn(nullable=false)
     */
    private $warehouse;

    /**
     * @ORM\OneToMany(targetEntity=PaymentsFiles::class, mappedBy="order_number", cascade={"remove"})
     */
    private $paymentsFiles;

    /**
     * @ORM\OneToMany(targetEntity=PaymentsReceivedFiles::class, mappedBy="order_number", cascade={"remove"})
     */
    private $paymentsReceivedFiles;

    /**
     * @ORM\OneToMany(targetEntity=DebitCreditNotesFiles::class, mappedBy="number_order", cascade={"remove"})
     */
    private $debitCreditNotesFiles;

    /**
     * @ORM\OneToMany(targetEntity=PaymentsTransactionsCodes::class, mappedBy="order_number", cascade={"remove"})
     */
    private $paymentsTransactionsCodes;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $inventory_id;

    /**
     * @ORM\ManyToOne(targetEntity=CommunicationStatesBetweenPlatforms::class, inversedBy="orders")
     * @ORM\JoinColumn(nullable=false)
     */
    private $status_sent_crm;

    /**
     * @ORM\Column(type="smallint", options={"default":0})
     */
    private $attempts_send_crm;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $error_message_crm;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $sales_id_3pl;

    /**
     * @ORM\ManyToOne(targetEntity=ShippingTypes::class, inversedBy="orders")
     */
    private $shipping_type;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $bill_name;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $bill_identity_type;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $bill_identity_number;

    /**
     * @ORM\ManyToOne(targetEntity=CustomerAddresses::class, inversedBy="receiver_address")
     */
    private $receiver_address;

    /**
     * @ORM\OneToMany(targetEntity=Transactions::class, mappedBy="number_order")
     */
    private $transactions;

    /**
     * @ORM\ManyToOne(targetEntity=PaymentType::class, inversedBy="orders")
     */
    private $payment_type;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $proforma_bill_file;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $fiscalInvoiceRequired;

    /**
     * @ORM\Column(type="float", nullable=true)
     */
    private $subtotal_usd;

    /**
     * @ORM\Column(type="float", nullable=true)
     */
    private $total_product_discount_usd;

    /**
     * @ORM\Column(type="float", nullable=true)
     */
    private $promotional_code_discount;

    /**
     * @ORM\Column(type="float", nullable=true)
     */
    private $tax_usd;

    /**
     * @ORM\Column(type="float", nullable=true)
     */
    private $total_order_usd;

    public function __construct()
    {
        $this->guideNumbers = new ArrayCollection();
        $this->ordersProducts = new ArrayCollection();
        $this->paymentsFiles = new ArrayCollection();
        $this->paymentsReceivedFiles = new ArrayCollection();
        $this->debitCreditNotesFiles = new ArrayCollection();
        $this->paymentsTransactionsCodes = new ArrayCollection();
        $this->attempts_send_crm = 0;
        $this->created_at = new \DateTime();
        $this->transactions = new ArrayCollection();
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

    public function getBillAddress(): ?CustomerAddresses
    {
        return $this->bill_address;
    }

    public function setBillAddress(?CustomerAddresses $bill_address): self
    {
        $this->bill_address = $bill_address;

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

    public function getBillAddressOrder(): ?string
    {
        return $this->bill_address_order;
    }

    public function setBillAddressOrder(string $bill_address_order): self
    {
        $this->bill_address_order = $bill_address_order;

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

    public function getSubtotalRD(): ?float
    {
        return $this->subtotal_rd;
    }

    public function setSubtotalRD(float $subtotal_rd): self
    {
        $this->subtotal_rd = $subtotal_rd;

        return $this;
    }

    public function getTotalProductDiscountRD(): ?float
    {
        return $this->total_product_discount_rd;
    }

    public function setTotalProductDiscountRD(float $total_product_discount_rd): self
    {
        $this->total_product_discount_rd = $total_product_discount_rd;

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

    public function getTaxRD(): ?float
    {
        return $this->tax_rd;
    }

    public function setTaxRD(float $tax_rd): self
    {
        $this->tax_rd = $tax_rd;

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

    public function getTotalOrderRD(): ?float
    {
        return $this->total_order_rd;
    }

    public function setTotalOrderRD(float $total_order_rd): self
    {
        $this->total_order_rd = $total_order_rd;

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

    public function getReceiverName(): ?string
    {
        return $this->receiver_name;
    }

    public function setReceiverName(string $receiver_name): self
    {
        $this->receiver_name = $receiver_name;

        return $this;
    }

    public function getReceiverDocumentType(): ?string
    {
        return $this->receiver_document_type;
    }

    public function setReceiverDocumentType(string $receiver_document_type): self
    {
        $this->receiver_document_type = $receiver_document_type;

        return $this;
    }

    public function getReceiverDocument(): ?string
    {
        return $this->receiver_document;
    }

    public function setReceiverDocument(string $receiver_document): self
    {
        $this->receiver_document = $receiver_document;

        return $this;
    }

    public function getReceiverPhoneCell(): ?string
    {
        return $this->receiver_phone_cell;
    }

    public function setReceiverPhoneCell(string $receiver_phone_cell): self
    {
        $this->receiver_phone_cell = $receiver_phone_cell;

        return $this;
    }

    public function getReceiverPhoneHome(): ?string
    {
        return $this->receiver_phone_home;
    }

    public function setReceiverPhoneHome(?string $receiver_phone_home): self
    {
        $this->receiver_phone_home = $receiver_phone_home;

        return $this;
    }

    public function getReceiverEmail(): ?string
    {
        return $this->receiver_email;
    }

    public function setReceiverEmail(string $receiver_email): self
    {
        $this->receiver_email = $receiver_email;

        return $this;
    }

    public function getReceiverCountry(): ?Countries
    {
        return $this->receiver_country;
    }

    public function setReceiverCountry(?Countries $receiver_country): self
    {
        $this->receiver_country = $receiver_country;

        return $this;
    }

    public function getReceiverState(): ?States
    {
        return $this->receiver_state;
    }

    public function setReceiverState(?States $receiver_state): self
    {
        $this->receiver_state = $receiver_state;

        return $this;
    }

    public function getReceiverCity(): ?Cities
    {
        return $this->receiver_city;
    }

    public function setReceiverCity(?Cities $receiver_city): self
    {
        $this->receiver_city = $receiver_city;

        return $this;
    }

    public function getReceiverAddressOrder(): ?string
    {
        return $this->receiver_address_order;
    }

    public function setReceiverAddressOrder(string $receiver_address_order): self
    {
        $this->receiver_address_order = $receiver_address_order;

        return $this;
    }

    public function getReceiverCodZip(): ?string
    {
        return $this->receiver_cod_zip;
    }

    public function setReceiverCodZip(string $receiver_cod_zip): self
    {
        $this->receiver_cod_zip = $receiver_cod_zip;

        return $this;
    }

    public function getReceiverAdditionalInfo(): ?string
    {
        return $this->receiver_additional_info;
    }

    public function setReceiverAdditionalInfo(string $receiver_additional_info): self
    {
        $this->receiver_additional_info = $receiver_additional_info;

        return $this;
    }

    public function generateOrderToCRM()
    {
        $guide_numbers_array = $this->guideNumbers;
        $guide_numbers_result = [];

        foreach ($guide_numbers_array as $guideNumber) {
            $items = [];
            foreach ($guideNumber->getItemsGuideNumbers() as $item) {
                $items[] = [
                    'product_id' => $item->getProduct()->getId3pl(),
                    'quantity' => $item->getQuantity(),
                ];
            }
            $guide_numbers_result[] = [
                "lb" => $guideNumber->getLb(),
                "height" => $guideNumber->getHeight(),
                "width" => $guideNumber->getWidth(),
                "depth" => $guideNumber->getDepth(),
                "courier_id" => $guideNumber->getCourierId(),
                'courier_name' => $guideNumber->getCourierName(),
                "service_id" => $guideNumber->getServiceId(),
                "service_name" => $guideNumber->getServiceName(),
                'guide_number' => $guideNumber->getNumber(),
                'items' => $items,
            ];
        }

        $orders_products_array = $this->ordersProducts;
        $orders_products_result_rd = [];
        $orders_products_result_usd = [];

        foreach ($orders_products_array as $order_product) {
            if($order_product->getProduct()->getCurrency()->getId()==1){//si es rd
                $orders_products_result_rd[] = [
                    'product_id' => $order_product->getProduct()->getId3pl(),
                    'currency_id' => $order_product->getProduct()->getCurrency()->getId(),
                    'currency_name' => $order_product->getProduct()->getCurrency()->getName(),
                    'product_name' => $order_product->getProduct()->getName(),
                    'qty' => $order_product->getQuantity(),
                    'weight' => $order_product->getProduct()->getWeight(),
                    'price' => $order_product->getPrice(),
                    'discount' => $order_product->getDiscount() ?: 0
                ];
            }else{ //si es dolar
                $orders_products_result_usd[] = [
                    'product_id' => $order_product->getProduct()->getId3pl(),
                    'currency_id' => $order_product->getProduct()->getCurrency()->getId(),
                    'currency_name' => $order_product->getProduct()->getCurrency()->getName(),
                    'product_name' => $order_product->getProduct()->getName(),
                    'qty' => $order_product->getQuantity(),
                    'weight' => $order_product->getProduct()->getWeight(),
                    'price' => $order_product->getPrice(),
                    'discount' => $order_product->getDiscount() ?: 0
                ];
            }
        }


        $payments_files_array = $this->paymentsFiles;
        $payments_files_result = [];

        foreach ($payments_files_array as $paymentFile) {
            $payments_files_result[] = [
                "payment_file" => $paymentFile->getPaymentFile(),
            ];
        }

        $payments_received_files_array = $this->paymentsReceivedFiles;
        $payments_received_files_result = [];

        foreach ($payments_received_files_array as $paymentReceivedFile) {
            $payments_received_files_result[] = [
                "payment_received_file" => $paymentReceivedFile->getPaymentReceivedFile(),
            ];
        }

        $payments_transactions_codes_array = $this->paymentsTransactionsCodes;
        $payments_transactions_codes_result = [];

        foreach ($payments_transactions_codes_array as $paymentTransactionCode) {
            $payments_transactions_codes_result[] = [
                "payment_transaction_code" => $paymentTransactionCode->getPaymentTransactionCode(),
            ];
        }

        $debit_credite_notes_files_array = $this->debitCreditNotesFiles;
        $debit_credite_notes_files_result = [];

        foreach ($debit_credite_notes_files_array as $debitCreditNoteFile) {
            $debit_credite_notes_files_result[] = [
                "debit_credit_note_file" => $debitCreditNoteFile->getDebitCreditNoteFile(),
            ];
        }

        return [
            "order_id" => $this->getId(),
            // "inventory_id" => $this->getInventoryId(),
            "payment_type_id" => $this->getPaymentType() ? $this->getPaymentType()->getId() : null,
            "cardnet_session" => $this->getTransactionApproved() ? $this->getTransactionApproved()->getSession() : null,
            "cardnet_session_key" => $this->getTransactionApproved() ? $this->getTransactionApproved()->getSessionKey() : null,
            "cardnet_authorization_code" => $this->getTransactionApproved() ? $this->getTransactionApproved()->getAuthorizationCode() : null,
            "cardnet_tx_token" => $this->getTransactionApproved() ? $this->getTransactionApproved()->getTxToken() : null,
            "cardnet_response_code" => $this->getTransactionApproved() ? $this->getTransactionApproved()->getResponseCode() : null,
            "cardnet_creditcard_number" => $this->getTransactionApproved() ? $this->getTransactionApproved()->getCreditcardNumber() : null,
            "cardnet_retrival_reference_number" => $this->getTransactionApproved() ? $this->getTransactionApproved()->getRetrivalReferenceNumber() : null,
            "cardnet_remote_response_code" => $this->getTransactionApproved() ? $this->getTransactionApproved()->getRemoteResponseCode() : null,
            "fiscal_invoice_required" => $this->isFiscalInvoiceRequired() ? true : false,
            "created_at" => $this->getCreatedAt()->format('Y-m-d H:i:s'),
            "status_order" => $this->getStatus()->getId(),
            "packages" => $guide_numbers_result,
            "warehouse_id" => $this->getWarehouse()->getId3pl(),
            "itemsRD" => $orders_products_result_rd,
            "itemsUSD" => $orders_products_result_usd,
            "customer" => [
                "id" => $this->getCustomer()->getId(),
                "customer_type" => $this->getCustomerType()->getId(),
                "name" => $this->getCustomerName(),
                "email" => $this->getCustomerEmail(),
                "phone_code" => $this->getCustomerPhoneCode() ? $this->getCustomerPhoneCode()->getId() : null,
                "cel_phone_customer" => $this->getCelPhoneCustomer(),
                "phone_customer" => $this->getPhoneCustomer(),
                "customer_identity_type" => $this->getCustomerIdentityType(),
                "customer_identity_number" => $this->getCustomerIdentityNumber()
            ],
            "international_shipping" => $this->getInternationalShipping() ? 1 : 0,
            "shipping" => $this->getShipping() ? 1 : 0,
            "shipping_type" => $this->getShippingType() ? $this->getShippingType()->getId() : null,
            "bill_file" => $this->getBillFile(),
            "proforma_bill_file" => $this->getProformaBillFile(),
            "payments_files" => $payments_files_result, //este y payments_received_files por ahora los dejo asi
            "payments_received_files" => $payments_received_files_result,
            "payments_transactions_codes" => $payments_transactions_codes_result,
            "debit_credit_notes_files" => $debit_credite_notes_files_result,
            "receiver" => [
                "name" => $this->getReceiverName(),
                "document_type" => $this->getReceiverDocumentType(),
                "document" => $this->getReceiverDocument(),
                "phone_cell" => $this->getReceiverPhoneCell(),
                "phone_home" => $this->getReceiverPhoneHome(),
                "email" => $this->getReceiverEmail(),
                "country_id" => $this->getReceiverCountry() ? $this->getReceiverCountry()->getId() : null,
                "country_name" => $this->getReceiverCountry() ? $this->getReceiverCountry()->getName() : null,
                "state_id" => $this->getReceiverState() ? $this->getReceiverState()->getId() : null,
                "state_name" => $this->getReceiverState() ? $this->getReceiverState()->getName() : null,
                "city_id" => $this->getReceiverCity() ? $this->getReceiverCity()->getId() : null,
                "city_name" => $this->getReceiverCity() ? $this->getReceiverCity()->getName() : null,
                "address" => $this->getReceiverAddressOrder(),
                "cod_zip" => $this->getReceiverCodZip(),
                "additional_info" => $this->getReceiverAdditionalInfo()
            ],
            "bill_address" => [
                "bill_address_id" => $this->getBillAddress() ? $this->getBillAddress()->getId() : null,
                "bill_name" => $this->getBillName(),
                "bill_identity_type" => $this->getBillIdentityType(),
                "bill_identity_number" => $this->getBillIdentityNumber(),
                "country_id" => $this->getBillCountry() ? $this->getBillCountry()->getId() : null,
                "country_name" => $this->getBillCountry() ? $this->getBillCountry()->getName() : null,
                "state_id" => $this->getBillState() ? $this->getBillState()->getId() : null,
                "state_name" => $this->getBillState() ? $this->getBillState()->getName() : null,
                "city_id" => $this->getBillCity() ? $this->getBillCity()->getId() : null,
                "city_name" => $this->getBillCity() ? $this->getBillCity()->getName() : null,
                "address" => $this->getBillAddressOrder(),
                "cod_zip" => $this->getBillPostalCode()
            ],
            "subtotal_rd" => $this->getSubtotalRD(),
            "total_product_discount_rd" => $this->getTotalProductDiscountRD(),
            "tax_rd" => $this->getTaxRD(),
            "total_order_rd" => $this->getTotalOrderRD(),
            
            "subtotal_usd" => $this->getSubtotalUSD(),
            "total_product_discount_usd" => $this->getTotalProductDiscountUSD(),
            "tax_usd" => $this->getTaxUSD(),
            "total_order_usd" => $this->getTotalOrderUSD(),
            
            "promotional_code_discount" => $this->getPromotionalCodeDiscount(),
            "shipping_cost" => $this->getShippingCost(),
            "shipping_discount" => $this->getShippingDiscount()
        ];
    }

    public function getenerateOrderToFront()
    {

        $orders_products_array = $this->ordersProducts;
        $orders_products_result = [];

        foreach ($orders_products_array as $order_product) {
            $orders_products_result[] = [
                'id' => $order_product->getProduct()->getId3pl(),
                'name' => $order_product->getProduct()->getName(),
                'quantity' => $order_product->getQuantity(),
                'price' => $order_product->getPrice(),
                'currency_id' => $order_product->getCurrency()->getId(),
                'currency_sign' => $order_product->getCurrency()->getSign(),
                'discount' => $order_product->getDiscount()
            ];
        }
        return [];
    }

    public function getWarehouse(): ?Warehouses
    {
        return $this->warehouse;
    }

    public function setWarehouse(?Warehouses $warehouse): self
    {
        $this->warehouse = $warehouse;

        return $this;
    }

    /**
     * @return Collection<int, PaymentsFiles>
     */
    public function getPaymentsFiles(): Collection
    {
        return $this->paymentsFiles;
    }

    public function addPaymentsFile(PaymentsFiles $paymentsFile): self
    {
        if (!$this->paymentsFiles->contains($paymentsFile)) {
            $this->paymentsFiles[] = $paymentsFile;
            $paymentsFile->setOrderNumber($this);
        }

        return $this;
    }

    public function removePaymentsFile(PaymentsFiles $paymentsFile): self
    {
        if ($this->paymentsFiles->removeElement($paymentsFile)) {
            // set the owning side to null (unless already changed)
            if ($paymentsFile->getOrderNumber() === $this) {
                $paymentsFile->setOrderNumber(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, PaymentsReceivedFiles>
     */
    public function getPaymentsReceivedFiles(): Collection
    {
        return $this->paymentsReceivedFiles;
    }

    public function addPaymentsReceivedFile(PaymentsReceivedFiles $paymentsReceivedFile): self
    {
        if (!$this->paymentsReceivedFiles->contains($paymentsReceivedFile)) {
            $this->paymentsReceivedFiles[] = $paymentsReceivedFile;
            $paymentsReceivedFile->setOrderNumber($this);
        }

        return $this;
    }

    public function removePaymentsReceivedFile(PaymentsReceivedFiles $paymentsReceivedFile): self
    {
        if ($this->paymentsReceivedFiles->removeElement($paymentsReceivedFile)) {
            // set the owning side to null (unless already changed)
            if ($paymentsReceivedFile->getOrderNumber() === $this) {
                $paymentsReceivedFile->setOrderNumber(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, DebitCreditNotesFiles>
     */
    public function getDebitCreditNotesFiles(): Collection
    {
        return $this->debitCreditNotesFiles;
    }

    public function addDebitCreditNotesFile(DebitCreditNotesFiles $debitCreditNotesFile): self
    {
        if (!$this->debitCreditNotesFiles->contains($debitCreditNotesFile)) {
            $this->debitCreditNotesFiles[] = $debitCreditNotesFile;
            $debitCreditNotesFile->setNumberOrder($this);
        }

        return $this;
    }

    public function removeDebitCreditNotesFile(DebitCreditNotesFiles $debitCreditNotesFile): self
    {
        if ($this->debitCreditNotesFiles->removeElement($debitCreditNotesFile)) {
            // set the owning side to null (unless already changed)
            if ($debitCreditNotesFile->getNumberOrder() === $this) {
                $debitCreditNotesFile->setNumberOrder(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, PaymentsTransactionsCodes>
     */
    public function getPaymentsTransactionsCodes(): Collection
    {
        return $this->paymentsTransactionsCodes;
    }

    public function addPaymentsTransactionsCode(PaymentsTransactionsCodes $paymentsTransactionsCode): self
    {
        if (!$this->paymentsTransactionsCodes->contains($paymentsTransactionsCode)) {
            $this->paymentsTransactionsCodes[] = $paymentsTransactionsCode;
            $paymentsTransactionsCode->setOrderNumber($this);
        }

        return $this;
    }

    public function removePaymentsTransactionsCode(PaymentsTransactionsCodes $paymentsTransactionsCode): self
    {
        if ($this->paymentsTransactionsCodes->removeElement($paymentsTransactionsCode)) {
            // set the owning side to null (unless already changed)
            if ($paymentsTransactionsCode->getOrderNumber() === $this) {
                $paymentsTransactionsCode->setOrderNumber(null);
            }
        }

        return $this;
    }

    public function getInventoryId(): ?int
    {
        return $this->inventory_id;
    }

    public function setInventoryId(?int $inventory_id): self
    {
        $this->inventory_id = $inventory_id;

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

    public function getAttemptsSendCrm(): ?int
    {
        return $this->attempts_send_crm;
    }

    public function setAttemptsSendCrm(int $attempts_send_crm): self
    {
        $this->attempts_send_crm = $attempts_send_crm;

        return $this;
    }

    public function incrementAttemptsToSendOrderToCrm()
    {
        $this->setAttemptsSendCrm($this->attempts_send_crm + 1);
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

    public function getSalesId3pl(): ?int
    {
        return $this->sales_id_3pl;
    }

    public function setSalesId3pl(?int $sales_id_3pl): self
    {
        $this->sales_id_3pl = $sales_id_3pl;

        return $this;
    }

    public function getShippingType(): ?ShippingTypes
    {
        return $this->shipping_type;
    }

    public function setShippingType(?ShippingTypes $shipping_type): self
    {
        $this->shipping_type = $shipping_type;

        return $this;
    }

    public function getBillName(): ?string
    {
        return $this->bill_name;
    }

    public function setBillName(?string $bill_name): self
    {
        $this->bill_name = $bill_name;

        return $this;
    }

    public function getBillIdentityType(): ?string
    {
        return $this->bill_identity_type;
    }

    public function setBillIdentityType(?string $bill_identity_type): self
    {
        $this->bill_identity_type = $bill_identity_type;

        return $this;
    }

    public function getBillIdentityNumber(): ?string
    {
        return $this->bill_identity_number;
    }

    public function setBillIdentityNumber(?string $bill_identity_number): self
    {
        $this->bill_identity_number = $bill_identity_number;

        return $this;
    }

    public function getReceiverAddress(): ?CustomerAddresses
    {
        return $this->receiver_address;
    }

    public function setReceiverAddress(?CustomerAddresses $receiver_address): self
    {
        $this->receiver_address = $receiver_address;

        return $this;
    }

    /**
     * @return Collection<int, Transactions>
     */
    public function getTransactions(): Collection
    {
        return $this->transactions;
    }

    /**
     * @return Transaction|null La transacción aprobada, o null si no hay ninguna.
     */
    public function getTransactionApproved(): ?Transactions
    {
        // Filtra las transacciones para obtener solo las aprobadas
        $transactionApproved = $this->transactions->filter(function (Transactions $transaction) {
            return $transaction->getStatus()->getId() === Constants::STATUS_TRANSACTION_ACCEPTED;
        });

        // Obtén la primera transacción aprobada
        return $transactionApproved->first() ?: null;
    }

    public function addTransaction(Transactions $transaction): self
    {
        if (!$this->transactions->contains($transaction)) {
            $this->transactions[] = $transaction;
            $transaction->setNumberOrder($this);
        }

        return $this;
    }

    public function removeTransaction(Transactions $transaction): self
    {
        if ($this->transactions->removeElement($transaction)) {
            // set the owning side to null (unless already changed)
            if ($transaction->getNumberOrder() === $this) {
                $transaction->setNumberOrder(null);
            }
        }

        return $this;
    }

    public function getPaymentType(): ?PaymentType
    {
        return $this->payment_type;
    }

    public function setPaymentType(?PaymentType $payment_type): self
    {
        $this->payment_type = $payment_type;

        return $this;
    }

    public function getProformaBillFile(): ?string
    {
        return $this->proforma_bill_file;
    }

    public function setProformaBillFile(?string $proforma_bill_file): self
    {
        $this->proforma_bill_file = $proforma_bill_file;

        return $this;
    }

    public function isFiscalInvoiceRequired(): ?bool
    {
        return $this->fiscalInvoiceRequired;
    }

    public function setFiscalInvoiceRequired(?bool $fiscalInvoiceRequired): self
    {
        $this->fiscalInvoiceRequired = $fiscalInvoiceRequired;

        return $this;
    }

    public function getSubtotalUSD(): ?float
    {
        return $this->subtotal_usd;
    }

    public function setSubtotalUSD(?float $subtotal_usd): self
    {
        $this->subtotal_usd = $subtotal_usd;

        return $this;
    }

    public function getTotalProductDiscountUSD(): ?float
    {
        return $this->total_product_discount_usd;
    }

    public function setTotalProductDiscountUSD(?float $total_product_discount_usd): self
    {
        $this->total_product_discount_usd = $total_product_discount_usd;

        return $this;
    }

    public function getTaxUSD(): ?float
    {
        return $this->tax_usd;
    }

    public function setTaxUSD(?float $tax_usd): self
    {
        $this->tax_usd = $tax_usd;

        return $this;
    }

    public function getTotalOrderUSD(): ?float
    {
        return $this->total_order_usd;
    }

    public function setTotalOrderUSD(?float $total_order_usd): self
    {
        $this->total_order_usd = $total_order_usd;

        return $this;
    }
}
