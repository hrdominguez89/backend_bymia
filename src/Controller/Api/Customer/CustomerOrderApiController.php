<?php

namespace App\Controller\Api\Customer;

use App\Constants\Constants;
use App\Entity\CustomerAddresses;
use App\Entity\Orders;
use App\Entity\OrdersProducts;
use App\Entity\Recipients;
use App\Helpers\SendOrderToCrm;
use App\Repository\CitiesRepository;
use App\Repository\CommunicationStatesBetweenPlatformsRepository;
use App\Repository\CountriesRepository;
use App\Repository\CustomerAddressesRepository;
use App\Repository\CustomerRepository;
use App\Repository\OrdersRepository;
use App\Repository\ProductRepository;
use App\Repository\RegistrationTypeRepository;
use App\Repository\ShippingTypesRepository;
use App\Repository\ShoppingCartRepository;
use App\Repository\StatesRepository;
use App\Repository\StatusOrderTypeRepository;
use App\Repository\StatusTypeShoppingCartRepository;
use Doctrine\ORM\EntityManagerInterface;
use Exception;
use Lexik\Bundle\JWTAuthenticationBundle\Encoder\JWTEncoderInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;


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
            $order_product
                ->setNumberOrder($pre_order)
                ->setProduct($shopping_cart_product->getProduct())
                ->setName($shopping_cart_product->getProduct()->getName())
                ->setSku($shopping_cart_product->getProduct()->getSku())
                ->setPartNumber($shopping_cart_product->getProduct()->getPartNumber() ?: null)
                ->setCod($shopping_cart_product->getProduct()->getCod() ?: null)
                ->setWeight($shopping_cart_product->getProduct()->getWeight() ?: null)
                ->setQuantity($shopping_cart_product->getQuantity());
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
                    'status' => true,
                    'status_code' => Response::HTTP_CONFLICT,
                    'message' => 'Esta orden ya se encuentra procesada.'
                ],
                Response::HTTP_CONFLICT,
                ['Content-Type' => 'application/json']
            );
        }
        if ($request->getMethod() == 'GET') {
            $sumaProductos = 0;
            $sumaTotalPrecioProductos = 0.00;
            $orders_products_array = $order->getOrdersProducts();
            $orders_products_result = [];

            foreach ($orders_products_array as $order_product) {
                $orders_products_result[] = [
                    'quantity' => '(x' . $order_product->getQuantity() . ' Unit)',
                    'name' => $order_product->getProduct()->getName(),
                    'price' => (string)$order_product->getProduct()->getPrice(),
                ];
                $sumaProductos += $order_product->getQuantity();
                $sumaTotalPrecioProductos += ($order_product->getQuantity() * $order_product->getProduct()->getPrice());
            }


            $orderToSend = [
                'status' => (string)$order->getStatus()->getId(),
                'orderPlaced' => $order->getCreatedAt()->format('d-m-Y'),
                'total' => (string) $sumaTotalPrecioProductos, // revisar, podria ser.. $order->getTotalOrder()
                'sendTo' => $order->getReceiverName() ?: '',
                'numberOrder' => (string)$order->getId(),
                'detail' => [
                    'items' => $orders_products_result,
                    'products' => [
                        'total' => (string)$sumaProductos,
                        'totalPrice' => (string)$sumaTotalPrecioProductos,
                    ],
                    "productDiscount" => (string)$order->getTotalProductDiscount() ?: '0',
                    "promocionalDiscount" => (string)$order->getPromotionalCodeDiscount() ?: '0',
                    "tax" => (string)$order->getTax() ?: '0',
                    "totalOrderPrice" => (string)$sumaTotalPrecioProductos,
                ],
                'receiptOfPayment' => $order->getPaymentsReceivedFiles() ? ($order->getPaymentsReceivedFiles()[0] ? $order->getPaymentsReceivedFiles()[0]->getPaymentReceivedFile() : '') : '', //revisar, recibe mas de un recibo de recepcion de pago
                'bill' => $order->getBillFile() ?: '',
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

            //SETEO DIRECCION DEL CLIENTE COMO DIRECCION DE FACTURACION
            $customer_address = new CustomerAddresses();
            $customer_address->setCustomer($this->customer);
            $customer_address->setRegistrationDate(new \DateTime());
            $customer_address->setActive(true);
            $customer_address->setCountry($country_bill);
            $customer_address->setState($state_bill);
            $customer_address->setCity($city_bill);
            $customer_address->setStreet($data['order']['billData']['address']);
            $customer_address->setRegistrationType($registration_type_id);
            $customer_address->setPostalCode($data['order']['billData']['code_zip']);
            $customer_address->setAdditionalInfo($data['order']['billData']['additional_info']);
            $customer_address->setHomeAddress(false);
            $customer_address->setBillingAddress(true);
            $customerAddressesRepository->updateBillingAddress($this->customer->getId());
            $entityManager = $em;
            $entityManager->persist($customer_address);

            //SETEO direccion del destinatario

            $recipient_address = new Recipients();
            $recipient_address->setCustomer($this->customer);
            $recipient_address->setCountry($country_recipient);
            $recipient_address->setState($state_recipient);
            $recipient_address->setCity($city_recipient);
            $recipient_address->setName($data['order']['recipient']['name']);
            $recipient_address->setIdentityType($data['order']['recipient']['identity_type']);
            $recipient_address->setIdentityNumber($data['order']['recipient']['identity_number']);
            $recipient_address->setAddress($data['order']['recipient']['address']);
            $recipient_address->setZipCode($data['order']['recipient']['code_zip']);
            $recipient_address->setPhone($data['order']['recipient']['phone']);
            $recipient_address->setEmail($data['order']['recipient']['email']);
            $entityManager->persist($recipient_address);


            //por ahora esto se setea a 0
            $order->setTotalProductDiscount(0);
            $order->setPromotionalCodeDiscount(0);
            $order->setTax(0);
            $order->setShippingCost(0);
            $order->setShippingDiscount(0);
            $order->setPaypalServiceCost(0);
            //

            $order->setTotalOrder($order->getSubtotal());

            $order->setStatus($status_order_id);
            $order->setBillAddress($customer_address);
            $order->setBillCountry($country_bill);
            $order->setBillState($state_bill);
            $order->setBillCity($city_bill);
            $order->setCustomerIdentityType($data['order']['billData']['identity_type']);
            $order->setCustomerIdentityNumber($data['order']['billData']['identity_number']);
            $order->setInternationalShipping(TRUE);
            $order->setShipping(TRUE);
            $order->setBillAddressOrder($data['order']['billData']['address']); //ver de insertar en customer address
            $order->setBillPostalCode($data['order']['billData']['code_zip']); //ver de insertar en customer address
            $order->setBillAdditionalInfo($data['order']['billData']['additional_info'] ?: null); //ver de insertar en customer address
            $order->setReceiverCountry($country_recipient);
            $order->setReceiverState($state_recipient);
            $order->setReceiverCity($city_recipient);
            $order->setReceiverName($data['order']['recipient']['name']);
            $order->setReceiverDocumentType($data['order']['recipient']['identity_type']);
            $order->setReceiverDocument($data['order']['recipient']['identity_number']);
            $order->setReceiverPhoneCell($data['order']['recipient']['phone']);
            $order->setReceiverEmail($data['order']['recipient']['email']);
            $order->setReceiverAddress($data['order']['recipient']['address']);
            $order->setReceiverCodZip($data['order']['recipient']['code_zip']);
            $order->setReceiverAdditionalInfo($data['order']['recipient']['additional_info'] ?: '');
            $order->setShippingType($international_shipping_id);
            $order->setRecipient($recipient_address);

            //envio por helper los datos del cliente al crm

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
        Request $request,
        StatusOrderTypeRepository $statusOrderTypeRepository,
        ShoppingCartRepository $shoppingCartRepository,
        StatusTypeShoppingCartRepository $statusTypeShoppingCartRepository,
        EntityManagerInterface $em,
        SendOrderToCrm $sendOrderToCrm,
        CommunicationStatesBetweenPlatformsRepository $communicationStatesBetweenPlatformsRepository,
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
                    'price' => (string)$order_product->getProduct()->getPrice(),
                ];
                $sumaProductos += $order_product->getQuantity();
                $sumaTotalPrecioProductos += ($order_product->getQuantity() * $order_product->getProduct()->getPrice());
            }


            $orderToSend[] = [
                'status' => (string)$order->getStatus()->getId(),
                'orderPlaced' => $order->getCreatedAt()->format('d-m-Y'),
                'total' => (string) $sumaTotalPrecioProductos, // revisar, podria ser.. $order->getTotalOrder()
                'sendTo' => $order->getReceiverName() ?: '',
                'numberOrder' => (string)$order->getId(),
                'detail' => [
                    'items' => $orders_products_result,
                    'products' => [
                        'total' => (string)$sumaProductos,
                        'totalPrice' => (string)$sumaTotalPrecioProductos,
                    ],
                    "productDiscount" => (string)$order->getTotalProductDiscount() ?: '0',
                    "promocionalDiscount" => (string)$order->getPromotionalCodeDiscount() ?: '0',
                    "tax" => (string)$order->getTax() ?: '0',
                    "totalOrderPrice" => (string)$sumaTotalPrecioProductos,
                ],
                'receiptOfPayment' => $order->getPaymentsReceivedFiles() ? ($order->getPaymentsReceivedFiles()[0] ? $order->getPaymentsReceivedFiles()[0]->getPaymentReceivedFile() : '') : '', //revisar, recibe mas de un recibo de recepcion de pago
                'bill' => $order->getBillFile() ?: '',
            ];
        }


        return $this->json(
            $orderToSend,
            Response::HTTP_ACCEPTED,
            ['Content-Type' => 'application/json']
        );
    }
}
