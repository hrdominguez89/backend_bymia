<?php

namespace App\Controller\Secure;

use DateTime;
use App\Entity\ApiClients;
use App\Form\ApiClientsType;
use Symfony\Component\Uid\Uuid;
use App\Repository\ApiClientsRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

/**
 * @Route("/api_clients")
 */
class ApiClientsController extends AbstractController
{
    /**
     * @Route("/", name="api_clients")
     */
    public function index(ApiClientsRepository $apiClientsRepository): Response
    {
        $data['title'] = 'Usuarios API';
        $data['api_clients'] = $apiClientsRepository->findAll();
        $data['files_js'] = array('table_full_buttons.js?v=' . rand());
        $data['breadcrumbs'] = array(
            array('active' => true, 'title' => $data['title'])
        );
        return $this->render('secure/api_clients/abm_api_clients.html.twig', $data);
    }

    /**
     * @Route("/new", name="new_api_clients")
     */
    public function new(Request $request): Response
    {
        $data['title'] = "Nuevo usuario API";
        $data['api_client'] = new ApiClients();
        $form = $this->createForm(ApiClientsType::class, $data['api_client']);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $clave['api_client_id'] = Uuid::v1();
            $clave['api_key'] = Uuid::v4();
            $data['api_client']->setApiClientId($clave['api_client_id']);
            $data['api_client']->setApiKey($clave['api_key']);
            $data['api_client']->setCreatedAt(new \DateTime());
            $data['api_client']->setUpdatedAt(new \DateTime());


            // $entityManager = $this->getDoctrine()->getManager();
            // $entityManager->persist($data['api_client']);
            // $entityManager->flush();

            dump($data['api_client']);die();

            $message['type']= 'modal';
            $message['title'] = 'Se creó un nuevo usuario de API';
            $message['message'] = '
                Acontinuación se detalla las credenciales del usuario creado<br>
                <span class="fw-bold">API Client: </span>
                <br>
                <span class="fw-bold">Rol: </span>
                <br>                
                <span class="fw-bold">API Client: </span>
                <br>
                <span class="fw-bold">API Key: </span>
                ';
            $this->addFlash('message',$message);
            return $this->redirectToRoute('api_clients', [], Response::HTTP_SEE_OTHER);
        }

        $data['form'] = $form;
        $data['breadcrumbs'] = array(
            array('path' => 'api_clients', 'title' => 'Usuarios API'),
            array('active' => true, 'title' => $data['title'])
        );
        return $this->renderForm('secure/api_clients/form_api_clients.html.twig', $data);
    }

    /**
     * @Route("/{id}/edit", name="edit_api_clients")
     */
    public function edit($id, Request $request, ApiClientsRepository $apiClientsRepository): Response
    {
        $data['title'] = "Editar usuario API";
        $data['api_client'] = $apiClientsRepository->find($id);
        $form = $this->createForm(ApiClientsType::class, $data['api_client']);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $data['api_client']->setUpdatedAt(new \DateTime());

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($data['api_client']);
            $entityManager->flush();

            return $this->redirectToRoute('api_clients', [], Response::HTTP_SEE_OTHER);
        }

        $data['form'] = $form;
        $data['breadcrumbs'] = array(
            array('path' => 'api_clients', 'title' => 'Usuarios API'),
            array('active' => true, 'title' => $data['title'])
        );
        return $this->renderForm('secure/api_clients/form_api_clients.html.twig', $data);
    }
}
