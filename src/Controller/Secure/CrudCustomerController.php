<?php

namespace App\Controller\Secure;

use App\Constants\Constants;
use App\Entity\Customer;
use App\Form\CustomerType;
use App\Repository\CommunicationStatesBetweenPlatformsRepository;
use App\Repository\CustomerRepository;
use App\Repository\CustomerStatusTypeRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Helpers\SendCustomerToCrm;
use App\Form\CustomerSearchType;
use App\Form\Model\CustomerSearchDto;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;

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
    public function new(EntityManagerInterface $em, Request $request, CustomerStatusTypeRepository $customerStatusTypeRepository, SendCustomerToCrm $sendCustomerToCrm, CommunicationStatesBetweenPlatformsRepository $communicationStatesBetweenPlatformsRepository): Response
    {

        $data['title'] = "Nuevo cliente";
        $data['customer'] = new Customer();
        $form = $this->createForm(CustomerType::class, $data['customer']);
        $form->handleRequest($request);
        $status_customer = $customerStatusTypeRepository->find(Constants::CUSTOMER_STATUS_PENDING);

        if ($form->isSubmitted() && $form->isValid()) {
            $data['customer']->setStatus($status_customer);
            $data['customer']->setPassword($_ENV['PWD_NEW_USER']);
            if ($form->get('customer_type_role')->getData()->getId() == 2) {
                $data['customer']->setGenderType(null);
                $data['customer']->setDateOfBirth(null);
            }

            $data['customer']->setStatusSentCrm($communicationStatesBetweenPlatformsRepository->find(Constants::CBP_STATUS_PENDING));
            $data['customer']->setAttemptsSendCrm(0);

            $entityManager = $em;
            $entityManager->persist($data['customer']);
            $entityManager->flush();

            //envio por helper los datos del cliente al crm
            $sendCustomerToCrm->SendCustomerToCrm($data['customer']);

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
    public function edit(EntityManagerInterface $em, $id, Request $request, CustomerRepository $customerRepository, CommunicationStatesBetweenPlatformsRepository $communicationStatesBetweenPlatformsRepository, SendCustomerToCrm $sendCustomerToCrm): Response
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
            $data['customer']->setStatusSentCrm($communicationStatesBetweenPlatformsRepository->find(Constants::CBP_STATUS_PENDING));
            $data['customer']->setAttemptsSendCrm(0);
            $em->flush();

            //envio por helper los datos del cliente al crm
            $sendCustomerToCrm->SendCustomerToCrm($data['customer']);

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
