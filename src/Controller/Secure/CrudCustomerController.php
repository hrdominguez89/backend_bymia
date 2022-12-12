<?php

namespace App\Controller\Secure;

use App\Entity\Customer;
use App\Form\CustomerSearchType;
use App\Form\CustomerType;
use App\Form\Model\CustomerSearchDto;
use App\Helpers\FileUploader;
use App\Repository\CustomerRepository;
use Exception;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Contracts\HttpClient\HttpClientInterface;
use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;

/**
 * @Route("/customer")
 */
class CrudCustomerController extends AbstractController
{
    /**
     * @Route("/", name="secure_crud_customer_index")
     */
    public function index(CustomerRepository $customerRepository): Response
    {

        $data['title'] = 'Clientes';
        $data['customers'] = $customerRepository->listCustomersInfo();
        $data['title'] = "Clientes";
        $data['files_js'] = array('table_full_buttons.js?v=' . rand());
        $data['breadcrumbs'] = array(
            array('active' => true, 'title' => $data['title'])
        );
        return $this->render('secure/crud_customer/abm_customer.html.twig', $data);
    }

    /**
     * @Route("/new", name="secure_crud_customer_new", methods={"GET","POST"})
     */
    public function new(Request $request, HttpClientInterface $client): Response
    {

        $data['title'] = "Nuevo cliente";
        $data['customer'] = new Customer();
        $form = $this->createForm(CustomerType::class, $data['customer']);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $data['customer']->setStatus(true);
            $data['customer']->setPassword($_ENV['PWD_NEW_USER']);
            if ($form->get('customer_type_role')->getData()->getId() == 2) {
                $data['customer']->setGenderType(null);
                $data['customer']->setDateOfBirth(null);
            }

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($data['customer']);
            $entityManager->flush();

            try {
                $response = $client->request(
                    'POST',
                    $_ENV['CRM_API'] . '/acustomer/',
                    [
                        'headers'   => [
                            'Authorization' => $_ENV['CRM_AUTHORIZATION'],
                            'Content-Type'  => $_ENV['CRM_CONTENT_TYPE'],
                            'Cookie'        => $_ENV['CRM_COOKIE'],
                        ],
                        'json'  => [
                            $data['customer']->getCustomerTotalInfo(),
                        ]
                    ]
                );
                if (200 == $response->getStatusCode()) {
                    //FALTA GRABAR EN BASE QUE SE ENVIO AL CRM CORRECTAMENTE
                }
            } catch (TransportExceptionInterface $e) {
                // FALTA GRABAN EN BASE QUE ESTA PENDIENTE DE ENVIO
            }

            return $this->redirectToRoute('secure_crud_customer_index', [], Response::HTTP_SEE_OTHER);
        }

        $data['form'] = $form;
        $data['files_js'] = array(
            'customers/customers.js?v=' . rand(),
        );
        $data['breadcrumbs'] = array(
            array('path' => 'secure_crud_customer_index', 'title' => 'Clientes'),
            array('active' => true, 'title' => $data['title'])
        );
        return $this->renderForm('secure/crud_customer/customer_form.html.twig', $data);
    }

    /**
     * @Route("/{id}/edit", name="secure_crud_customer_edit", methods={"GET","POST"})
     */
    public function edit($id, Request $request, HttpClientInterface $client, CustomerRepository $customerRepository): Response
    {
        $data['title'] = "Editar cliente";
        $data['customer'] = $customerRepository->find($id);
        $form = $this->createForm(CustomerType::class, $data['customer']);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            if ($form->get('customer_type_role')->getData()->getId() == 2) {
                $data['customer']->setGenderType(null);
                $data['customer']->setDateOfBirth(null);
            }
            $this->getDoctrine()->getManager()->flush();

            try {
                $response = $client->request(
                    'POST',
                    $_ENV['CRM_API'] . '/acustomer/',
                    [
                        'headers'   => [
                            'Authorization' => $_ENV['CRM_AUTHORIZATION'],
                            'Content-Type'  => $_ENV['CRM_CONTENT_TYPE'],
                            'Cookie'        => $_ENV['CRM_COOKIE'],
                        ],
                        'json'  => $data['customer']->getCustomerTotalInfo(),
                    ]
                );
                if (200 == $response->getStatusCode()) {
                    //FALTA GRABAR EN BASE QUE SE ENVIO AL CRM CORRECTAMENTE
                }
            } catch (TransportExceptionInterface $e) {
                // FALTA GRABAN EN BASE QUE ESTA PENDIENTE DE ENVIO
            }

            return $this->redirectToRoute('secure_crud_customer_index', [], Response::HTTP_SEE_OTHER);
        }

        $data['form'] = $form;
        $data['files_js'] = array(
            'customers/customers.js?v=' . rand(),
        );
        $data['breadcrumbs'] = array(
            array('path' => 'secure_crud_customer_index', 'title' => 'Clientes'),
            array('active' => true, 'title' => $data['title'])
        );
        return $this->renderForm('secure/crud_customer/customer_form.html.twig', $data);
    }
}
