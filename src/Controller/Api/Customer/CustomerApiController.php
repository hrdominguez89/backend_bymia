<?php

namespace App\Controller\Api\Customer;

use App\Entity\FavoriteProduct;
use App\Entity\StatusTypeFavorite;
use App\Repository\CustomerRepository;
use App\Repository\FavoriteProductRepository;
use App\Repository\ProductRepository;
use App\Repository\StatusTypeFavoriteRepository;
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
     * @Route("/favorite/add", name="api_favorite_add",methods={"POST"})
     */
    public function favoriteAdd(Request $request, StatusTypeFavoriteRepository $statusTypeFavoriteRepository, ProductRepository $productRepository, FavoriteProductRepository $favoriteProductRepository, EntityManagerInterface $em): Response
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
}
