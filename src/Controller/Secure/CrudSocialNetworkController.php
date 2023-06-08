<?php

namespace App\Controller\Secure;

use App\Entity\SocialNetwork;
use App\Form\SocialNetworkType;
use App\Repository\SocialNetworkRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/social-network")
 */
class CrudSocialNetworkController extends AbstractController
{
    /**
     * @Route("/", name="secure_crud_social_network_index", methods={"GET"})
     */
    public function index(SocialNetworkRepository $socialNetworkRepository): Response
    {
        $data['title'] = 'Redes sociales';
        $data['social_networks'] = $socialNetworkRepository->findAll();
        $data['files_js'] = array('table_simple.js?v=' . rand());
        $data['breadcrumbs'] = array(
            array('active' => true, 'title' => $data['title'])
        );
        return $this->render('secure/crud_social_network/abm_redes.html.twig', $data);
    }

    /**
     * @Route("/new", name="secure_crud_social_network_new", methods={"GET","POST"})
     */
    public function new(Request $request, EntityManagerInterface $em): Response
    {
        $socialNetwork = new SocialNetwork();
        $form = $this->createForm(SocialNetworkType::class, $socialNetwork);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $em;
            $entityManager->persist($socialNetwork);
            $entityManager->flush();
            return $this->redirectToRoute('secure_crud_social_network_index', [], Response::HTTP_SEE_OTHER);
        }

        $data['form'] = $form;
        $data['social_network'] = $socialNetwork;
        $data['title'] = 'Nueva red social';
        $data['breadcrumbs'] = array(
            array('path' => 'secure_crud_social_network_index', 'title' => 'Redes sociales'),
            array('active' => true, 'title' => $data['title'])
        );

        return $this->renderForm('secure/crud_social_network/form_redes.html.twig', $data);
    }

    /**
     * @Route("/{id}", name="secure_crud_social_network_show", methods={"GET"})
     */
    public function show($id, SocialNetworkRepository $socialNetworkRepository): Response
    {
        $socialNetwork = $socialNetworkRepository->find($id);
        $data['social_network'] = $socialNetwork;
        $data['title'] = 'Red social';
        $data['breadcrumbs'] = array(
            array('path' => 'secure_crud_social_network_index', 'title' => 'Redes sociales'),
            array('active' => true, 'title' => $data['title'])
        );
        return $this->render('secure/crud_social_network/show.html.twig', $data);
    }

    /**
     * @Route("/{id}/edit", name="secure_crud_social_network_edit", methods={"GET","POST"})
     */
    public function edit($id, EntityManagerInterface $em, SocialNetworkRepository $socialNetworkRepository, Request $request): Response
    {
        $socialNetwork = $socialNetworkRepository->find($id);
        $form = $this->createForm(SocialNetworkType::class, $socialNetwork);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em->flush();

            return $this->redirectToRoute('secure_crud_social_network_index', [], Response::HTTP_SEE_OTHER);
        }
        $data['form'] = $form;
        $data['social_network'] = $socialNetwork;
        $data['title'] = 'Editar red social';
        $data['breadcrumbs'] = array(
            array('path' => 'secure_crud_social_network_index', 'title' => 'Redes sociales'),
            array('active' => true, 'title' => $data['title'])
        );
        return $this->renderForm('secure/crud_social_network/form_redes.html.twig', $data);
    }

    /**
     * @Route("/{id}", name="secure_crud_social_network_delete", methods={"POST"})
     */
    public function delete(EntityManagerInterface $em, Request $request, $id, SocialNetworkRepository $socialNetworkRepository): Response
    {
        $socialNetwork = $socialNetworkRepository->find($id);
        if ($this->isCsrfTokenValid('delete' . $socialNetwork->getId(), $request->request->get('_token'))) {
            $entityManager = $em;
            $entityManager->remove($socialNetwork);
            $entityManager->flush();
        }

        return $this->redirectToRoute('secure_crud_social_network_index', [], Response::HTTP_SEE_OTHER);
    }
}
