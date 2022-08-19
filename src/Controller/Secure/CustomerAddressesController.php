<?php

namespace App\Controller\Secure;

use App\Entity\CustomerAddresses;
use App\Entity\States;
use App\Entity\Cities;
use App\Form\CustomerAddressesType;
use App\Repository\CitiesRepository;
use App\Repository\CustomerAddressesRepository;
use App\Repository\CustomerRepository;
use App\Repository\StatesRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/customeraddresses")
 */
class CustomerAddressesController extends AbstractController
{
    /**
     * @Route("/{customer_id}/addresses", name="secure_customer_addresses", methods={"GET"})
     */
    public function index($customer_id, CustomerAddressesRepository $customerAddressesRepository, CustomerRepository $customerRepository): Response
    {
        $data['title'] = 'Direcciones del cliente';
        $data['customer'] = $customerRepository->find($customer_id);
        $data['customer_addresses'] = $customerAddressesRepository->listCustomerAddresses($customer_id);
        $data['files_js'] = array('table_full_buttons.js?v=' . rand());

        return $this->render('secure/customer_addresses/index.html.twig', $data);
    }

    /**
     * @Route("/{customer_id}/new", name="secure_customer_address_new", methods={"GET","POST"})
     */
    public function new($customer_id, Request $request, CustomerAddressesRepository $customerAddressesRepository, CustomerRepository $customerRepository, StatesRepository $statesRepository, CitiesRepository $citiesRepository): Response
    {
        $data['customer'] = $customerRepository->find($customer_id);
        $data['customer_addresses'] = new CustomerAddresses();
        $form = $this->createForm(CustomerAddressesType::class, $data['customer_addresses']);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $data['customer_addresses']->setCustomer($data['customer']);
            $data['customer_addresses']->setRegistrationDate(new \DateTime());
            $data['customer_addresses']->setActive(true);
            $data['customer_addresses']->setState($statesRepository->find($request->get('customer_addresses')['state']));
            $data['customer_addresses']->setCity($citiesRepository->find($request->get('customer_addresses')['city']));

            if (isset($request->get('customer_addresses')['favorite_address'])) {
                $customerAddressesRepository->updateFavoriteAddress($customer_id);
            }

            if (isset($request->get('customer_addresses')['billing_address'])) {
                $customerAddressesRepository->updateBillingAddress($customer_id);
            }

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($data['customer_addresses']);
            $entityManager->flush();

            return $this->redirectToRoute('secure_customer_addresses', ['customer_id' => $customer_id]);
        }

        $data['form'] = $form;
        $data['title'] = 'Nueva dirección del cliente';
        $data['files_js'] = array(
            'customers/customer_addresses.js?v=' . rand(),
        );

        return $this->renderForm('secure/customer_addresses/customer_address_form.html.twig', $data);
    }


    /**
     * @Route("/{customer_id}/edit/{customer_address_id}", name="secure_customer_address_edit", methods={"GET","POST"})
     */
    public function edit($customer_id, Request $request, $customer_address_id, StatesRepository $statesRepository, CitiesRepository $citiesRepository, CustomerAddressesRepository $customerAddressesRepository, CustomerRepository $customerRepository)
    {
        $data['customer'] = $customerRepository->find($customer_id);
        $data['customer_addresses'] = $customerAddressesRepository->findBy(['id' => $customer_address_id])[0];
        $form = $this->createForm(CustomerAddressesType::class, $data['customer_addresses']);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $data['customer_addresses']->setCustomer($data['customer']);
            $data['customer_addresses']->setRegistrationDate(new \DateTime());
            $data['customer_addresses']->setActive(true);
            if (isset($request->get('customer_addresses')['state']) && (int)$request->get('customer_addresses')['state']) {
                $data['customer_addresses']->setState($statesRepository->find($request->get('customer_addresses')['state']));
            } else {
                $data['customer_addresses']->setState(null);
            }
            if (isset($request->get('customer_addresses')['city']) && (int)$request->get('customer_addresses')['city']) {
                $data['customer_addresses']->setCity($citiesRepository->find($request->get('customer_addresses')['city']));
            } else {
                $data['customer_addresses']->setCity(null);
            }

            if (isset($request->get('customer_addresses')['favorite_address'])) {
                $customerAddressesRepository->updateFavoriteAddress($customer_id);
            }

            if (isset($request->get('customer_addresses')['billing_address'])) {
                $customerAddressesRepository->updateBillingAddress($customer_id);
            }

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($data['customer_addresses']);
            $entityManager->flush();

            return $this->redirectToRoute('secure_customer_addresses', ['customer_id' => $customer_id]);
        }

        $data['form'] = $form;
        $data['title'] = 'Editar dirección del cliente';
        $data['files_js'] = array(
            'customers/customer_addresses.js?v=' . rand(),
        );

        return $this->renderForm('secure/customer_addresses/customer_address_form.html.twig', $data);
    }

    /**
     * @Route("/getStates/{country_id}", name="secure_customer_address_get_states", methods={"GET"})
     */
    public function getStates($country_id, StatesRepository $statesRepository): Response
    {
        $data['data'] = $statesRepository->findStatesByCountryId($country_id);
        if ($data['data']) {
            $data['status'] = true;
        } else {
            $data['status'] = false;
            $data['message'] = 'No se encontraron estados con el id indicado';
        }
        return new JsonResponse($data);
    }

    /**
     * @Route("/getCities/{state_id}", name="secure_customer_address_get_cities", methods={"GET"})
     */
    public function getCities($state_id, CitiesRepository $citiesRepository): Response
    {
        $data['data'] = $citiesRepository->findCitiesByStateId($state_id);
        if ($data['data']) {
            $data['status'] = true;
        } else {
            $data['status'] = false;
            $data['message'] = 'No se encontraron estados con el id indicado';
        }
        return new JsonResponse($data);
    }
}
