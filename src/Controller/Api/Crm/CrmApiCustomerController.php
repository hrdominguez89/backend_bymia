<?php

namespace App\Controller\Api\Crm;

use App\Entity\CustomerAddresses;
use App\Form\CustomerAddressApiType;
use App\Repository\CitiesRepository;
use App\Repository\CountriesRepository;
use App\Repository\CustomerAddressesRepository;
use App\Repository\CustomerRepository;
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
    public function customer(CustomerRepository $customerRepository, $customer_id): Response
    {

        $customer = $customerRepository->find($customer_id);
        if ($customer) {
            return $this->json(
                $customer->getCustomerTotalInfo(),
                Response::HTTP_OK,
                ['Content-Type' => 'application/json']
            );
        } else {
            return $this->json(
                ['message' => 'Not found'],
                Response::HTTP_NOT_FOUND,
                ['Content-Type' => 'application/json']
            );
        }
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
                        ->setFavoriteAddress(@$data['home_address'] ?: false)
                        ->setBillingAddress(@$data['billing_address'] ?: false);
                    //seteo valores de los objetos de relacion al objeto

                    //creo el formulario para hacer las validaciones    
                    $form = $this->createForm(CustomerAddressApiType::class, $customer_address);
                    $form->submit($data, false);

                    if (!$form->isValid()) {
                        $error_forms = $this->getErrorsFromForm($form);
                        return $this->json(
                            [
                                'message' => 'Error de validaci??n',
                                'validation' => $error_forms
                            ],
                            Response::HTTP_BAD_REQUEST,
                            ['Content-Type' => 'application/json']
                        );
                    }
                    if (@$data['home_address'] ?: false) {
                        $customerAddressesRepository->updateFavoriteAddress($customer_id);
                    }

                    if (@$data['billing_address'] ?: false) {
                        $customerAddressesRepository->updateBillingAddress($customer_id);
                    }
                    $em->persist($customer_address);
                    $em->flush();

                    return $this->json(
                        ['message' => 'Usuario creado'],
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
    public function customerAddress(CustomerRepository $customerRepository, $address_id = false): Response
    {

        $customer = $customerRepository->find();

        return $this->json(
            $customer,
            Response::HTTP_OK,
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
