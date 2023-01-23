<?php

namespace App\Controller\Api\Crm;

use App\Entity\Customer;
use App\Entity\CustomerAddresses;
use App\Form\CustomerAddressApiType;
use App\Form\RegisterCustomerApiType;
use App\Repository\CitiesRepository;
use App\Repository\CountriesRepository;
use App\Repository\CustomerAddressesRepository;
use App\Repository\CustomerRepository;
use App\Repository\CustomerStatusTypeRepository;
use App\Repository\CustomersTypesRolesRepository;
use App\Repository\GenderTypeRepository;
use App\Repository\StatesRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;



/**
 * @Route("/api/crm")
 */
class CrmApiCustomerController extends AbstractController
{
    /**
     * @Route("/customer/{customer_id}", name="api_customer",methods={"GET","PATCH"})
     * 
     */
    public function customer(
        CustomerRepository $customerRepository,
        CustomersTypesRolesRepository $customersTypesRolesRepository,
        CountriesRepository $countriesRepository,
        CustomerStatusTypeRepository $customerStatusTypeRepository,
        GenderTypeRepository $genderTypeRepository,
        Request $request,
        EntityManagerInterface $em,
        $customer_id
    ): Response {
        $customer = $customerRepository->find($customer_id);
        if ($customer) {

            switch ($request->getMethod()) {
                case 'GET':
                    return $this->json(
                        $customer->getCustomerTotalInfo(),
                        Response::HTTP_OK,
                        ['Content-Type' => 'application/json']
                    );
                case 'PATCH':
                    $body = $request->getContent();
                    $data = json_decode($body, true);

                    //Busco los objetos de cada relacion
                    $customer_type_role = $customersTypesRolesRepository->findOneBy(['id' => @$data['customer_type_role']]) ?: null;
                    $country_phone_code = $countriesRepository->findOneBy(['id' => @$data['country_phone_code']]) ?: null;
                    $status_type = $customerStatusTypeRepository->findOneBy(['id' => @$data['status']]) ?: null;
                    $gender_type = $genderTypeRepository->findOneBy(['id' => @$data['gender_type']]) ?: null;


                    // seteo valores de los objetos de relacion al objeto
                    if ($customer_type_role) $customer->setCustomerTypeRole($customer_type_role);
                    if ($country_phone_code) $customer->setCountryPhoneCode($country_phone_code);
                    if ($status_type) $customer->setStatus($status_type);
                    if ($gender_type) $customer->setGenderType($gender_type);



                    //creo el formulario para hacer las validaciones    
                    $form = $this->createForm(RegisterCustomerApiType::class, $customer);
                    $form->submit($data, false);

                    if (!$form->isValid()) {
                        $error_forms = $this->getErrorsFromForm($form);
                        return $this->json(
                            [
                                'message' => 'Error de validación.',
                                'validation' => $error_forms
                            ],
                            Response::HTTP_BAD_REQUEST,
                            ['Content-Type' => 'application/json']
                        );
                    }

                    try {
                        $em->persist($customer);
                        $em->flush();
                    } catch (\Exception $e) {
                        return $this->json(
                            [
                                'message' => 'Error al intentar grabar en la base de datos.',
                                'validation' => ['others' => $e->getMessage()]
                            ],
                            Response::HTTP_UNPROCESSABLE_ENTITY,
                            ['Content-Type' => 'application/json']
                        );
                    }

                    return $this->json(
                        [
                            'message' => 'Cliente actualizado con éxito.',
                            'customer_updated' => $customer->getCustomerTotalInfo()
                        ],
                        Response::HTTP_OK,
                        ['Content-Type' => 'application/json']
                    );
                    break;
            }
        }
        //si no encontro ni customer en methodo get o customer en post retorno not found 
        return $this->json(
            ['message' => 'Not found.'],
            Response::HTTP_NOT_FOUND,
            ['Content-Type' => 'application/json']
        );
    }

    /**
     * @Route("/customer/{customer_id}/addresses", name="api_customer_addresses",methods={"GET","POST"})
     * 
     */
    public function customerAddresses(
        EntityManagerInterface $em,
        CountriesRepository $countriesRepository,
        StatesRepository $statesRepository,
        CitiesRepository $citiesRepository,
        Request $request,
        CustomerAddressesRepository $customerAddressesRepository,
        CustomerRepository $customerRepository,
        $customer_id
    ): Response {
        switch ($request->getMethod()) {
            case 'GET':
                $customer_addresses = $customerAddressesRepository->findAddressesByCustomerId($customer_id);
                if ($customer_addresses) {
                    return $this->json(
                        $customer_addresses,
                        Response::HTTP_OK,
                        ['Content-Type' => 'application/json']
                    );
                }
                break;
            case 'POST':
                $customer = $customerRepository->findOneBy(["id" => $customer_id]);
                if ($customer) {

                    $body = $request->getContent();
                    $data = json_decode($body, true);

                    //Busco los objetos de cada relacion
                    $country = $countriesRepository->findOneBy(['id' => @$data['country_id']]) ?: null;
                    $state = $statesRepository->findOneBy(['id' => @$data['state_id']]) ?: null;
                    $city = $citiesRepository->findOneBy(['id' => @$data['city_id']]) ?: null;

                    //creo el objeto customer address
                    $customer_address = new CustomerAddresses();

                    $customer_address
                        ->setCustomer($customer)
                        ->setCountry($country)
                        ->setState($state)
                        ->setCity($city)
                        ->setHomeAddress(@$data['home_address'] ?: false)
                        ->setBillingAddress(@$data['billing_address'] ?: false);
                    // seteo valores de los objetos de relacion al objeto

                    //creo el formulario para hacer las validaciones    
                    $form = $this->createForm(CustomerAddressApiType::class, $customer_address);
                    $form->submit($data, false);

                    if (!$form->isValid()) {
                        $error_forms = $this->getErrorsFromForm($form);
                        return $this->json(
                            [
                                'message' => 'Error de validación.',
                                'validation' => $error_forms
                            ],
                            Response::HTTP_BAD_REQUEST,
                            ['Content-Type' => 'application/json']
                        );
                    }
                    if (@$data['home_address'] ?: false) {
                        $customerAddressesRepository->updateHomeAddress($customer_id);
                    }

                    if (@$data['billing_address'] ?: false) {
                        $customerAddressesRepository->updateBillingAddress($customer_id);
                    }

                    try {
                        $em->persist($customer_address);
                        $em->flush();
                    } catch (\Exception $e) {
                        return $this->json(
                            [
                                'message' => 'Error al intentar grabar en la base de datos.',
                                'validation' => ['others' => $e->getMessage()]
                            ],
                            Response::HTTP_UNPROCESSABLE_ENTITY,
                            ['Content-Type' => 'application/json']
                        );
                    }

                    return $this->json(
                        [
                            'message' => 'Dirección creada con éxito.',
                            'new_address' => $customer_address->getTotalCustomerAddressInfo()
                        ],
                        Response::HTTP_CREATED,
                        ['Content-Type' => 'application/json']
                    );
                }
                break;
        }
        //si no encontro ni customer address en methodo get o customer en post retorno not found 
        return $this->json(
            ['message' => 'Not found'],
            Response::HTTP_NOT_FOUND,
            ['Content-Type' => 'application/json']
        );
    }

    /**
     * @Route("/customer/address/{address_id}", name="api_customer_address",methods={"GET","PATCH"})
     * 
     */
    public function customerAddress(
        EntityManagerInterface $em,
        CountriesRepository $countriesRepository,
        StatesRepository $statesRepository,
        CitiesRepository $citiesRepository,
        Request $request,
        CustomerAddressesRepository $customerAddressesRepository,
        $address_id
    ): Response {
        $customer_address = $customerAddressesRepository->find($address_id);
        if ($customer_address) {
            switch ($request->getMethod()) {
                case 'GET':
                    return $this->json(
                        $customer_address->getTotalCustomerAddressInfo(),
                        Response::HTTP_OK,
                        ['Content-Type' => 'application/json']
                    );
                    break;
                case 'PATCH':

                    $body = $request->getContent();
                    $data = json_decode($body, true);

                    //Busco los objetos de cada relacion
                    $country = $countriesRepository->findOneBy(['id' => @$data['country_id']]) ?: null;
                    $state = $statesRepository->findOneBy(['id' => @$data['state_id']]) ?: null;
                    $city = $citiesRepository->findOneBy(['id' => @$data['city_id']]) ?: null;

                    // seteo valores de los objetos de relacion al objeto

                    if ($country) $customer_address->setCountry($country);
                    if ($state) $customer_address->setState($state);
                    if ($city) $customer_address->setCity($city);

                    if (isset($data['home_address'])) {
                        $customer_address->setHomeAddress($data['home_address'] == true ?: false);
                    }

                    if (isset($data['billing_address'])) {
                        $customer_address->setBillingAddress($data['billing_address'] == false ?: false);
                    }


                    //creo el formulario para hacer las validaciones    
                    $form = $this->createForm(CustomerAddressApiType::class, $customer_address);
                    $form->submit($data, false);

                    if (!$form->isValid()) {
                        $error_forms = $this->getErrorsFromForm($form);
                        return $this->json(
                            [
                                'message' => 'Error de validación.',
                                'validation' => $error_forms
                            ],
                            Response::HTTP_BAD_REQUEST,
                            ['Content-Type' => 'application/json']
                        );
                    }
                    if (isset($data['home_address']) && $data['home_address'] == true) {
                        $customerAddressesRepository->updateHomeAddress($customer_address->getCustomer()->getId());
                    }
                    if (isset($data['billing_address']) && $data['billing_address'] == true) {
                        $customerAddressesRepository->updateBillingAddress($customer_address->getCustomer()->getId());
                    }


                    try {
                        $em->persist($customer_address);
                        $em->flush();
                    } catch (\Exception $e) {
                        return $this->json(
                            [
                                'message' => 'Error al intentar grabar en la base de datos.',
                                'validation' => ['others' => $e->getMessage()]
                            ],
                            Response::HTTP_UNPROCESSABLE_ENTITY,
                            ['Content-Type' => 'application/json']
                        );
                    }

                    return $this->json(
                        [
                            'message' => 'Dirección actualizada con éxito.',
                            'updated_address' => $customer_address->getTotalCustomerAddressInfo()
                        ],
                        Response::HTTP_OK,
                        ['Content-Type' => 'application/json']
                    );
                    break;
            }
        }

        //si no encontro ni customer address en methodo get o customer en post retorno not found 
        return $this->json(
            ['message' => 'Not found'],
            Response::HTTP_NOT_FOUND,
            ['Content-Type' => 'application/json']
        );
    }

    /**
     * @Route("/countries", name="api_countries",methods={"GET"})
     * 
     */
    public function countries(CountriesRepository $CountriesRepository): Response
    {

        $countries = $CountriesRepository->getCountries();

        return $this->json(
            $countries,
            Response::HTTP_OK,
            ['Content-Type' => 'application/json']
        );
    }

    /**
     * @Route("/country/{country_id}/states", name="api_states_by_country",methods={"GET"})
     * 
     */
    public function statesByCountry(StatesRepository $statesRepository, $country_id): Response
    {

        $states = $statesRepository->findVisibleStatesByCountryId($country_id);

        return $this->json(
            $states,
            Response::HTTP_OK,
            ['Content-Type' => 'application/json']
        );
    }

    /**
     * @Route("/state/{state_id}/cities", name="api_cities_by_states",methods={"GET"})
     * 
     */
    public function citiesByState(CitiesRepository $citiesRepository, $state_id): Response
    {

        $cities = $citiesRepository->findVisibleCitiesByStateId($state_id);

        return $this->json(
            $cities,
            Response::HTTP_OK,
            ['Content-Type' => 'application/json']
        );
    }


    private function getErrorsFromForm(FormInterface $form)
    {
        $errors = array();
        foreach ($form->getErrors() as $error) {
            $errors[] = $error->getMessage();
        }
        foreach ($form->all() as $childForm) {
            if ($childForm instanceof FormInterface) {
                if ($childErrors = $this->getErrorsFromForm($childForm)) {
                    $errors[$childForm->getName()] = $childErrors;
                }
            }
        }
        return $errors;
    }
}
