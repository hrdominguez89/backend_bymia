<?php

namespace App\Controller\Secure;

use App\Entity\SectionsHome;
use App\Form\SectionsHomeType;
use App\Repository\SectionsHomeRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/sections_home")
 */
class SectionsHomeController extends AbstractController
{

    /**
     * @Route("/", name="secure_sections_home_index", methods={"GET","POST"})
     */
    public function index(EntityManagerInterface $em,SectionsHomeRepository $sectionsHomeRepository, Request $request): Response
    {
        $data['title'] = 'Secciones de la home';
        $data['breadcrumbs'] = array(
            array('active' => true, 'title' => $data['title'])
        );

        $arr_sections = $sectionsHomeRepository->findAll();
        if (empty($arr_sections))
            $data['sections_home'] = new SectionsHome;
        else
            $data['sections_home'] = $arr_sections[0];

        $form = $this->createForm(SectionsHomeType::class, $data['sections_home']);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {

            $entityManager = $em;
            $entityManager->persist($data['sections_home']);
            $entityManager->flush();
            
            $message['type'] = 'modal';
            $message['alert'] = 'success';
            $message['title'] = 'Éxito';
            $message['message'] = '
                Cambios guardados con éxito
                ';
            $this->addFlash('message', $message);
            return $this->redirectToRoute('secure_sections_home_index');
        }
        $data['form'] = $form;
        return $this->renderForm('secure/sections_home/home_section_form.html.twig', $data);
    }
}
