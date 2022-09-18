<?php

namespace App\Controller\Secure;

use App\Entity\PrivacyPolicy;
use App\Form\PrivacyPolicyType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

/**
 * @Route("/privacy-policy")
 */
class PrivacyPolicyController extends AbstractController
{
    /**
     * @Route("/", name="privacy_policy")
     */
    public function index(EntityManagerInterface $em, Request $request): Response
    {
        $arr_privacy_policy = $em->getRepository(PrivacyPolicy::class)->findAll();
        if (empty($arr_privacy_policy))
            $privacy_policy = new PrivacyPolicy();
        else
            $privacy_policy = $arr_privacy_policy[0];

        $form = $this->createForm(PrivacyPolicyType::class, $privacy_policy);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $data = $request->get('privacy_policy');
            $privacy_policy->setDescription($data['description']);
            $em->persist($privacy_policy);
            $em->flush();

            $message['type'] = 'modal';
            $message['alert'] = 'success';
            $message['title'] = 'Éxito';
            $message['message'] = '
                Cambios guardados con éxito
                ';
            $this->addFlash('message', $message);
            return $this->redirectToRoute('privacy_policy');
        }

        $data['title'] = 'Política de privacidad';
        $data['breadcrumbs'] = array(
            array('active' => true, 'title' => $data['title'])
        );

        $data['files_js'] = array('ckeditor_text_area.js?v=' . rand());
        $data['form'] = $form->createView();

        return $this->render('secure/privacy_policy/form_privacy_policy.html.twig', $data);
    }
}
