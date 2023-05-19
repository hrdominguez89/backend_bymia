<?php

namespace App\Controller\Api\Customer;

use App\Constants\Constants;
use App\Entity\FavoriteProduct;
use App\Entity\Orders;
use App\Entity\OrdersProducts;
use App\Entity\ShoppingCart;
use App\Helpers\SendOrderToCrm;
use App\Repository\CommunicationStatesBetweenPlatformsRepository;
use App\Repository\CustomerRepository;
use App\Repository\FavoriteProductRepository;
use App\Repository\OrderRepository;
use App\Repository\OrdersRepository;
use App\Repository\ProductRepository;
use App\Repository\ShoppingCartRepository;
use App\Repository\StatusOrderTypeRepository;
use App\Repository\StatusTypeFavoriteRepository;
use App\Repository\StatusTypeShoppingCartRepository;
use App\Repository\WarehousesRepository;
use DateTime;
use Doctrine\ORM\EntityManagerInterface;
use Lexik\Bundle\JWTAuthenticationBundle\Encoder\JWTEncoderInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;


/**
 * @Route("/api/customer")
 */
class CustomerApiController extends AbstractController
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
     * @Route("/order", name="api_customer_order",methods={"POST"})
     */
    public function newOrder(
        Request $request,
        StatusOrderTypeRepository $statusOrderTypeRepository,
        ShoppingCartRepository $shoppingCartRepository,
        StatusTypeShoppingCartRepository $statusTypeShoppingCartRepository,
        EntityManagerInterface $em,
        SendOrderToCrm $sendOrderToCrm,
        CommunicationStatesBetweenPlatformsRepository $communicationStatesBetweenPlatformsRepository,
        OrdersRepository $ordersRepository
    ): Response {

        $body = $request->getContent();
        $data = json_decode($body, true);
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



        $new_order = new Orders();

        $new_order
            ->setCustomer($this->customer)
            ->setCustomerType($this->customer->getCustomerTypeRole())
            ->setCustomerName($this->customer->getName())
            ->setCustomerEmail($this->customer->getEmail())
            ->setCustomerPhoneCode($this->customer->getCountryPhoneCode())
            ->setCelPhoneCustomer($this->customer->getCelPhone())
            ->setPhoneCustomer($this->customer->getPhone())
            ->setCustomerIdentityType('DNI') //hardcode
            ->setCustomerIdentityNumber('34987273') //hardcode
            ->setInternationalShipping(TRUE) //hardcode
            ->setShipping(TRUE) //hardcode
            ->setBillFile(null); //hardcode


        foreach ($this->customer->getCustomerAddresses() as $address) {
            if ($address->getBillingAddress() && $address->getActive()) {
                $new_order
                    ->setBillAddress($address)
                    ->setBillCountry($address->getCountry())
                    ->setBillState($address->getState() ?: null)
                    ->setBillCity($address->getCity() ?: null)
                    ->setBillAddressOrder($address->getStreet() ?: '' . ' ' . $address->getNumberStreet() ?: '' . ', ' . $address->getFloor() ?: '' . ' ' . $address->getDepartment() ?: '',)
                    ->setBillPostalCode($address->getPostalCode() ?: '')
                    ->setBillAdditionalInfo($address->getAdditionalInfo() ?: '');
            }
        }


        $new_order
            ->setStatusSentCrm($status_sent_crm)
            ->setAttemptsSendCrm(0)
            ->setSubtotal(10.20) //hardcode
            ->setTotalProductDiscount(20.30) //hardcode
            ->setPromotionalCodeDiscount(0) //hardcode
            ->setTax(0) //hardcode
            ->setShippingCost(0) //hardcode
            ->setShippingDiscount(0) //hardcode
            ->setPaypalServiceCost(0) //hardcode
            ->setTotalOrder(200.50) //hardcode
            ->setStatus($statusOrderTypeRepository->findOneBy(["id" => Constants::STATUS_ORDER_OPEN]))
            ->setCreatedAt(new \DateTime())
            ->setReceiverName('nombre receptor') //hardcode
            ->setReceiverDocumentType('documento receptor typo') //hardcode
            ->setReceiverDocument('numero documento') //hardcode
            ->setReceiverPhoneCell('1122334455') //hardcode
            ->setReceiverPhoneHome(null) //hardcode
            ->setReceiverEmail('email@email.com') //hardcode
            ->setReceiverCountry($new_order->getBillCountry()) //hardcode
            ->setReceiverState($new_order->getBillState()) //hardcode
            ->setReceiverCity($new_order->getBillCity()) //hardcode
            ->setReceiverAddress('addreess receiver') //hardcode
            ->setReceiverCodZip('cod_zip') //hardcode
            ->setReceiverAdditionalInfo('additional_info') //hardcode
            ->setWarehouse($shopping_cart_products[0]->getProduct()->getInventory()->getWarehouse()) //revisar porque estoy forzando a un warehouse
            ->setInventoryId($shopping_cart_products[0]->getProduct()->getInventory()->getId()); //revisar porque estoy forzando a un inventario

        foreach ($shopping_cart_products as $shopping_cart_product) {
            $shopping_cart_product->setStatus($statusTypeShoppingCartRepository->findOneBy(["id" => Constants::STATUS_SHOPPING_CART_EN_ORDEN]));
            $order_product = new OrdersProducts();
            $order_product
                ->setNumberOrder($new_order)
                ->setProduct($shopping_cart_product->getProduct())
                ->setName($shopping_cart_product->getProduct()->getName())
                ->setSku($shopping_cart_product->getProduct()->getSku())
                ->setPartNumber($shopping_cart_product->getProduct()->getPartNumber())
                ->setCod($shopping_cart_product->getProduct()->getCod())
                ->setWeight($shopping_cart_product->getProduct()->getWeight())
                ->setPrice($shopping_cart_product->getProduct()->getPrice())
                ->setQuantity($shopping_cart_product->getQuantity())
                ->setDiscount(0); //harcode
            $em->persist($order_product);
            $em->persist($shopping_cart_product);
        }

        $em->persist($new_order);
        $em->flush();


        $new_order = $ordersRepository->findOneBy(['id'=>$new_order->getId()]);

        $response_send_to_crm = $sendOrderToCrm->SendOrderToCrm($new_order);

        if ($response_send_to_crm['status']) {
            return $this->json(
                $new_order->generateOrderToCRM(),
                $response_send_to_crm['status_code'],
                ['Content-Type' => 'application/json']
            );
        } else {
            $em->remove($new_order);
            return $this->json(
                [
                    'message' => $response_send_to_crm['message'],
                ],
                $response_send_to_crm['status_code'],
                ['Content-Type' => 'application/json']
            );
        }
    }

    /**
     * @Route("/cart/list", name="api_cart_list",methods={"GET"})
     */
    public function cartList(ShoppingCartRepository $shoppingCartRepository): Response
    {

        $shopping_cart_products = $shoppingCartRepository->findAllShoppingCartProductsByStatus($this->customer->getId(), 1);

        if (!$shopping_cart_products) { //retorno si el producto ya fue activado al carrito..
            return $this->json(
                [
                    "shop_cart_list" => [],
                    'message' => 'No tiene productos en su lista de carrito.'
                ],
                Response::HTTP_ACCEPTED,
                ['Content-Type' => 'application/json']
            );
        }

        $shopping_cart_products_list = [];
        foreach ($shopping_cart_products as $shopping_cart_product) {
            $shopping_cart_products_list[] = $shopping_cart_product->getProduct()->getBasicDataProduct();
        }

        return $this->json(
            [
                "shop_cart_list" => $shopping_cart_products_list,
            ],
            Response::HTTP_ACCEPTED,
            ['Content-Type' => 'application/json']
        );
    }

    /**
     * @Route("/cart/addAllFavorites", name="api_cart_add_all_favorites",methods={"POST"})
     */
    public function addAllFavorites(ShoppingCartRepository $shoppingCartRepository,  FavoriteProductRepository $favoriteProductRepository, StatusTypeFavoriteRepository $statusTypeFavoriteRepository, EntityManagerInterface $em): Response
    {

        $favorite_products = $favoriteProductRepository->findAllFavoriteProductsByStatus($this->customer->getId(), 1);

        if (!$favorite_products) { //retorno si el producto ya fue activado como favorito..
            return $this->json(
                [
                    'message' => 'No tiene productos en su lista de favoritos.'
                ],
                Response::HTTP_CONFLICT,
                ['Content-Type' => 'application/json']
            );
        }

        $status_on_shopping_cart = $statusTypeFavoriteRepository->find(3); //status agregar al carrito
        $status_active = $statusTypeFavoriteRepository->find(1); //status activo


        $actual_datetime = new DateTime();


        foreach ($favorite_products as $favorite_product) {
            $favorite_product
                ->setStatus($status_on_shopping_cart)
                ->setUpdatedAt($actual_datetime);

            $shopping_cart_product = new ShoppingCart;
            $shopping_cart_product
                ->setCustomer($this->customer)
                ->setProduct($favorite_product->getProduct())
                ->setFavorite($favorite_product)
                ->setStatus($status_active);

            $em->persist($favorite_product);
            $em->persist($shopping_cart_product);
        }

        $em->flush();

        $shopping_cart_products = $shoppingCartRepository->findAllShoppingCartProductsByStatus($this->customer->getId(), 1);

        $shopping_cart_products_list = [];
        foreach ($shopping_cart_products as $shopping_cart_product) {
            $shopping_cart_products_list[] = $shopping_cart_product->getProduct()->getBasicDataProduct();
        }

        return $this->json(
            [
                "wish_list" => [],
                "shop_cart_list" => $shopping_cart_products_list,
            ],
            Response::HTTP_ACCEPTED,
            ['Content-Type' => 'application/json']
        );
    }

    /**
     * @Route("/cart/add", name="api_cart_add",methods={"POST"})
     */
    public function cartAdd(Request $request, FavoriteProductRepository $favoriteProductRepository, StatusTypeFavoriteRepository $statusTypeFavoriteRepository, StatusTypeShoppingCartRepository $statusTypeShoppingCartRepository, ProductRepository $productRepository, ShoppingCartRepository $shoppingCartRepository, EntityManagerInterface $em): Response
    {

        $body = $request->getContent();
        $data = json_decode($body, true);

        $product = $productRepository->findActiveProductById($data['product_id']);
        if (!$product) { //retorno no se encontro producto activo.
            return $this->json(
                [
                    'message' => 'No fue posible encontrar el producto indicado.'
                ],
                Response::HTTP_NOT_FOUND,
                ['Content-Type' => 'application/json']
            );
        }

        $shopping_cart_product = $shoppingCartRepository->findShoppingCartProductByStatus((int)$product->getId(), (int)$this->customer->getId(), 1);

        if ($shopping_cart_product) { //retorno si el producto ya fue fue añadido al carrito..
            return $this->json(
                [
                    'message' => 'El producto ya se encuenta en su lista de carrito.'
                ],
                Response::HTTP_CONFLICT,
                ['Content-Type' => 'application/json']
            );
        }

        $favorite_product = $favoriteProductRepository->findFavoriteProductByStatus((int)$product->getId(), (int)$this->customer->getId(), 1);

        if ($favorite_product) { //retorno si el producto ya fue activado como favorito..
            $actual_datetime = new DateTime();
            $favorite_product
                ->setStatus($statusTypeFavoriteRepository->find(3))
                ->setUpdatedAt($actual_datetime);

            $em->persist($favorite_product);
        }

        $shopping_cart_product = new ShoppingCart;

        $shopping_cart_product
            ->setCustomer($this->customer)
            ->setProduct($product)
            ->setStatus($statusTypeShoppingCartRepository->find(1))
            ->setFavorite($favorite_product);

        $em->persist($shopping_cart_product);
        $em->flush();

        return $this->json(
            [
                'message' => 'Producto agregado al carrito.'
            ],
            Response::HTTP_CREATED,
            ['Content-Type' => 'application/json']
        );
    }

    /**
     * @Route("/cart/remove", name="api_cart_remove",methods={"POST"})
     */
    public function cartRemove(Request $request, StatusTypeShoppingCartRepository $statusTypeShoppingCartRepository, ProductRepository $productRepository, ShoppingCartRepository $shoppingCartRepository, EntityManagerInterface $em): Response
    {

        $body = $request->getContent();
        $data = json_decode($body, true);

        $product = $productRepository->findActiveProductById($data['product_id']);
        if (!$product) { //retorno no se encontro producto activo.
            return $this->json(
                [
                    'message' => 'No fue posible encontrar el producto indicado.'
                ],
                Response::HTTP_NOT_FOUND,
                ['Content-Type' => 'application/json']
            );
        }

        $shopping_cart_product = $shoppingCartRepository->findShoppingCartProductByStatus((int)$product->getId(), (int)$this->customer->getId(), 1);

        if (!$shopping_cart_product) { //retorno si el producto se encuentra en el carrito.
            return $this->json(
                [
                    'message' => 'El producto indicado no se encuentra su lista de carrito.'
                ],
                Response::HTTP_CONFLICT,
                ['Content-Type' => 'application/json']
            );
        }

        $shopping_cart_product
            ->setStatus($statusTypeShoppingCartRepository->find(2)) //status eliminado
            ->setUpdatedAt(new DateTime());

        $em->persist($shopping_cart_product);
        $em->flush();

        return $this->json(
            [
                'message' => 'Producto eliminado de tu lista de carrito.'
            ],
            Response::HTTP_ACCEPTED,
            ['Content-Type' => 'application/json']
        );
    }

    /**
     * @Route("/cart/removeAll", name="api_cart_removeAll",methods={"POST"})
     */
    public function cartRemoveAll(StatusTypeShoppingCartRepository $statusTypeShoppingCartRepository, ShoppingCartRepository $shoppingCartRepository, EntityManagerInterface $em): Response
    {

        $shopping_cart_products = $shoppingCartRepository->findAllShoppingCartProductsByStatus($this->customer->getId(), 1);

        if (!$shopping_cart_products) { //retorno si el producto ya se encuentra en carrito.
            return $this->json(
                [
                    'message' => 'No tiene productos en su lista de carrito.'
                ],
                Response::HTTP_CONFLICT,
                ['Content-Type' => 'application/json']
            );
        }

        $actual_datetime = new DateTime();
        $status = $statusTypeShoppingCartRepository->find(2);
        foreach ($shopping_cart_products as $shopping_cart_product) {
            $shopping_cart_product
                ->setStatus($status) //status eliminado
                ->setUpdatedAt($actual_datetime);

            $em->persist($shopping_cart_product);
        }
        $em->flush();

        return $this->json(
            [
                'message' => 'Se eliminaron todos los productos de su lista de carrito.'
            ],
            Response::HTTP_ACCEPTED,
            ['Content-Type' => 'application/json']
        );
    }


    /**
     * @Route("/favorite/list", name="api_favorite_list",methods={"GET"})
     */
    public function favoriteList(FavoriteProductRepository $favoriteProductRepository): Response
    {

        $favorite_products = $favoriteProductRepository->findAllFavoriteProductsByStatus($this->customer->getId(), 1);



        if (!$favorite_products) { //retorno si el producto ya fue activado como favorito..
            return $this->json(
                [
                    "wish_list" => [],
                    'message' => 'No tiene productos en su lista de favoritos.'
                ],
                Response::HTTP_ACCEPTED,
                ['Content-Type' => 'application/json']
            );
        }

        $favorite_products_list = [];
        foreach ($favorite_products as $favorite_product) {
            $favorite_products_list[] = $favorite_product->getProduct()->getBasicDataProduct();
        }

        return $this->json(
            [
                "wish_list" => $favorite_products_list,
            ],
            Response::HTTP_ACCEPTED,
            ['Content-Type' => 'application/json']
        );
    }

    /**
     * @Route("/favorite/add", name="api_favorite_add",methods={"POST"})
     */
    public function favoriteAdd(Request $request, StatusTypeFavoriteRepository $statusTypeFavoriteRepository, ProductRepository $productRepository, ShoppingCartRepository $shoppingCartRepository, FavoriteProductRepository $favoriteProductRepository, EntityManagerInterface $em): Response
    {

        $body = $request->getContent();
        $data = json_decode($body, true);

        $product = $productRepository->findActiveProductById($data['product_id']);
        if (!$product) { //retorno no se encontro producto activo.
            return $this->json(
                [
                    'message' => 'No fue posible encontrar el producto indicado.'
                ],
                Response::HTTP_NOT_FOUND,
                ['Content-Type' => 'application/json']
            );
        }

        $favorite_product = $favoriteProductRepository->findFavoriteProductByStatus((int)$product->getId(), (int)$this->customer->getId(), 1);

        if ($favorite_product) { //retorno si el producto ya fue activado como favorito..
            return $this->json(
                [
                    'message' => 'El producto ya se encuenta en su lista de favoritos.'
                ],
                Response::HTTP_CONFLICT,
                ['Content-Type' => 'application/json']
            );
        }

        $cart_product = $shoppingCartRepository->findShoppingCartProductByStatus((int)$product->getId(), (int)$this->customer->getId(), 1);

        if ($cart_product) { //retorno si el producto ya fue activado como favorito..
            return $this->json(
                [
                    'message' => 'El producto ya se encuentra añadido al carrito.'
                ],
                Response::HTTP_CONFLICT,
                ['Content-Type' => 'application/json']
            );
        }

        $favorite_product = new FavoriteProduct;

        $favorite_product
            ->setCustomer($this->customer)
            ->setProduct($product)
            ->setStatus($statusTypeFavoriteRepository->find(1));

        $em->persist($favorite_product);
        $em->flush();

        return $this->json(
            [
                'message' => 'Producto agregado a favorito.'
            ],
            Response::HTTP_CREATED,
            ['Content-Type' => 'application/json']
        );
    }

    /**
     * @Route("/favorite/remove", name="api_favorite_remove",methods={"POST"})
     */
    public function favoriteRemove(Request $request, StatusTypeFavoriteRepository $statusTypeFavoriteRepository, ProductRepository $productRepository, FavoriteProductRepository $favoriteProductRepository, EntityManagerInterface $em): Response
    {

        $body = $request->getContent();
        $data = json_decode($body, true);

        $product = $productRepository->findActiveProductById($data['product_id']);
        if (!$product) { //retorno no se encontro producto activo.
            return $this->json(
                [
                    'message' => 'No fue posible encontrar el producto indicado.'
                ],
                Response::HTTP_NOT_FOUND,
                ['Content-Type' => 'application/json']
            );
        }

        $favorite_product = $favoriteProductRepository->findFavoriteProductByStatus((int)$product->getId(), (int)$this->customer->getId(), 1);

        if (!$favorite_product) { //retorno si el producto ya fue activado como favorito..
            return $this->json(
                [
                    'message' => 'El producto indicado no se encuentra su lista de favoritos.'
                ],
                Response::HTTP_NOT_FOUND,
                ['Content-Type' => 'application/json']
            );
        }

        $favorite_product
            ->setStatus($statusTypeFavoriteRepository->find(2)) //status eliminado
            ->setUpdatedAt(new DateTime());

        $em->persist($favorite_product);
        $em->flush();

        return $this->json(
            [
                'message' => 'Producto eliminado de tu lista de favoritos.'
            ],
            Response::HTTP_ACCEPTED,
            ['Content-Type' => 'application/json']
        );
    }

    /**
     * @Route("/favorite/removeAll", name="api_favorite_removeAll",methods={"POST"})
     */
    public function favoriteRemoveAll(StatusTypeFavoriteRepository $statusTypeFavoriteRepository, FavoriteProductRepository $favoriteProductRepository, EntityManagerInterface $em): Response
    {

        $favorite_products = $favoriteProductRepository->findAllFavoriteProductsByStatus($this->customer->getId(), 1);

        if (!$favorite_products) { //retorno si el producto ya fue activado como favorito..
            return $this->json(
                [
                    'message' => 'No tiene productos en su lista de favoritos.'
                ],
                Response::HTTP_CONFLICT,
                ['Content-Type' => 'application/json']
            );
        }

        $actual_datetime = new DateTime();
        $status = $statusTypeFavoriteRepository->find(2);
        foreach ($favorite_products as $favorite_product) {
            $favorite_product
                ->setStatus($status) //status eliminado
                ->setUpdatedAt($actual_datetime);

            $em->persist($favorite_product);
        }
        $em->flush();

        return $this->json(
            [
                'message' => 'Se eliminaron todos los productos de su lista de favoritos.'
            ],
            Response::HTTP_ACCEPTED,
            ['Content-Type' => 'application/json']
        );
    }
}
