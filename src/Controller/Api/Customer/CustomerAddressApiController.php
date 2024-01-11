<?php

namespace App\Controller\Api\Customer;

use App\Constants\Constants;
use App\Entity\Orders;
use App\Entity\OrdersProducts;
use App\Helpers\SendOrderToCrm;
use App\Repository\CommunicationStatesBetweenPlatformsRepository;
use App\Repository\CustomerAddressesRepository;
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
        ProductRepository $productRepository,
        CustomerAddressesRepository $customerAddressesRepository
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

        $bill_address = $customerAddressesRepository->findOneBy(['active' => true, 'customer' => $this->customer, 'billing_address' => true], ['id' => 'DESC']);

        $recipes_addresses = $customerAddressesRepository->getLastFiveAddress($this->customer);

        $recipes_addresses_data = [];
        foreach ($recipes_addresses as $recipe_address) {
            $recipes_addresses_data[] = $recipe_address->getRecipeDataToProfile();
        }

        $customerData = [
            'code_id' => $this->customer->getId(),
            'country_name' => $this->customer->getCountryPhoneCode() ? $this->customer->getCountryPhoneCode()->getName() : '',
            'country_id' => $this->customer->getCountryPhoneCode() ? $this->customer->getCountryPhoneCode()->getId() : '',
            'type_user' => $this->customer->getCustomerTypeRole() ? $this->customer->getCustomerTypeRole()->getName() : '',
            'type_user_id' => $this->customer->getCustomerTypeRole() ? $this->customer->getCustomerTypeRole()->getId() : '',
            'name' => $this->customer->getName(),
            'email' => $this->customer->getEmail(),
            'phone_code' => $this->customer->getCountryPhoneCode() ? (string)$this->customer->getCountryPhoneCode()->getPhonecode() : '',
            'phone' => (string)$this->customer->getPhone() ? $this->customer->getPhone() : '',
            'gender' => $this->customer->getGenderType() ? $this->customer->getGenderType()->getDescription() : '',
            'gender_id' => $this->customer->getGenderType() ? $this->customer->getGenderType()->getId() : '',
            'birthdate' => (string)$this->customer->getDateOfBirth()->format('m/d/Y'),
            'latest_billing_data' => $bill_address->getBillAddressDataToProfile() ?: null,
            'my_addresses' => $recipes_addresses_data ?: null,
        ];

        return $this->json(
            [
                'customerData' => $customerData,
                'status' => TRUE,
                'status_code' => Response::HTTP_OK,
            ],
            Response::HTTP_OK,
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
