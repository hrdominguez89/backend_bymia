<?php

namespace App\Controller\Secure;

use App\Entity\CoverImage;
use App\Form\CoverImageType;
use App\Helpers\FileUploader;
use App\Repository\CoverImageRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

/**
 * @Route("/cover-image")
 */
class CoverImageController extends AbstractController
{

    /**
     * @Route("/", name="secure_cover_image_index", methods={"GET"})
     */
    public function index(CoverImageRepository $coverImageRepository): Response
    {
        $data['sliders'] = $coverImageRepository->findBy(array(), array('visible' => 'DESC', 'numberOrder' => 'ASC'));
        $data['title'] = 'Sliders';
        $data['breadcrumbs'] = array(
            array('active' => true, 'title' => $data['title'])
        );
        $data['files_js'] = array('table_reorder.js?v=' . rand());
        return $this->render('secure/cover_image/abm_sliders.html.twig', $data);
    }

    /**
     * @Route("/new", name="secure_cover_image_new", methods={"GET","POST"})
     */
    public function new(Request $request,EntityManagerInterface $em): Response
    {
        $data['slider'] = new CoverImage();
        $form = $this->createForm(CoverImageType::class, $data['slider']);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $em;
            $entityManager->persist($data['slider']);
            $entityManager->flush();

            return $this->redirectToRoute('secure_cover_image_index', [], Response::HTTP_SEE_OTHER);
        }
        $data['title'] = 'Nuevo slider';
        $data['breadcrumbs'] = array(
            array('path' => 'secure_cover_image_index', 'title' => 'Sliders'),
            array('active' => true, 'title' => $data['title'])
        );
        // $data['files_js'] = array(
        //     'ckeditor_text_area.js?v=' . rand(),
        // );
        $data['form'] = $form;
        return $this->renderForm('secure/cover_image/form_cover_image.html.twig', $data);
    }

    /**
     * @Route("/{id}/show", name="secure_cover_image_show", methods={"GET"})
     */
    public function show(): Response
    {
        $coverImage = new CoverImage();
        return $this->render('secure/cover_image/show.html.twig', [
            'cover_image' => $coverImage,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="secure_cover_image_edit", methods={"GET","POST"})
     */
    public function edit($id, CoverImageRepository $coverImageRepository, Request $request,EntityManagerInterface $em): Response
    {
        $data['slider'] = $coverImageRepository->find($id);
        $form = $this->createForm(CoverImageType::class, $data['slider']);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $em;
            $entityManager->persist($data['slider']);
            $entityManager->flush();

            return $this->redirectToRoute('secure_cover_image_index', [], Response::HTTP_SEE_OTHER);
        }
        $data['title'] = 'Editar slider';
        $data['breadcrumbs'] = array(
            array('path' => 'secure_cover_image_index', 'title' => 'Sliders'),
            array('active' => true, 'title' => $data['title'])
        );
        // $data['files_js'] = array(
        //     'ckeditor_text_area.js?v=' . rand(),
        // );
        $data['form'] = $form;
        return $this->renderForm('secure/cover_image/form_cover_image.html.twig', $data);
    }

    /**
     * @Route("/{id}/delete", name="secure_cover_image_delete", methods={"POST"})
     */
    public function delete(Request $request, $id, CoverImageRepository $coverImageRepository,EntityManagerInterface $em): Response
    {
        $coverImage = new CoverImage();
        if ($this->isCsrfTokenValid('delete' . $coverImageRepository->find($id), $request->request->get('_token'))) {
            $entityManager = $em;
            $entityManager->remove($coverImage);
            $entityManager->flush();
        }

        return $this->redirectToRoute('secure_cover_image_index', [], Response::HTTP_SEE_OTHER);
    }


    /**
     * @Route("/updateVisible", name="secure_cover_image_update_visible", methods={"POST"})
     */
    public function updateVisible(Request $request, CoverImageRepository $coverImageRepository,EntityManagerInterface $em): Response
    {
        $id = (int)$request->get('id');
        $visible = $request->get('visible');

        $entity_object = $coverImageRepository->find($id);

        if ($visible == 'on') {
            $entity_object->setVisible(false);
            $data['visible'] = false;
        } else {
            $entity_object->setVisible(true);
            $data['visible'] = true;
        }

        $entityManager = $em;
        $entityManager->persist($entity_object);
        $entityManager->flush();

        $data['status'] = true;

        return new JsonResponse($data);
    }

    /**
     * @Route("/updateOrder", name="secure_cover_image_update_order", methods={"POST"})
     */
    public function updateOrder(Request $request, CoverImageRepository $coverImageRepository,EntityManagerInterface $em): Response
    {
        $ids = $request->get('orderData')['ids'];
        $orders = $request->get('orderData')['orders'];

        foreach ($coverImageRepository->findById($ids) as $obj) {
            $obj->setNumberOrder($orders[array_search($obj->getId(), $ids)]);
        }

        $entityManager = $em;
        // $entityManager->persist($entity_object);
        $entityManager->flush();

        $data['status'] = true;

        return new JsonResponse($data);
    }
}
