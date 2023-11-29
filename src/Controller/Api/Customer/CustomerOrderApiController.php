<?php

namespace App\Controller\Api\Customer;

use App\Constants\Constants;
use App\Entity\Orders;
use App\Entity\OrdersProducts;
use App\Helpers\SendOrderToCrm;
use App\Repository\CommunicationStatesBetweenPlatformsRepository;
use App\Repository\CustomerRepository;
use App\Repository\OrdersRepository;
use App\Repository\ProductRepository;
use App\Repository\ShoppingCartRepository;
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
     * @Route("/order/{order_id}", name="api_customer_order",methods={"GET","POST"})
     */
    public function order(
        $order_id,
        Request $request,
        StatusOrderTypeRepository $statusOrderTypeRepository,
        ShoppingCartRepository $shoppingCartRepository,
        StatusTypeShoppingCartRepository $statusTypeShoppingCartRepository,
        EntityManagerInterface $em,
        SendOrderToCrm $sendOrderToCrm,
        CommunicationStatesBetweenPlatformsRepository $communicationStatesBetweenPlatformsRepository,
        OrdersRepository $ordersRepository
        // CustomerAd
    ): Response {

        switch ($request->getMethod()) {
            case 'GET':
                return $this->json(
                    [
                        'ok' => 'ok',
                        'order_id' => $order_id
                    ],
                    Response::HTTP_OK,
                    ['Content-Type' => 'application/json']
                );
                $order = $ordersRepository->findOrderByCustomerId($this->customer->getId(), $order_id);
                $items = [];
                $bill_data = [
                    "bill_data" => [
                        "identity_type" => "DNI",
                        "identity_number" => "34987273",
                        "country_id" => 11,
                        "country_name" => "Argentina",
                        "state_id" => 4545,
                        "state_name" => "Buenos Aires",
                        "city_id" => 42022,
                        "city_name" => "Ciudad Autonoma de Buenos Aires",
                        "code_zip" => "abc123",
                        "additional_info" => "informacion adicional",
                        "address" => "Calle 123 4to A"
                    ]
                ];


                switch ($order->getStatus()->getId()) {
                    case Constants::STATUS_ORDER_PENDING:
                        foreach ($order->getOrdersProducts() as $order_product) {
                            $items[] = [
                                "id" => $order_product->getProduct()->getId(),
                                "name" => $order_product->getProduct()->getName(),
                                "quantity" => $order_product->getQuantity(),
                                "price" => $order_product->getProduct()->getPrice(),
                                "discount_price" => $order_product->getProduct()->getDiscountActive() ?  ($order_product->getProduct()->getPrice() - (($order_product->getProduct()->getPrice() / 100) * $order_product->getProduct()->getDiscountActive())) : 0,
                            ];
                        }
                        $order = [
                            'items' => $items
                        ];
                        break;
                    default:
                        dd('default case');
                        break;
                }


                return $this->json(
                    $order,
                    Response::HTTP_OK,
                    ['Content-Type' => 'application/json']
                );
                /*
                {
                    "items": [
                        {
                            "id": 10,
                            "name": "Prueba producto 1",
                            "quantity": 5,
                            "price": 10,
                            "old_price":
                        },
                        {
                            "id": 11,
                            "name": "Prueba producto 2",
                            "quantity": 4,
                            "price": 20,
                            "old_price":
                        },
                        {
                            "id": 13,
                            "name": "Prueba producto 3",
                            "quantity": 1,
                            "price": 300,
                            "old_price":
                        }
                    ],
                    "bill_data": {
                        "identity_type": "DNI",
                        "identity_number": "34987273",
                        "country_id": 11,
                        "country_name": "Argentina",
                        "state_id": 4545,
                        "state_name": "Buenos Aires",
                        "city_id": 42022,
                        "city_name": "Ciudad Autonoma de Buenos Aires",
                        "code_zip" : "abc123",
                        "additional_info": "informacion adicional",
                        "address": "Calle 123 4to A"
                    },
                    "recipients": [
                        {
                            "recipient_id": 1,
                            "country_name": "Argentina",
                            "state_name": "Córdoba",
                            "city_name": "Cosquin",
                            "recipient_name": "Destinatario prueba 1",
                            "address": "Direccion destinatario 1 23233",
                            "recipient_phone": "1163549766"
                        },
                        {
                            "recipient_id": 2,
                            "country_name": "Argentina",
                            "state_name": "Córdoba",
                            "city_name": "La falda",
                            "recipient_name": "Destinatario prueba 2",
                            "address": "Direccion destinatario 2 23233",
                            "recipient_phone": "1163549766"
                        },
                        {
                            "recipient_id": 3,
                            "country_name": "Argentina",
                            "state_name": "Córdoba",
                            "city_name": "Córdoba Capital",
                            "recipient_name": "Destinatario prueba 3",
                            "address": "Direccion destinatario 3 23233",
                            "recipient_phone": "1163549766"
                        }
                    ]
                }
                */

                $order = $ordersRepository->find(['id' => $order_id]);

                return $this->json(
                    $order->generateOrderToCRM(),
                    Response::HTTP_OK,
                    ['Content-Type' => 'application/json']
                );
            case 'POST':

                $body = $request->getContent();
                $data = json_decode($body, true);
        }

        $status_sent_crm = $communicationStatesBetweenPlatformsRepository->find(Constants::CBP_STATUS_PENDING);

        $shopping_cart_products = $shoppingCartRepository->findAllShoppingCartProductsByStatus($this->customer->getId(), 1);
        if (!$shopping_cart_products) {
            return $this->json(
                [
                    "shop_cart_list" => [],
                    'message' => 'No tiene productos en su lista de carrito.'
                ],
                Response::HTTP_ACCEPTED,
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
                    'quantity' => '(x'.$order_product->getQuantity().' Unit)',
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
                    "productDiscount" => (string)$order->getTotalProductDiscount()?:'0',
                    "promocionalDiscount" => (string)$order->getPromotionalCodeDiscount()?:'0',
                    "tax" => (string)$order->getTax()?:'0',
                    "totalOrderPrice"=> (string)$sumaTotalPrecioProductos,
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
