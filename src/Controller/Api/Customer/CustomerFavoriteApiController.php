<?php

namespace App\Controller\Api\Customer;

use App\Constants\Constants;
use App\Entity\FavoriteProduct;
use App\Repository\CustomerRepository;
use App\Repository\FavoriteProductRepository;
use App\Repository\ProductRepository;
use App\Repository\ShoppingCartRepository;
use App\Repository\StatusTypeFavoriteRepository;
use DateTime;
use Doctrine\ORM\EntityManagerInterface;
use Lexik\Bundle\JWTAuthenticationBundle\Encoder\JWTEncoderInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;


/**
 * @Route("/api/customer/favorite")
 */
class CustomerFavoriteApiController extends AbstractController
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
     * @Route("/list", name="api_favorite_list",methods={"GET"})
     */
    public function favoriteList(FavoriteProductRepository $favoriteProductRepository): Response
    {

        $favorite_products = $favoriteProductRepository->findAllFavoriteProductsByStatus($this->customer->getId(), Constants::STATUS_FAVORITE_ACTIVO);



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
     * @Route("/add", name="api_favorite_add",methods={"POST"})
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

        $favorite_product = $favoriteProductRepository->findFavoriteProductByStatus((int)$product->getId(), (int)$this->customer->getId(), Constants::STATUS_FAVORITE_ACTIVO);

        if ($favorite_product) { //retorno si el producto ya fue activado como favorito..
            return $this->json(
                [
                    'message' => 'El producto ya se encuenta en su lista de favoritos.'
                ],
                Response::HTTP_CONFLICT,
                ['Content-Type' => 'application/json']
            );
        }

        $cart_product = $shoppingCartRepository->findShoppingCartProductByStatus((int)$product->getId(), (int)$this->customer->getId(), Constants::STATUS_SHOPPING_CART_ACTIVO);

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
            ->setStatus($statusTypeFavoriteRepository->find(Constants::STATUS_FAVORITE_ACTIVO));

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
     * @Route("/remove", name="api_favorite_remove",methods={"POST"})
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

        $favorite_product = $favoriteProductRepository->findFavoriteProductByStatus((int)$product->getId(), (int)$this->customer->getId(), Constants::STATUS_FAVORITE_ACTIVO);

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
            ->setStatus($statusTypeFavoriteRepository->find(Constants::STATUS_FAVORITE_ELIMINADO)) //status eliminado
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
     * @Route("/removeAll", name="api_favorite_remove_all",methods={"POST"})
     */
    public function favoriteRemoveAll(StatusTypeFavoriteRepository $statusTypeFavoriteRepository, FavoriteProductRepository $favoriteProductRepository, EntityManagerInterface $em): Response
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

        $actual_datetime = new DateTime();
        $status = $statusTypeFavoriteRepository->find(Constants::STATUS_FAVORITE_ELIMINADO);
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
