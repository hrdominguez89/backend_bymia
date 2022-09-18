<?php

namespace App\Controller\Secure;

use App\Entity\TermsConditions;
use App\Form\TermsConditionsType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/terms-conditions")
 */
class TermsConditionsController extends AbstractController
{
    /**
     * @Route("/", name="terms_conditions")
     */
    public function index(EntityManagerInterface $em, Request $request): Response
    {
        $arr_terms_conditions = $em->getRepository(TermsConditions::class)->findAll();
        if (empty($arr_terms_conditions))
            $terms_conditions = new TermsConditions();
        else
            $terms_conditions = $arr_terms_conditions[0];

        $form = $this->createForm(TermsConditionsType::class, $terms_conditions);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $data = $request->get('terms_conditions');
            $terms_conditions->setDescription($data['description']);
            $em->persist($terms_conditions);
            $em->flush();

            $message['type'] = 'modal';
            $message['alert'] = 'success';
            $message['title'] = 'Éxito';
            $message['message'] = '
                Cambios guardados con éxito
                ';
            $this->addFlash('message', $message);
            return $this->redirectToRoute('terms_conditions');
        }

        $data['title'] = 'Términos y condiciones';
        $data['breadcrumbs'] = array(
            array('active' => true, 'title' => $data['title'])
        );

        $data['files_js'] = array('ckeditor_text_area.js?v=' . rand());
        $data['form'] = $form->createView();

        return $this->render('secure/terms_conditions/form_terminos_y_condiciones.html.twig', $data);
    }
}
