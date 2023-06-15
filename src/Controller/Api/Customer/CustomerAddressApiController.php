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
use DateTime;
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
class CustomerAddressApiController extends AbstractController
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
     * @Route("/pre-ordera", name="api_customer_pre_ordera",methods={"POST"})
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

        // Iterar sobre los productos enviados en la solicitud
        foreach ($data['products'] as $product_cart) {
            $productId = $product_cart['product_id'];
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
            if ($product_on_cart->getAvailable() < $quantity) {
                $product_on_cart->setQuantity($quantity);
                $errors['product_quantity_not_available'][] = $product_on_cart->getBasicDataProduct();
                
                continue;
            }

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
                ->setPartNumber($shopping_cart_product->getProduct()->getPartNumber()?:null)
                ->setCod($shopping_cart_product->getProduct()->getCod()?:null)
                ->setWeight($shopping_cart_product->getProduct()->getWeight()?:null)
                ->setQuantity($shopping_cart_product->getQuantity())
            ;
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
     * @Route("/ordera/{id}", name="api_customer_ordera",methods={"GET","POST"})
     */
    public function order(
        $id,
        Request $request,
        StatusOrderTypeRepository $statusOrderTypeRepository,
        ShoppingCartRepository $shoppingCartRepository,
        StatusTypeShoppingCartRepository $statusTypeShoppingCartRepository,
        EntityManagerInterface $em,
        SendOrderToCrm $sendOrderToCrm,
        CommunicationStatesBetweenPlatformsRepository $communicationStatesBetweenPlatformsRepository,
        OrdersRepository $ordersRepository
    ): Response {

        switch ($request->getMethod()) {
            case 'GET':

                $order = $ordersRepository->find(['id' => $id]);

                return $this->json(
                    $order->generateOrderToCRM(),
                    Response::HTTP_OK,
                    ['Content-Type' => 'application/json']
                );
            case 'PATCH':

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
     * @Route("/ordersa", name="api_customer_ordersa",methods={"GET"})
     */
    public function orders(
        $id,
        Request $request,
        StatusOrderTypeRepository $statusOrderTypeRepository,
        ShoppingCartRepository $shoppingCartRepository,
        StatusTypeShoppingCartRepository $statusTypeShoppingCartRepository,
        EntityManagerInterface $em,
        SendOrderToCrm $sendOrderToCrm,
        CommunicationStatesBetweenPlatformsRepository $communicationStatesBetweenPlatformsRepository,
        OrdersRepository $ordersRepository
    ): Response {

        switch ($request->getMethod()) {
            case 'GET':

                $order = $ordersRepository->find(['id' => $id]);

                return $this->json(
                    $order->generateOrderToCRM(),
                    Response::HTTP_OK,
                    ['Content-Type' => 'application/json']
                );
            case 'PATCH':

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

    
}
