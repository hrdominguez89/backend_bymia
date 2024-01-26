<?php

namespace App\Controller\Api\Customer;

use App\Constants\Constants;
use App\Entity\Orders;
use App\Entity\OrdersProducts;
use App\Helpers\SendOrderToCrm;
use App\Repository\CitiesRepository;
use App\Repository\CommunicationStatesBetweenPlatformsRepository;
use App\Repository\CountriesRepository;
use App\Repository\CustomerAddressesRepository;
use App\Repository\CustomerRepository;
use App\Repository\CustomersTypesRolesRepository;
use App\Repository\GenderTypeRepository;
use App\Repository\OrdersRepository;
use App\Repository\ProductRepository;
use App\Repository\ShoppingCartRepository;
use App\Repository\StatesRepository;
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
use Symfony\Component\Validator\Constraints\Country;

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

        $recipes_addresses = $customerAddressesRepository->getRecipienAddress($this->customer);

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
            'phone' => (string)$this->customer->getCelPhone() ?: '',
            'gender' => $this->customer->getGenderType() ? $this->customer->getGenderType()->getDescription() : '',
            'gender_id' => $this->customer->getGenderType() ? $this->customer->getGenderType()->getId() : '',
            'birthdate' => $this->customer->getDateOfBirth() ? (string)$this->customer->getDateOfBirth()->format('m/d/Y') : '',
            'latest_billing_data' => $bill_address ? $bill_address->getBillAddressDataToProfile() : null,
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
     * @Route("/profile", name="customer_profile_data",methods={"PATCH"})
     */
    public function updateProfileData(
        Request $request,
        EntityManagerInterface $em,
        CustomersTypesRolesRepository $customersTypesRolesRepository,
        GenderTypeRepository $genderTypeRepository,
        CountriesRepository $countriesRepository
    ): Response {


        $body = $request->getContent();
        $data = json_decode($body, true);

        if (!$this->customer) {
            return $this->json(
                [
                    'status' => false,
                    'status_code' => Response::HTTP_NOT_FOUND,
                    'message' => 'El cliente indicado no existe.'
                ],
                Response::HTTP_NOT_FOUND,
                ['Content-Type' => 'application/json']
            );
        }

        $this->customer->setName($data['profile']['name']);
        $this->customer->setCountryPhoneCode($countriesRepository->find($data['profile']['country_id']));
        $this->customer->setCustomerTypeRole($customersTypesRolesRepository->find($data['profile']['customer_type_role']));
        $this->customer->setGenderType($genderTypeRepository->find($data['profile']['gender_type']));
        $this->customer->setCelPhone($data['profile']['cel_phone']);
        $this->customer->setDateOfBirth(DateTime::createFromFormat('Y-m-d', $data['profile']['date_of_birth']));

        $em->persist($this->customer);
        $em->flush();

        return $this->json(
            [
                'status' => true,
                'status_code' => Response::HTTP_ACCEPTED,
                'message' => 'Datos actualizados correctamente'
            ],
            Response::HTTP_ACCEPTED,
            ['Content-Type' => 'application/json']
        );
    }

    /**
     * @Route("/bill/{bill_address_id}", name="customer_bill_data_update",methods={"PATCH"})
     */
    public function updateBillData(
        $bill_address_id,
        Request $request,
        EntityManagerInterface $em,
        CustomerAddressesRepository $customerAddressesRepository,
        CitiesRepository $citiesRepository,
        StatesRepository $statesRepository,
        CountriesRepository $countriesRepository
    ): Response {

        $body = $request->getContent();
        $data = json_decode($body, true);

        $bill_address = $customerAddressesRepository->findOneBy(['id' => $bill_address_id, 'customer' => $this->customer]);

        if (!$bill_address) {
            return $this->json(
                [
                    'status' => false,
                    'status_code' => Response::HTTP_NOT_FOUND,
                    'message' => 'El ID indicado no existe.'
                ],
                Response::HTTP_NOT_FOUND,
                ['Content-Type' => 'application/json']
            );
        }
        $bill_address->setAdditionalInfo($data['billingData']['additional_info'] ?: '');
        $bill_address->setStreet($data['billingData']['address'] ?: '');
        $bill_address->setCity($citiesRepository->find($data['billingData']['city_id']));
        $bill_address->setState($statesRepository->find($data['billingData']['state_id']));
        $bill_address->setCountry($countriesRepository->find($data['billingData']['country_id']));
        $bill_address->setEmail($data['billingData']['email'] ?: '');
        $bill_address->setIdentityType($data['billingData']['identity_type'] ?: '');
        $bill_address->setIdentityNumber($data['billingData']['identity_number'] ?: '');
        $bill_address->setName($data['billingData']['name'] ?: '');
        $bill_address->setPostalCode($data['billingData']['zip_code'] ?: '');
        $bill_address->setPhone($data['billingData']['phone'] ?: '');

        $em->persist($bill_address);
        $em->flush();

        return $this->json(
            [
                'status' => true,
                'status_code' => Response::HTTP_ACCEPTED,
                'message' => 'Datos actualizados correctamente'
            ],
            Response::HTTP_ACCEPTED,
            ['Content-Type' => 'application/json']
        );
    }

    /**
     * @Route("/address/{address_id}", name="customer_address_data_update",methods={"PATCH","DELETE"})
     */
    public function updateAddressData(
        $address_id,
        Request $request,
        EntityManagerInterface $em,
        CustomerAddressesRepository $customerAddressesRepository,
        CitiesRepository $citiesRepository,
        StatesRepository $statesRepository,
        CountriesRepository $countriesRepository
    ): Response {
        $recipientAdddress = $customerAddressesRepository->findOneBy(['id' => $address_id, 'customer' => $this->customer]);

        if (!$recipientAdddress) {
            return $this->json(
                [
                    'status' => false,
                    'status_code' => Response::HTTP_NOT_FOUND,
                    'message' => 'El ID indicado no existe.'
                ],
                Response::HTTP_NOT_FOUND,
                ['Content-Type' => 'application/json']
            );
        }

        if ($request->getMethod() == 'DELETE') {

            $recipientAdddress->setActive(false);
            $em->persist($recipientAdddress);
            $em->flush();

            return $this->json(
                [
                    'status' => true,
                    'status_code' => Response::HTTP_ACCEPTED,
                    'message' => 'Dirección eliminada correctamente.'
                ],
                Response::HTTP_ACCEPTED,
                ['Content-Type' => 'application/json']
            );
        }

        $body = $request->getContent();
        $data = json_decode($body, true);


        $recipientAdddress->setAdditionalInfo($data['address']['additional_info'] ?: '');
        $recipientAdddress->setStreet($data['address']['address'] ?: '');
        $recipientAdddress->setCity($citiesRepository->find($data['address']['city_id']));
        $recipientAdddress->setState($statesRepository->find($data['address']['state_id']));
        $recipientAdddress->setCountry($countriesRepository->find($data['address']['country_id']));
        $recipientAdddress->setEmail($data['address']['email'] ?: '');
        $recipientAdddress->setIdentityType($data['address']['identity_type'] ?: '');
        $recipientAdddress->setIdentityNumber($data['address']['identity_number'] ?: '');
        $recipientAdddress->setName($data['address']['name'] ?: '');
        $recipientAdddress->setPostalCode($data['address']['zip_code'] ?: '');
        $recipientAdddress->setPhone($data['address']['phone'] ?: '');

        $em->persist($recipientAdddress);
        $em->flush();

        return $this->json(
            [
                'status' => true,
                'status_code' => Response::HTTP_ACCEPTED,
                'message' => 'Datos actualizados correctamente'
            ],
            Response::HTTP_ACCEPTED,
            ['Content-Type' => 'application/json']
        );
    }
}
