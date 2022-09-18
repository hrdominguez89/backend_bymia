<?php

namespace App\Controller\Secure;

use App\Entity\ContactUs;
use App\Form\ContactUsType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/contact-us")
 */
class ContactUsController extends AbstractController
{
    /**
     * @Route("/", name="contact_us")
     */
    public function index(EntityManagerInterface $em, Request $request): Response
    {
        $arr_obj_contact_us = $em->getRepository(ContactUs::class)->findAll();
        if (empty($arr_obj_contact_us))
            $contact_us = new ContactUs();
        else
            $contact_us = $arr_obj_contact_us[0];

        $form = $this->createForm(ContactUsType::class, $contact_us);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $data = $request->get('contact_us');
            $contact_us
                ->setEmail($data['email'])
                ->setDescription($data['description'])
                ->setUrl($data['url'])
                ->setAddress($data['address'])
                ->setPhoneMain($data['phoneMain'])
                ->setPhoneOther($data['phoneOther']);
            $em->persist($contact_us);
            $em->flush();

            $message['type'] = 'modal';
            $message['alert'] = 'success';
            $message['title'] = 'Éxito';
            $message['message'] = '
                Cambios guardados con éxito
                ';
            $this->addFlash('message', $message);
            return $this->redirectToRoute('contact_us');
        }
        $data['title'] = 'Acerca de nosotros';
        $data['breadcrumbs'] = array(
            array('active' => true, 'title' => $data['title'])
        );
        $data['form'] = $form->createView();
        return $this->render('secure/contact_us/form_contact_us.html.twig', $data);
    }
}
