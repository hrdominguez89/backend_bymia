<?php

namespace App\Controller\Secure;

use App\Entity\Refund;
use App\Form\RefundType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

/**
 * @Route("/refund")
 */
class RefundController extends AbstractController
{
    /**
     * @Route("/", name="refund")
     */
    public function index(EntityManagerInterface $em, Request $request): Response
    {
        $arr_refund = $em->getRepository(Refund::class)->findAll();
        if (empty($arr_refund))
            $refund = new Refund();
        else
            $refund = $arr_refund[0];

        $form = $this->createForm(RefundType::class, $refund);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $data = $request->get('refund');
            $refund->setDescription($data['description']);
            $em->persist($refund);
            $em->flush();

            $message['type'] = 'modal';
            $message['alert'] = 'success';
            $message['title'] = 'Éxito';
            $message['message'] = '
                Cambios guardados con éxito
                ';
            $this->addFlash('message', $message);
            return $this->redirectToRoute('refund');
        }

        $data['title'] = 'Devolución';
        $data['breadcrumbs'] = array(
            array('active' => true, 'title' => $data['title'])
        );

        $data['files_js'] = array('ckeditor_text_area.js?v=' . rand());
        $data['form'] = $form->createView();
        return $this->render('secure/refund/form_refund.html.twig', $data);
    }
}
