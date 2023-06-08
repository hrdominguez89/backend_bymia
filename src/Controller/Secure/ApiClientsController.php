<?php

namespace App\Controller\Secure;

use DateTime;
use App\Entity\ApiClients;
use App\Form\ApiClientsType;
use Symfony\Component\Uid\Uuid;
use App\Repository\ApiClientsRepository;
use Doctrine\ORM\EntityManagerInterface;
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
    public function new(Request $request,EntityManagerInterface $em): Response
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


            $entityManager = $em;
            $entityManager->persist($data['api_client']);
            $entityManager->flush();

            $message['type'] = 'modal';
            $message['alert'] = 'success';
            $message['size'] = 'modal-xl';
            $message['title'] = 'Se creó un nuevo usuario de API';
            $message['message'] = '
                A continuación se detalla las credenciales del usuario creado<br>
                <br>
                Anotelas ya que no podrá volver a consultarlas, en caso de olvido o pérdida deberá reiniciar la api key.<br>
                <br>
                <span class="fw-bold">API Client: </span>' . $data['api_client']->getName() . '
                <br>
                <span class="fw-bold">Rol: </span>' . $data['api_client']->getApiClientTypeRole()->getName() . '
                <br>
                <span class="fw-bold">Authentication: </span> Basic
                <br>               
                <span class="fw-bold">Username: </span> ' . $clave['api_client_id'] . '
                <br>
                <span class="fw-bold">Password: </span> ' . $clave['api_key'] . '
                <br>
                <span class="fw-bold">Clave encriptada: </span> ' . base64_encode($clave['api_client_id'] . ':' . $clave['api_key']) . '
                <br>
                ';
            $this->addFlash('message', $message);
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
    public function edit($id, Request $request, ApiClientsRepository $apiClientsRepository,EntityManagerInterface $em): Response
    {
        $data['reset_api_key'] = true;
        $data['title'] = "Editar usuario API";
        $data['api_client'] = $apiClientsRepository->find($id);
        $form = $this->createForm(ApiClientsType::class, $data['api_client']);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            if ($form->get('reset_api_key')->getData()) {
                $clave['api_key'] = Uuid::v4();
                $data['api_client']->setApiKey($clave['api_key']);
                $message['type'] = 'modal';
                $message['alert'] = 'success';
                $message['size'] = 'modal-xl';
                $message['title'] = 'Se editó usuario de API';
                $message['message'] = '
                    A continuación se detalla las credenciales del usuario editado<br>
                    <br>
                    Anotelas ya que no podrá volver a consultarlas, en caso de olvido o pérdida deberá reiniciar la api key.<br>
                    <br>
                    <span class="fw-bold">API Client: </span>' . $data['api_client']->getName() . '
                    <br>
                    <span class="fw-bold">Rol: </span>' . $data['api_client']->getApiClientTypeRole()->getName() . '
                    <br>                
                    <span class="fw-bold">Authentication: </span> Basic
                    <br>                
                    <span class="fw-bold">Username: </span> ' . $data['api_client']->getApiClientId() . '
                    <br>
                    <span class="fw-bold">Password: </span> ' . $clave['api_key'] . '
                    <br>
                    <span class="fw-bold">Clave encriptada: </span> ' . base64_encode($data['api_client']->getApiClientId() . ':' . $clave['api_key']) . '
                    <br>
                    ';
                $this->addFlash('message', $message);
            }
            $entityManager = $em;
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
