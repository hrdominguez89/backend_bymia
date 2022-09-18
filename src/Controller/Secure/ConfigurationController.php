<?php

namespace App\Controller\Secure;

use App\Entity\Configuration;
use App\Form\ConfigurationType;
use App\Repository\ConfigurationRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

use App\Helpers\FileUploader;
use Symfony\Component\HttpFoundation\File\UploadedFile;

/**
 * @Route("/configuration")
 */
class ConfigurationController extends AbstractController
{
    /**
     * @Route("/", name="secure_configuration_index", methods={"GET"})
     */
    public function index(ConfigurationRepository $configurationRepository): Response
    {
        $data['configurations'] = $configurationRepository->findAll();
        $data['title'] = 'Iconos inicio';
        $data['breadcrumbs'] = array(
            array('active' => true, 'title' => $data['title'])
        );
        $data['files_js'] = array('table_simple.js?v=' . rand());
        return $this->render('secure/configuration/abm_home_icons.html.twig', $data);
    }

    /**
     * @Route("/new", name="secure_configuration_new", methods={"GET","POST"})
     */
    public function new(Request $request, FileUploader $fileUploader): Response
    {
        $configuration = new Configuration();
        $form = $this->createForm(ConfigurationType::class, $configuration);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            /** @var UploadedFile $imageFile */
            $imageFile = $form->get('image')->getData();
            if ($imageFile) {
                $imageFileName = $fileUploader->upload($imageFile);
                $configuration->setImage($_ENV['SITE_URL'] . '/uploads/images/' . $imageFileName);
            }
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($configuration);
            $entityManager->flush();

            return $this->redirectToRoute('secure_configuration_index', [], Response::HTTP_SEE_OTHER);
        }
        $data['configuration'] = $configuration;
        $data['form'] = $form;
        $data['title'] = 'Nuevo icono de inicio';
        $data['breadcrumbs'] = array(
            array('path' => 'secure_configuration_index', 'title' => 'Iconos de inicio'),
            array('active' => true, 'title' => $data['title'])
        );

        return $this->renderForm('secure/configuration/form_home_icons.html.twig', $data);
    }

    /**
     * @Route("/{id}/show", name="secure_configuration_show", methods={"GET"})
     */
    public function show(Configuration $configuration): Response
    {
        $data['configuration'] = $configuration;
        $data['title'] = 'Icono de inicio';
        $data['breadcrumbs'] = array(
            array('path' => 'secure_configuration_index', 'title' => 'Iconos de incio'),
            array('active' => true, 'title' => $data['title'])
        );
        return $this->render('secure/configuration/show.html.twig', $data);
    }

    /**
     * @Route("/{id}/edit", name="secure_configuration_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Configuration $configuration, FileUploader $fileUploader): Response
    {
        $form = $this->createForm(ConfigurationType::class, $configuration);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            /** @var UploadedFile $imageFile */
            $imageFile = $form->get('image')->getData();
            if ($imageFile) {
                $imageFileName = $fileUploader->upload($imageFile);
                $configuration->setImage($_ENV['SITE_URL'] . '/uploads/images/' . $imageFileName);
            }
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('secure_configuration_index', [], Response::HTTP_SEE_OTHER);
        }

        $data['configuration'] = $configuration;
        $data['form'] = $form;
        $data['title'] = 'Editar icono de inicio';
        $data['breadcrumbs'] = array(
            array('path' => 'secure_configuration_index', 'title' => 'Iconos de inicio'),
            array('active' => true, 'title' => $data['title'])
        );

        return $this->renderForm('secure/configuration/form_home_icons.html.twig', $data);
    }

    /**
     * @Route("/{id}", name="secure_configuration_delete", methods={"POST"})
     */
    public function delete(Request $request, Configuration $configuration): Response
    {
        if ($this->isCsrfTokenValid('delete' . $configuration->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($configuration);
            $entityManager->flush();
        }

        return $this->redirectToRoute('secure_configuration_index', [], Response::HTTP_SEE_OTHER);
    }
}
