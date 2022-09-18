<?php

namespace App\Controller\Secure;

use App\Entity\CustomerSupport;
use App\Form\CustomerSupportType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

/**
 * @Route("/customer-support")
 */
class CustomerSupportController extends AbstractController
{
    /**
     * @Route("/", name="customer_support")
     */
    public function index(EntityManagerInterface $em, Request $request): Response
    {
        $arr_customer_support = $em->getRepository(CustomerSupport::class)->findAll();
        if (empty($arr_customer_support))
            $customer_support = new CustomerSupport();
        else
            $customer_support = $arr_customer_support[0];

        $form = $this->createForm(CustomerSupportType::class, $customer_support);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $data = $request->get('customer_support');
            $customer_support->setDescription($data['description']);
            $em->persist($customer_support);
            $em->flush();

            $message['type'] = 'modal';
            $message['alert'] = 'success';
            $message['title'] = 'Éxito';
            $message['message'] = '
                Cambios guardados con éxito
                ';
            $this->addFlash('message', $message);
            return $this->redirectToRoute('customer_support');
        }

        $data['title'] = 'Atención al cliente';
        $data['breadcrumbs'] = array(
            array('active' => true, 'title' => $data['title'])
        );

        $data['files_js'] = array('ckeditor_text_area.js?v=' . rand());
        $data['form'] = $form->createView();

        return $this->render('secure/customer_support/form_customer_support.html.twig', $data);
    }
}
