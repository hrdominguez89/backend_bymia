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
 * @Route("/api/customer/data")
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
     * @Route("", name="customer_full_data",methods={"GET"})
     */
    public function fullDataCustomer(
        ShoppingCartRepository $shoppingCartRepository,
        StatusTypeShoppingCartRepository $statusTypeShoppingCartRepository,
        EntityManagerInterface $em,
        CommunicationStatesBetweenPlatformsRepository $communicationStatesBetweenPlatformsRepository,
        ProductRepository $productRepository
    ): Response {
        if (!$this->customer) {
            return $this->json(
                [
                    'status' => false,
                    'status_code' => Response::HTTP_NOT_FOUND,
                    'message' => 'Cliente no encontrado.'
                ],
                Response::HTTP_NOT_FOUND,
                ['Content-Type' => 'application/json']
            );
        }

        $customerData = [
            'code_id' => $this->customer->getId(),
            'type_user' => $this->customer->getCustomerTypeRole()->getName(),
            'name' => $this->customer->getName(),
            'email' => $this->customer->getEmail(),
            'phone' => (string)$this->customer->getPhone() ? $this->customer->getCountryPhoneCode()->getPhonecode() . ($this->customer->getStateCodePhone() ? $this->customer->getStateCodePhone() : '') . $this->customer->getPhone() : '',
            'gender' => $this->customer->getGenderType()->getDescription(),
            'birthdate' => (string)$this->customer->getDateOfBirth()->format('m/d/Y'),
            'latest_billing_data' => [
                'code_id' => 1,
                'type_user' => null,
                'name' => 'Nombre Facturacion',
                'email' => 'factura@factura.com',
                'phone' => '1112223344',
                'identity_type' => 'dni',
                'identity_number' => '34987273',
                'country' => 'República Dominicana',
                'state' => 'Estado de RD',
                'city' => 'Ciudad de RD',
                'address' => 'CALLE AVENIDA SIEMPRE VIVA',
                'zip_code' => '1027'
            ],
            'my_addresses' => [
                [
                    'name' => 'Nombre de la persona',
                    'phone' => '11199922288',
                    'country' => 'República Dominicana',
                    'state' => 'Estado de RD',
                    'city' => 'Ciudad de RD',
                    'zip_code' => '1234',
                    'address' => 'Avenida 1'
                ],
                [
                    'name' => 'Nombre de la persona',
                    'phone' => '11199922288',
                    'country' => 'República Dominicana',
                    'state' => 'Estado de RD',
                    'city' => 'Ciudad de RD',
                    'zip_code' => '1234',
                    'address' => 'Avenida 2'
                ],
                [
                    'name' => 'Nombre de la persona',
                    'phone' => '11199922288',
                    'country' => 'República Dominicana',
                    'state' => 'Estado de RD',
                    'city' => 'Ciudad de RD',
                    'zip_code' => '1234',
                    'address' => 'Avenida 3'
                ],
            ]
        ];

        return $this->json(
            [
                'customerData' => $customerData,
                'status' => TRUE,
                'status_code' => Response::HTTP_NOT_FOUND,
            ],
            Response::HTTP_NOT_FOUND,
            ['Content-Type' => 'application/json']
        );
    }

    /**
     * @Route("/bill", name="customer_bill_data",methods={"GET"})
     */
    public function billData(
        Request $request,
        StatusOrderTypeRepository $statusOrderTypeRepository,
        ShoppingCartRepository $shoppingCartRepository,
        StatusTypeShoppingCartRepository $statusTypeShoppingCartRepository,
        EntityManagerInterface $em,
        CommunicationStatesBetweenPlatformsRepository $communicationStatesBetweenPlatformsRepository,
        ProductRepository $productRepository
    ): Response {

        return $this->json(
            [
                'status' => false,
                'status_code' => Response::HTTP_INTERNAL_SERVER_ERROR,
                'message' => 'Mensaje de error'
            ],
            Response::HTTP_INTERNAL_SERVER_ERROR,
            ['Content-Type' => 'application/json']
        );
    }

    /**
     * @Route("/recipient", name="customer_recipient_data",methods={"GET"})
     */
    public function recipientData(
        Request $request,
        StatusOrderTypeRepository $statusOrderTypeRepository,
        ShoppingCartRepository $shoppingCartRepository,
        StatusTypeShoppingCartRepository $statusTypeShoppingCartRepository,
        EntityManagerInterface $em,
        CommunicationStatesBetweenPlatformsRepository $communicationStatesBetweenPlatformsRepository,
        ProductRepository $productRepository
    ): Response {

        return $this->json(
            [
                'status' => false,
                'status_code' => Response::HTTP_INTERNAL_SERVER_ERROR,
                'message' => 'Mensaje de error'
            ],
            Response::HTTP_INTERNAL_SERVER_ERROR,
            ['Content-Type' => 'application/json']
        );
    }
}
