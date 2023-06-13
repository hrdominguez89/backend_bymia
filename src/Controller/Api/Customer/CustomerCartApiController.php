<?php

namespace App\Controller\Api\Customer;

use App\Constants\Constants;
use App\Entity\ShoppingCart;
use App\Repository\CustomerRepository;
use App\Repository\FavoriteProductRepository;
use App\Repository\ProductRepository;
use App\Repository\ShoppingCartRepository;
use App\Repository\StatusTypeFavoriteRepository;
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
 * @Route("/api/customer/cart")
 */
class CustomerCartApiController extends AbstractController
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
     * @Route("/list", name="api_cart_list",methods={"GET"})
     */
    public function cartList(ShoppingCartRepository $shoppingCartRepository): Response
    {

        $shopping_cart_products = $shoppingCartRepository->findAllShoppingCartProductsByStatus($this->customer->getId(), Constants::STATUS_SHOPPING_CART_ACTIVO);

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
     * @Route("/addAllFavorites", name="api_cart_add_all_favorites",methods={"POST"})
     */
    public function addAllFavorites(ShoppingCartRepository $shoppingCartRepository,  FavoriteProductRepository $favoriteProductRepository, StatusTypeFavoriteRepository $statusTypeFavoriteRepository, StatusTypeShoppingCartRepository $statusTypeShoppingCartRepository, EntityManagerInterface $em): Response
    {

        $favorite_products = $favoriteProductRepository->findAllFavoriteProductsByStatus($this->customer->getId(), Constants::STATUS_FAVORITE_ACTIVO);

        if (!$favorite_products) { //retorno si el producto ya fue activado como favorito..
            return $this->json(
                [
                    'message' => 'No tiene productos en su lista de favoritos.'
                ],
                Response::HTTP_CONFLICT,
                ['Content-Type' => 'application/json']
            );
        }

        $status_on_shopping_cart = $statusTypeFavoriteRepository->find(Constants::STATUS_FAVORITE_EN_CARRITO); //status agregar al carrito
        $status_active = $statusTypeShoppingCartRepository->find(Constants::STATUS_SHOPPING_CART_ACTIVO); //status activo


        $actual_datetime = new DateTime();


        foreach ($favorite_products as $favorite_product) {
            if ($favorite_product->getProduct()->getAvailable() > 0) {
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
            } else {
                $favorite_products_list = [];
                foreach ($favorite_products as $favorite_product) {
                    $favorite_products_list[] = $favorite_product->getProduct()->getBasicDataProduct();
                }
            }
        }
        $message = 'Productos agregados al carrito correctamente.';
        if ($favorite_products_list) {
            $message = 'Algunos productos no pudieron ser agregados al carrito por falta de disponiblidad.';
        }

        $em->flush();

        $shopping_cart_products = $shoppingCartRepository->findAllShoppingCartProductsByStatus($this->customer->getId(), Constants::STATUS_SHOPPING_CART_ACTIVO);

        $shopping_cart_products_list = [];
        foreach ($shopping_cart_products as $shopping_cart_product) {
            $shopping_cart_products_list[] = $shopping_cart_product->getProduct()->getBasicDataProduct();
        }

        return $this->json(
            [
                "wish_list" => $favorite_products_list,
                "shop_cart_list" => $shopping_cart_products_list,
                "message" => $message
            ],
            Response::HTTP_ACCEPTED,
            ['Content-Type' => 'application/json']
        );
    }

    /**
     * @Route("/add", name="api_cart_add",methods={"POST"})
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

        $shopping_cart_product = $shoppingCartRepository->findShoppingCartProductByStatus((int)$product->getId(), (int)$this->customer->getId(), Constants::STATUS_SHOPPING_CART_ACTIVO);

        if ($shopping_cart_product) { //retorno si el producto ya fue fue añadido al carrito..
            return $this->json(
                [
                    'message' => 'El producto ya se encuenta en su lista de carrito.'
                ],
                Response::HTTP_CONFLICT,
                ['Content-Type' => 'application/json']
            );
        }

        if ($product->getAvailable() <= 0) {
            return $this->json(
                [
                    'message' => 'No fue posible agregar el producto al carrito, por falta de disponibilidad.'
                ],
                Response::HTTP_CONFLICT,
                ['Content-Type' => 'application/json']
            );
        }

        $favorite_product = $favoriteProductRepository->findFavoriteProductByStatus((int)$product->getId(), (int)$this->customer->getId(), Constants::STATUS_FAVORITE_ACTIVO);

        if ($favorite_product) { //retorno si el producto ya fue activado como favorito..
            $actual_datetime = new DateTime();
            $favorite_product
                ->setStatus($statusTypeFavoriteRepository->find(Constants::STATUS_FAVORITE_EN_CARRITO))
                ->setUpdatedAt($actual_datetime);

            $em->persist($favorite_product);
        }

        $shopping_cart_product = new ShoppingCart;

        $shopping_cart_product
            ->setCustomer($this->customer)
            ->setProduct($product)
            ->setStatus($statusTypeShoppingCartRepository->find(Constants::STATUS_SHOPPING_CART_ACTIVO))
            ->setFavorite($favorite_product);

        $em->persist($shopping_cart_product);
        try {
            $em->flush();

            return $this->json(
                [
                    'message' => 'Producto agregado al carrito.',
                    'status_code' => Response::HTTP_CREATED
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
     * @Route("/remove", name="api_cart_remove",methods={"POST"})
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

        $shopping_cart_product = $shoppingCartRepository->findShoppingCartProductByStatus((int)$product->getId(), (int)$this->customer->getId(), Constants::STATUS_SHOPPING_CART_ACTIVO);

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
            ->setStatus($statusTypeShoppingCartRepository->find(Constants::STATUS_SHOPPING_CART_ELIMINADO)) //status eliminado
            ->setUpdatedAt(new DateTime());

        $em->persist($shopping_cart_product);

        try {
            $em->flush();

            return $this->json(
                [
                    'message' => 'Producto eliminado de tu lista de carrito.'
                ],
                Response::HTTP_ACCEPTED,
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
     * @Route("/removeAll", name="api_cart_remove_all",methods={"POST"})
     */
    public function cartRemoveAll(StatusTypeShoppingCartRepository $statusTypeShoppingCartRepository, ShoppingCartRepository $shoppingCartRepository, EntityManagerInterface $em): Response
    {

        $shopping_cart_products = $shoppingCartRepository->findAllShoppingCartProductsByStatus($this->customer->getId(), Constants::STATUS_SHOPPING_CART_ACTIVO);

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
        $status = $statusTypeShoppingCartRepository->find(Constants::STATUS_SHOPPING_CART_ELIMINADO);
        foreach ($shopping_cart_products as $shopping_cart_product) {
            $shopping_cart_product
                ->setStatus($status) //status eliminado
                ->setUpdatedAt($actual_datetime);

            $em->persist($shopping_cart_product);
        }

        try {
            $em->flush();

            return $this->json(
                [
                    'message' => 'Se eliminaron todos los productos de su lista de carrito.'
                ],
                Response::HTTP_ACCEPTED,
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
}
