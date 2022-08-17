<?php

namespace App\Controller\Secure;

use App\Entity\CustomerAddresses;
use App\Form\CustomerAddressesType;
use App\Repository\CustomerAddressesRepository;
use App\Repository\CustomerRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
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
    public function new($customer_id, Request $request, CustomerRepository $customerRepository): Response
    {
        $data['customer'] = $customerRepository->find($customer_id);
        $data['customer_addresses'] = new CustomerAddresses();
        $form = $this->createForm(CustomerAddressesType::class, $data['customer_addresses']);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // $data['customer']->setStatus(true);
            // $data['customer']->setPassword($_ENV['PWD_NEW_USER']);

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($data['customer_addresses']);
            $entityManager->flush();

            return $this->redirectToRoute('secure_customer_addresses', [], Response::HTTP_SEE_OTHER);
        }

        $data['form'] = $form;
        $data['title'] = 'Nueva dirección del cliente';
        $data['files_js'] = array('table_full_buttons.js?v=' . rand());

        return $this->renderForm('secure/customer_addresses/customer_address_form.html.twig', $data);
    }
}
