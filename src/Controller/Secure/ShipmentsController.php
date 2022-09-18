<?php

namespace App\Controller\Secure;

use App\Entity\Shipments;
use App\Form\ShipmentsType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

/**
 * @Route("/shipments")
 */

class ShipmentsController extends AbstractController
{
    /**
     * @Route("/", name="shipments")
     */
    public function index(EntityManagerInterface $em, Request $request): Response
    {
        $arr_shipments = $em->getRepository(Shipments::class)->findAll();
        if (empty($arr_shipments))
            $shipments = new Shipments();
        else
            $shipments = $arr_shipments[0];

        $form = $this->createForm(ShipmentsType::class, $shipments);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $data = $request->get('shipments');
            $shipments->setDescription($data['description']);
            $em->persist($shipments);
            $em->flush();

            $message['type'] = 'modal';
            $message['alert'] = 'success';
            $message['title'] = 'Éxito';
            $message['message'] = '
                Cambios guardados con éxito
                ';
            $this->addFlash('message', $message);
            return $this->redirectToRoute('shipments');
        }

        $data['title'] = 'Envíos';
        $data['breadcrumbs'] = array(
            array('active' => true, 'title' => $data['title'])
        );

        $data['files_js'] = array('ckeditor_text_area.js?v=' . rand());
        $data['form'] = $form->createView();
        return $this->render('secure/shipments/form_shipments.html.twig', $data);
    }
}
