<?php

namespace App\Controller\Api\Customer;

use App\Repository\CustomerRepository;
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

    public function __construct(JWTEncoderInterface $jwtEncoder, CustomerRepository $customerRepository,RequestStack $requestStack)
    {
        $request = $requestStack->getCurrentRequest();
        $token = explode(' ', $request->headers->get('Authorization'))[1];

        $username = @$jwtEncoder->decode($token)['username'] ?: '';

        $this->customer = $customerRepository->findOneBy(['email' => $username]);
    }


    /**
     * @Route("/user", name="app_user_api",methods={"GET|POST"})
     */
    public function index(Request $request): Response
    {
        dd('entre',$this->customer);

        $parametros = json_decode($request->getContent(), true);
        $parametros = $request->headers;

        return $this->json(
            [
                'prueb' => $parametros->get('authorization'),
                'aaa' => 'bbbb'
            ],
            Response::HTTP_OK,
            ['Content-Type' => 'application/json']
        );
    }
}
