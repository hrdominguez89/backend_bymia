<?php

namespace App\Controller\Secure;

use App\Entity\AboutUs;
use App\Form\AboutUsType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/about-us")
 */
class AboutUsController extends AbstractController
{
    /**
     * @Route("/", name="about_us")
     */
    public function index(EntityManagerInterface $em, Request $request): Response
    {
        $arr_about_us = $em->getRepository(AboutUs::class)->findAll();
        if (empty($arr_about_us))
            $about_us = new AboutUs();
        else
            $about_us = $arr_about_us[0];

        $form = $this->createForm(AboutUsType::class, $about_us);

        $form->handleRequest($request);
        if ($form->isSubmitted()) {
            $data = $request->get('about_us');
            $about_us->setDescription($data['description']);
            $em->persist($about_us);
            $em->flush();
        }
        return $this->render('secure/about_us/index.html.twig', [
            'controller_name' => 'AboutUsController',
            'form' => $form->createView()
        ]);
    }
}
