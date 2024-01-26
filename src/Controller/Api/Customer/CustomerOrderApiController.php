<?php

namespace App\Controller\Api\Customer;

use App\Constants\Constants;
use App\Entity\CustomerAddresses;
use App\Entity\Orders;
use App\Entity\OrdersProducts;
use App\Entity\Recipients;
use App\Entity\Transactions;
use App\Helpers\SendOrderToCrm;
use App\Repository\CitiesRepository;
use App\Repository\CommunicationStatesBetweenPlatformsRepository;
use App\Repository\CountriesRepository;
use App\Repository\CustomerAddressesRepository;
use App\Repository\CustomerRepository;
use App\Repository\OrdersProductsRepository;
use App\Repository\OrdersRepository;
use App\Repository\PaymentTypeRepository;
use App\Repository\ProductRepository;
use App\Repository\RegistrationTypeRepository;
use App\Repository\ShippingTypesRepository;
use App\Repository\ShoppingCartRepository;
use App\Repository\StatesRepository;
use App\Repository\StatusOrderTypeRepository;
use App\Repository\StatusTypeShoppingCartRepository;
use App\Repository\StatusTypeTransactionRepository;
use App\Repository\TransactionsRepository;
use DateTime;
use Doctrine\ORM\EntityManagerInterface;
use Exception;
use Lexik\Bundle\JWTAuthenticationBundle\Encoder\JWTEncoderInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;

/**
 * @Route("/api/customer")
 */
class CustomerOrderApiController extends AbstractController
{

    private $customer;

    public function __construct(JWTEncoderInterface $jwtEncoder, CustomerRepository $customerRepository, RequestStack $requestStack)
    {
        $request = $requestStack->getCurrentRequest();
        $token = explode(' ', $request->headers->get('Authorization'))[1];

        $username = @$jwtEncoder->decode($token)['username'] ?: '';

        $this->customer = $customerRepository->findOneBy(['email' => $username]);
    }

    /**
     * @Route("/pre-order", name="api_customer_pre_order",methods={"POST"})
     */
    public function preOrder(
        Request $request,
        StatusOrderTypeRepository $statusOrderTypeRepository,
        ShoppingCartRepository $shoppingCartRepository,
        StatusTypeShoppingCartRepository $statusTypeShoppingCartRepository,
        EntityManagerInterface $em,
        CommunicationStatesBetweenPlatformsRepository $communicationStatesBetweenPlatformsRepository,
        ProductRepository $productRepository
    ): Response {

        $body = $request->getContent();
        $data = json_decode($body, true);


        $subTotal = 0;
        // Crear arrays para almacenar los errores
        $errors = [];
        $shopping_cart_products = [];
        // Iterar sobre los productos enviados en la solicitud
        foreach ($data['products'] as $product_cart) {
            $productId = $product_cart['id']; //product_id
            $quantity = $product_cart['quantity'];

            // Verificar si el producto existe y está activo
            $product = $productRepository->findActiveProductById($productId);
            if (!$product) {
                $errors['product_not_found'][] = $productId;
                continue;
            }

            // Verificar si el producto está en el carrito de compras
            $product_on_cart = $shoppingCartRepository->findShoppingCartProductByStatus($productId, $this->customer->getId(), Constants::STATUS_SHOPPING_CART_ACTIVO);
            if (!$product_on_cart) {
                $errors['product_not_added_cart'][] = $product->getBasicDataProduct();
                continue;
            }

            // Verificar disponibilidad de cantidad
            if ($product_on_cart->getProduct()->getAvailable() < $quantity) {
                $errors['product_quantity_not_available'][] = $product_on_cart->getProduct()->getBasicDataProduct();

                continue;
            }
            $subTotal += $product_on_cart->getProduct()->getRealPrice() * $quantity;
            $product_on_cart->setQuantity($quantity);
            // Agregar producto al carrito de compras
            $shopping_cart_products[] = $product_on_cart;
        }

        // Verificar si hubo errores
        if (!empty($errors)) {
            $response = [
                "status_code" => Response::HTTP_CONFLICT,
                'message' => 'Error al intentar agregar uno o más productos a la orden.',
                "errors" => $errors
            ];
            return $this->json($response, Response::HTTP_CONFLICT, ['Content-Type' => 'application/json']);
        }

        $status_sent_crm = $communicationStatesBetweenPlatformsRepository->find(Constants::CBP_STATUS_PENDING);
        $pre_order = new Orders();

        $pre_order
            ->setSubtotal($subTotal)
            ->setCustomer($this->customer)
            ->setCustomerType($this->customer->getCustomerTypeRole())
            ->setCustomerName($this->customer->getName())
            ->setCustomerEmail($this->customer->getEmail())
            ->setCustomerPhoneCode($this->customer->getCountryPhoneCode())
            ->setCelPhoneCustomer($this->customer->getCelPhone())
            ->setPhoneCustomer($this->customer->getPhone() ?: null)
            ->setStatusSentCrm($status_sent_crm)
            ->setAttemptsSendCrm(0)
            ->setStatus($statusOrderTypeRepository->findOneBy(["id" => Constants::STATUS_ORDER_PENDING]))
            ->setCreatedAt(new \DateTime())
            ->setWarehouse($shopping_cart_products[0]->getProduct()->getInventory()->getWarehouse()) //revisar porque estoy forzando a un warehouse
            ->setInventoryId($shopping_cart_products[0]->getProduct()->getInventory()->getId()); //revisar porque estoy forzando a un inventario

        foreach ($shopping_cart_products as $shopping_cart_product) {
            $shopping_cart_product->setStatus($statusTypeShoppingCartRepository->findOneBy(["id" => Constants::STATUS_SHOPPING_CART_EN_ORDEN]));
            $order_product = new OrdersProducts();
            //NO GUARDO PRECIO PORQUE AUN NO FUE PAGADO, CUANDO REALICE EL PAGO AHI SE GUARDA EL PRECIO.
            $order_product
                ->setNumberOrder($pre_order)
                ->setProduct($shopping_cart_product->getProduct())
                ->setName($shopping_cart_product->getProduct()->getName())
                ->setSku($shopping_cart_product->getProduct()->getSku())
                ->setPartNumber($shopping_cart_product->getProduct()->getPartNumber() ?: null)
                ->setCod($shopping_cart_product->getProduct()->getCod() ?: null)
                ->setWeight($shopping_cart_product->getProduct()->getWeight() ?: null)
                ->setQuantity($shopping_cart_product->getQuantity())
                ->setShoppingCart($shopping_cart_product);
            $em->persist($order_product);
            $em->persist($shopping_cart_product);

            $pre_order->addOrdersProduct($order_product);
        }

        $em->persist($pre_order);
        try {
            $em->flush();
            return $this->json(
                [
                    'status_code' => Response::HTTP_CREATED,
                    'order_id' => $pre_order->getId(),
                    'message' => 'Orden creada correctamente.'
                ],
                Response::HTTP_CREATED,
                ['Content-Type' => 'application/json']
            );
        } catch (Exception $e) {
            return $this->json(
                [
                    'status_code' => Response::HTTP_INTERNAL_SERVER_ERROR,
                    'message' => $e->getMessage()
                ],
                Response::HTTP_INTERNAL_SERVER_ERROR,
                ['Content-Type' => 'application/json']
            );
        }
    }

    /**
     * @Route("/order/{order_id}", name="api_customer_order_by_id",methods={"GET","PATCH"})
     */
    public function order(
        $order_id,
        StatusOrderTypeRepository $statusOrderTypeRepository,
        OrdersRepository $ordersRepository,
        CountriesRepository $countriesRepository,
        StatesRepository $statesRepository,
        CitiesRepository $citiesRepository,
        ShippingTypesRepository $shippingTypesRepository,
        Request $request,
        EntityManagerInterface $em,
        CustomerAddressesRepository $customerAddressesRepository,
        RegistrationTypeRepository $registrationTypeRepository,
        HttpClientInterface $client,
        StatusTypeTransactionRepository $statusTypeTransactionRepository,
        OrdersProductsRepository $ordersProductsRepository,
        TransactionsRepository $transactionsRepository,
        PaymentTypeRepository $paymentTypeRepository,
        SendOrderToCrm $sendOrderToCrm
    ): Response {

        if (!(int)$order_id) {
            return $this->json(
                [
                    'status_code' => Response::HTTP_CONFLICT,
                    'message' => 'El ID indicado, no es valido.'
                ],
                Response::HTTP_CONFLICT,
                ['Content-Type' => 'application/json']
            );
        }

        $order = $ordersRepository->findOneBy([
            'id' => $order_id,
            'customer' => $this->customer->getId(),
        ]);

        if (!$order) {
            return $this->json(
                [
                    'status_code' => Response::HTTP_NOT_FOUND,
                    'message' => 'No se encontron orden con el ID indicado.'
                ],
                Response::HTTP_NOT_FOUND,
                ['Content-Type' => 'application/json']
            );
        }
        if ($order->getStatus()->getId() != Constants::STATUS_ORDER_PENDING) {
            return $this->json(
                [
                    'status' => false,
                    'status_code' => Response::HTTP_CONFLICT,
                    'message' => 'Esta orden ya se encuentra procesada.'
                ],
                Response::HTTP_CONFLICT,
                ['Content-Type' => 'application/json']
            );
        }
        if ($request->getMethod() == 'GET') {

            $paymentsTypes = $paymentTypeRepository->getPayments();

            $bill_address = $customerAddressesRepository->findOneBy(['active' => true, 'customer' => $this->customer, 'billing_address' => true], ['id' => 'DESC']);

            $recipes_addresses = $customerAddressesRepository->getRecipienAddress($this->customer);



            $recipes_addresses_data = [];

            foreach ($recipes_addresses as $recipe_address) {
                $recipes_addresses_data[] = $recipe_address ? $recipe_address->getAddressDataToOrder() : [];
            }

            $sumaProductos = 0;
            $sumaTotalPrecioProductosSinDescuentos = 0.00;
            $descuento = 0.00;
            $totalOrder = 0.00;
            $ITBIS = 0.00;
            $orders_products_array = $order->getOrdersProducts();
            $orders_products_result = [];

            foreach ($orders_products_array as $order_product) {
                $orders_products_result[] = [
                    'quantity' => '(x' . $order_product->getQuantity() . ' Unit)',
                    'name' => $order_product->getProduct()->getName(),
                    'price' => (string)$order_product->getProduct()->getPrice(),
                ];
                $sumaProductos += $order_product->getQuantity();
                $sumaTotalPrecioProductosSinDescuentos += ($order_product->getQuantity() * $order_product->getProduct()->getPrice());
                $descuentoDelProducto = ($order_product->getProduct()->getDiscountActive() ? ((($order_product->getProduct()->getPrice() / 100) * $order_product->getProduct()->getDiscountActive()) * $order_product->getQuantity()) : 0);
                $precioConDescuento = $order_product->getProduct()->getPrice() - $descuentoDelProducto; //POR AHORA NO LO USO
                $descuento += $descuentoDelProducto;
            }
            $totalOrder = ($sumaTotalPrecioProductosSinDescuentos - $descuento);
            $ITBIS = $totalOrder - ($totalOrder / 1.18);

            $orderToSend = [
                'status' => (string)$order->getStatus()->getId(),
                'paymentsTypes' => $paymentsTypes,
                'orderPlaced' => $order->getCreatedAt()->format('d-m-Y'),
                'total' => number_format($totalOrder, 2, ',', '.'), // revisar, podria ser.. $order->getTotalOrder()
                'sendTo' => $order->getReceiverName() ?: '',
                'numberOrder' => (string)$order->getId(),
                'detail' => [
                    'items' => $orders_products_result,
                    'products' => [
                        'total' => (string)$sumaProductos,
                        'totalPrice' => number_format($sumaTotalPrecioProductosSinDescuentos, 2, ',', '.'),
                    ],
                    "productDiscount" => $descuento,
                    "promocionalDiscount" => (string)$order->getPromotionalCodeDiscount() ?: '0', //esta funcion no esta habilitada todavia 27/12/2023
                    "tax" => $ITBIS,
                    "totalOrderPrice" => number_format($totalOrder, 2, ',', '.'),
                ],
                'receiptOfPayment' => $order->getPaymentsFiles(),
                'bill' => $order->getBillFile() ?: '',
                'bill_address' => $bill_address ? $bill_address->getAddressDataToOrder() : null,
                'recipient_address' => $recipes_addresses_data ?: null
            ];


            return $this->json(
                $orderToSend,
                Response::HTTP_ACCEPTED,
                ['Content-Type' => 'application/json']
            );
        }


        //revisar que los productos sigan estando disponibles.

        // SI ES PATCH ACTUALIZA LA ORDEN Y PASA A ESTADO ACEPTADO
        //$order <-- ya tengo la orden
        //$this->customer <-- el cliente.
        try {
            $body = $request->getContent();
            $data = json_decode($body, true);


            //VALORES NECESARIOS PARA HACER UPDATE A LA ORDEN

            $status_order_id = $statusOrderTypeRepository->find(Constants::STATUS_ORDER_OPEN);
            $registration_type_id = $registrationTypeRepository->find(1); //1 =  registracion web
            $country_bill = $countriesRepository->find($data['order']['billData']['country_id']);
            $state_bill = $statesRepository->find($data['order']['billData']['state_id']);
            $city_bill = $citiesRepository->find($data['order']['billData']['city_id']);

            $country_recipient = $countriesRepository->find($data['order']['recipient']['country_id']);
            $state_recipient = $statesRepository->find($data['order']['recipient']['state_id']);
            $city_recipient = $citiesRepository->find($data['order']['recipient']['city_id']);

            //esto setea international shipping = 2
            $international_shipping_id = $shippingTypesRepository->find(2);

            //FIN VALORES NECESARIOS

            $entityManager = $em;

            $customer_bill_address = @$data['order']['billData']['address_id'] ? $customerAddressesRepository->find($data['order']['billData']['address_id']) : null;
            $customer_recipient_address = @$data['order']['recipient']['address_id'] ? $customerAddressesRepository->find($data['order']['recipient']['address_id']) : null;

            if (!$customer_bill_address) { //SI CUSTOMER_BILL_ADDRESS ES NULO INSERTI DATOS DE FACTURACION
                $customer_bill_address = new CustomerAddresses();
            }

            //SETEO DIRECCION DEL CLIENTE COMO DIRECCION DE FACTURACION
            $customer_bill_address->setCustomer($this->customer);
            $customer_bill_address->setRegistrationDate(new \DateTime());
            $customer_bill_address->setActive(true);
            $customer_bill_address->setCountry($country_bill);
            $customer_bill_address->setState($state_bill);
            $customer_bill_address->setCity($city_bill);
            $customer_bill_address->setName($data['order']['billData']['name']);
            $customer_bill_address->setIdentityType($data['order']['billData']['identity_type']);
            $customer_bill_address->setIdentityNumber($data['order']['billData']['identity_number']);
            $customer_bill_address->setStreet($data['order']['billData']['address']);
            $customer_bill_address->setRegistrationType($registration_type_id);
            $customer_bill_address->setPostalCode($data['order']['billData']['code_zip']);
            $customer_bill_address->setAdditionalInfo(@$data['order']['billData']['additional_info'] ?: '');
            $customer_bill_address->setPhone($data['order']['billData']['phone']);
            $customer_bill_address->setEmail($data['order']['billData']['email']);
            $customer_bill_address->setHomeAddress(false);
            $customer_bill_address->setRecipeAddress(false);
            $customer_bill_address->setBillingAddress(true);

            $customerAddressesRepository->updateBillingAddress($this->customer->getId());
            $entityManager->persist($customer_bill_address);
            if (!$data['order']['same_address']) {
                if (!$customer_recipient_address) {
                    $customer_recipient_address = new CustomerAddresses();
                }
                //SETEO direccion del destcustomer_inatario
                $customer_recipient_address->setCustomer($this->customer);
                $customer_recipient_address->setRegistrationDate(new \DateTime());
                $customer_recipient_address->setActive(true);
                $customer_recipient_address->setCountry($country_recipient);
                $customer_recipient_address->setState($state_recipient);
                $customer_recipient_address->setCity($city_recipient);
                $customer_recipient_address->setName($data['order']['recipient']['name']);
                $customer_recipient_address->setIdentityType($data['order']['recipient']['identity_type']);
                $customer_recipient_address->setIdentityNumber($data['order']['recipient']['identity_number']);
                $customer_recipient_address->setStreet($data['order']['recipient']['address']);
                $customer_recipient_address->setRegistrationType($registration_type_id);
                $customer_recipient_address->setPostalCode($data['order']['recipient']['code_zip']);
                $customer_recipient_address->setAdditionalInfo(@$data['order']['recipient']['additional_info'] ?: '');
                $customer_recipient_address->setPhone($data['order']['recipient']['phone']);
                $customer_recipient_address->setEmail($data['order']['recipient']['email']);
                $customer_recipient_address->setRecipeAddress(true);
                $customer_recipient_address->setHomeAddress(true);
                $customer_recipient_address->setBillingAddress(false);

                $customerAddressesRepository->updateHomeAddress($this->customer->getId());
                $entityManager->persist($customer_recipient_address);
            } else {
                $customer_recipient_address = $customer_bill_address;
            }

            $products_in_order = $ordersProductsRepository->findBy([
                'number_order' => $order,
            ]);

            $totalProductDiscount = 0;
            $totalPreciosSinDescuentos = 0;
            foreach ($products_in_order as $product_in_order) {
                $product_in_order->setPrice($product_in_order->getProduct()->getPrice());
                $product_in_order->setDiscount($product_in_order->getProduct()->getDiscountActive() ?: 0);
                $product_in_order->setProductDiscount($product_in_order->getProduct()->getDiscountActiveObject());
                $entityManager->persist($product_in_order);

                $totalProductDiscount += $product_in_order->getQuantity() * ($product_in_order->getProduct()->getPrice() - $product_in_order->getProduct()->getRealPrice());
                $totalPreciosSinDescuentos += $product_in_order->getQuantity() * $product_in_order->getProduct()->getPrice();
            }
            $entityManager->flush();

            $totalOrder = $totalPreciosSinDescuentos - $totalProductDiscount; //por ahora es asi, falta descuento x codigo promocional.
            $ITBIS = $totalOrder - ($totalOrder / 1.18);

            //Creo lastpayment para saber cual fue el ultimo tipo de pago, porque si es diferente al creado anterior tengo que crear un nuevo session id
            $lastPaymentType = $order->getPaymentType() ? $order->getPaymentType()->getId() : null;

            $order->setPaymentType($paymentTypeRepository->find($data['order']['paymentTypeId']));

            $order->setTax($ITBIS); //se cobra el itbis en RD
            $order->setTotalProductDiscount($totalProductDiscount);
            //por ahora esto se setea a 0
            $order->setPromotionalCodeDiscount(0); //seteamos esto a 0 por el momento
            $order->setShippingCost(0); //seteamos esto a 0 por el momento
            $order->setShippingDiscount(0); //seteamos esto a 0 por el momento
            $order->setPaypalServiceCost(0); //seteamos esto a 0 por el momento

            $order->setTotalOrder($totalOrder);

            $order->setBillAddress($customer_bill_address);
            $order->setBillCountry($country_bill);
            $order->setBillState($state_bill);
            $order->setBillCity($city_bill);
            $order->setCustomerIdentityType($data['order']['billData']['identity_type']);
            $order->setCustomerIdentityNumber($data['order']['billData']['identity_number']);
            $order->setInternationalShipping(TRUE);
            $order->setShipping(TRUE);
            $order->setBillAddressOrder($data['order']['billData']['address']);
            $order->setBillPostalCode($data['order']['billData']['code_zip']);
            $order->setBillAdditionalInfo(@$data['order']['billData']['additional_info'] ?: '');
            $order->setBillName($data['order']['billData']['name']);
            $order->setBillIdentityType($data['order']['billData']['identity_type']);
            $order->setBillIdentityNumber($data['order']['billData']['identity_number']);
            $order->setReceiverCountry($country_recipient);
            $order->setReceiverState($state_recipient);
            $order->setReceiverCity($city_recipient);
            $order->setReceiverName($data['order']['recipient']['name']);
            $order->setReceiverDocumentType($data['order']['recipient']['identity_type']);
            $order->setReceiverDocument($data['order']['recipient']['identity_number']);
            $order->setReceiverPhoneCell($data['order']['recipient']['phone']);
            $order->setReceiverEmail($data['order']['recipient']['email']);
            $order->setReceiverAddressOrder($data['order']['recipient']['address']);
            $order->setReceiverCodZip($data['order']['recipient']['code_zip']);
            $order->setReceiverAdditionalInfo(@$data['order']['recipient']['additional_info'] ?: '');
            $order->setShippingType($international_shipping_id);
            $order->setReceiverAddress($customer_recipient_address);
            $order->setFiscalInvoiceRequired($data['order']['fiscalInvoiceId'] == 1 ? true : false);


            if ($data['order']['paymentTypeId'] != Constants::PAYMENT_TYPE_TRANSACTION) {

                $fechaActual = new DateTime();

                $fechaActual->modify('-30 minutes');

                $criterios = [
                    'number_order' => $order,
                    'status' => $statusTypeTransactionRepository->find(Constants::STATUS_TRANSACTION_NEW),
                ];

                $transaction = $transactionsRepository->findOneBy($criterios, ['created_at' => 'DESC']);

                if (!$transaction || !($transaction->getCreatedAt() >= $fechaActual) || !(@$lastPaymentType == $data['order']['paymentTypeId'])) {
                    $transactionsRepository->cancelOldNewTransaction($order);
                    $transaction =  new Transactions;
                    $transaction->setNumberOrder($order);
                    $transaction->setStatus($statusTypeTransactionRepository->find(Constants::STATUS_TRANSACTION_NEW));
                    $transaction->setAmount($totalOrder);
                    $transaction->setTax($ITBIS);
                    $em->persist($transaction);
                    $em->flush();
                }

                try {
                    $response_session = $client->request(
                        'POST',
                        $_ENV['CARDNET_URL_SESSION'],
                        [
                            'json'  => [
                                "MerchantType" => $data['order']['paymentTypeId'] == Constants::PAYMENT_TYPE_CARDNET_ONE_PAYMENT ? $_ENV['CARDNET_MERCHANT_TYPE'] : $_ENV['CARDNET_MERCHANT_TYPE_CUOTAS'],
                                "MerchantNumber" => $data['order']['paymentTypeId'] == Constants::PAYMENT_TYPE_CARDNET_ONE_PAYMENT ?  $_ENV['CARDNET_MERCHANT_NUMBER'] : $_ENV['CARDNET_MERCHANT_NUMBER_CUOTAS'],
                                "MerchantTerminal" => $data['order']['paymentTypeId'] == Constants::PAYMENT_TYPE_CARDNET_ONE_PAYMENT ?  $_ENV['CARDNET_MERCHANT_TERMINAL'] : $_ENV['CARDNET_MERCHANT_TERMINAL_CUOTAS'],
                                "TransactionType" => $_ENV['CARDNET_TRANSACTION_TYPE'],
                                "CurrencyCode" => $_ENV['CARDNET_CURRENCY_CODE'],
                                "AcquiringInstitutionCode" => $_ENV['CARDNET_ACQUIRING_INSTITUTION_CODE'],
                                "ReturnUrl" => $_ENV['CARDNET_SUCCESS_URL'] . '?customer=' . $this->customer->getId() . '&order=' . $order->getId() . '&transaction=' . $transaction->getId() . '&status=1', //1 es accepted
                                "CancelUrl" => $_ENV['CARDNET_CANCEL_URL'] . '?customer=' . $this->customer->getId() . '&order=' . $order->getId() . '&transaction=' . $transaction->getId() . '&status=2', //2 es rejected
                                "PageLanguaje" => $_ENV['CARDNET_PAGE_LANGUAGE'],
                                "OrdenId" => $order->getId(),
                                "TransactionId" => $transaction->getId(),
                                "Tax" => (int)($ITBIS * 100),
                                "MerchantName" => $_ENV['CARDNET_MERCHANT_NAME'],
                                "AVS" => $order->getReceiverAddressOrder(),
                                "Amount" => (int)($totalOrder * 100),
                                "3DS_email" => $data['order']['billData']['email'],
                                "3DS_mobilePhone" => $data['order']['billData']['phone'],
                                "3DS_workPhone" => " ",
                                "3DS_homePhone" => " ",
                                "3DS_billAddr_line1" => $data['order']['billData']['address'],
                                "3DS_billAddr_line2" => $data['order']['billData']['additional_info'],
                                "3DS_billAddr_line3" => " ",
                                "3DS_billAddr_city" => $city_bill->getName(),
                                "3DS_billAddr_state" => $state_bill->getName(),
                                "3DS_billAddr_country" => $country_bill->getName(),
                                "3DS_billAddr_postCode" => $data['order']['billData']['code_zip'],
                                "3DS_shipAddr_line1" => $data['order']['recipient']['address'],
                                "3DS_shipAddr_line2" => $data['order']['recipient']['additional_info'],
                                "3DS_shipAddr_line3" => " ",
                                "3DS_shipAddr_city" => $city_recipient->getName(),
                                "3DS_shipAddr_state" => $state_recipient->getName(),
                                "3DS_shipAddr_country" => $country_recipient->getName(),
                                "3DS_shipAddr_postCode" => $data['order']['recipient']['code_zip']
                            ],
                        ]
                    );
                    $body = $response_session->getContent(false);
                    $data_session = json_decode($body, true);

                    $transaction->setSession($data_session['SESSION']);
                    $transaction->setSessionKey($data_session['session-key']);
                    $em->persist($transaction);
                    $em->persist($order);
                    $em->flush();

                    $responseCardnet = [
                        'status' => true,
                        'status_code' => Response::HTTP_ACCEPTED,
                        'paymentTypeId' => $order->getPaymentType()->getId(),
                        'urlCardnet' => $order->getPaymentType()->getUrl(),
                        'SESSION' => $data_session['SESSION'],
                    ];

                    return $this->json(
                        $responseCardnet,
                        Response::HTTP_ACCEPTED,
                        ['Content-Type' => 'application/json']
                    );
                } catch (TransportExceptionInterface $e) {
                    return $this->json(
                        [
                            'status' => false,
                            'status_code' => Response::HTTP_NOT_FOUND,
                            'message' => $e->getMessage()
                        ],
                        Response::HTTP_NOT_FOUND,
                        ['Content-Type' => 'application/json']
                    );
                }
            }
            //envio por helper los datos del cliente al crm
            $order->setStatus($status_order_id);

            $entityManager->persist($order);
            $entityManager->flush();

            $sendOrderToCrm->SendOrderToCrm($order);

            //actualizar orden con los datos de contacto.

            //enviar orden al crm

            //retornar mensaje ok
            return $this->json(
                [
                    'status' => true,
                    'status_code' => Response::HTTP_ACCEPTED,
                    'paymentTypeId' => $order->getPaymentType()->getId(),
                    'urlCardnet' => NULL,
                    'SESSION' => NULL,
                    'message' => 'Su orden ya se encuentra en proceso.'
                ],
                Response::HTTP_ACCEPTED,
                ['Content-Type' => 'application/json']
            );
        } catch (Exception $e) {
            return $this->json(
                [
                    'status' => false,
                    'status_code' => Response::HTTP_INTERNAL_SERVER_ERROR,
                    'message' => 'Error: ' . $e->getMessage()
                ],
                Response::HTTP_INTERNAL_SERVER_ERROR,
                ['Content-Type' => 'application/json']
            );
        }
    }

    /**
     * @Route("/orders", name="api_customer_orders",methods={"GET"})
     */
    public function orders(
        OrdersRepository $ordersRepository
    ): Response {

        $this->customer->getId();
        $orders = $ordersRepository->findOrdersByCustomerId($this->customer->getId());

        $orderToSend = [];
        foreach ($orders as $order) {
            $sumaProductos = 0;
            $sumaTotalPrecioProductos = 0.00;
            $orders_products_array = $order->getOrdersProducts();
            $orders_products_result = [];

            foreach ($orders_products_array as $order_product) {
                $orders_products_result[] = [
                    'quantity' => '(x' . $order_product->getQuantity() . ' Unit)',
                    'name' => $order_product->getProduct()->getName(),
                    'price' => (string)$order_product->getPrice(),
                ];
                $sumaProductos += $order_product->getQuantity();
                $sumaTotalPrecioProductos += ($order_product->getQuantity() * $order_product->getProduct()->getRealPrice());
            }


            $orderToSend[] = [
                'status' => (string)$order->getStatus()->getId(),
                'orderPlaced' => $order->getCreatedAt()->format('d-m-Y'),
                'total' => (string) $order->getTotalOrder() ?: $sumaTotalPrecioProductos,
                'sendTo' => $order->getReceiverName() ?: '',
                'numberOrder' => (string)$order->getId(),
                'detail' => [
                    'items' => $orders_products_result,
                    'products' => [
                        'total' => (string)$sumaProductos,
                        'totalPrice' => (string)$order->getTotalOrder() ?: $sumaTotalPrecioProductos,
                    ],
                    "productDiscount" => (string)$order->getTotalProductDiscount() ?: '0',
                    "promocionalDiscount" => (string)$order->getPromotionalCodeDiscount() ?: '0',
                    "tax" => (string)$order->getTax() ?: '0',
                    "totalOrderPrice" => (string)$order->getTotalOrder() ?: $sumaTotalPrecioProductos,
                ],
                'receiptOfPayment' => $order->getPaymentsReceivedFiles() ? ($order->getPaymentsReceivedFiles()[0] ? $order->getPaymentsReceivedFiles()[0]->getPaymentReceivedFile() : '') : '', //revisar, recibe mas de un recibo de recepcion de pago
                'bill' => $order->getBillFile() ?: '',
                'proforma_bill' => $order->getProformaBillFile() ?: ''
            ];
        }


        return $this->json(
            $orderToSend,
            Response::HTTP_ACCEPTED,
            ['Content-Type' => 'application/json']
        );
    }
}
