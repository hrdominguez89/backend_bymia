<?php

namespace App\Controller\Secure;

use App\Entity\Customer;
use App\Form\CustomerSearchType;
use App\Form\CustomerType;
use App\Form\Model\CustomerSearchDto;
use App\Helpers\FileUploader;
use App\Repository\CustomerRepository;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

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

        $customers = $customerRepository->findAll();
        
        // dump($customers);die();

        $data['customers'] = $customers;
        $data['files_js'] = array('table_full_buttons.js?v=' . rand());

        return $this->render('secure/crud_customer/index.html.twig', $data);
    }

    /**
     * @Route("/new", name="secure_crud_customer_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        $customer = new Customer();
        $form = $this->createForm(CustomerType::class, $customer);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($customer);
            $entityManager->flush();

            return $this->redirectToRoute('secure_crud_customer_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('secure/crud_customer/new.html.twig', [
            'customer' => $customer,
            'form' => $form,
        ]);
    }

    /**
     * @Route("/{id}", name="secure_crud_customer_show", methods={"GET"})
     */
    public function show(Customer $customer): Response
    {
        return $this->render('secure/crud_customer/show.html.twig', [
            'customer' => $customer,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="secure_crud_customer_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Customer $customer, FileUploader $fileUploader): Response
    {
        $form = $this->createForm(CustomerType::class, $customer);
        $form->handleRequest($request);

        if ($form->isSubmitted() /*&& $form->isValid()*/) {
            /** @var UploadedFile $imageFile */
            $imageFile = $form->get('image')->getData();
            if ($imageFile) {
                $imageFileName = $fileUploader->upload($imageFile);
                $customer->setImage('uploads/images/' . $imageFileName);
            }
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('secure_crud_customer_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('secure/crud_customer/edit.html.twig', [
            'customer' => $customer,
            'form' => $form,
        ]);
    }

    /**
     * @Route("/{id}", name="secure_crud_customer_delete", methods={"POST"})
     */
    public function delete(Request $request, Customer $customer): Response
    {
        if ($this->isCsrfTokenValid('delete' . $customer->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($customer);
            $entityManager->flush();
        }

        return $this->redirectToRoute('secure_crud_customer_index', [], Response::HTTP_SEE_OTHER);
    }
}
