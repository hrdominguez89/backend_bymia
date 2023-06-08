<?php

namespace App\Controller\Secure;

use App\Entity\Category;
use App\Form\CategoryType;
use App\Repository\CategoryRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Service\FileUploader;
use App\Repository\CommunicationStatesBetweenPlatformsRepository;
use App\Constants\Constants;
use App\Helpers\SendCategoryTo3pl;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;



/**
 * @Route("/category")
 */
class CrudCategoryController extends AbstractController
{

    private $pathImg = 'categories';
    /**
     * @Route("/", name="secure_crud_category_index", methods={"GET"})
     */
    public function index(CategoryRepository $categoryRepository): Response
    {
        $data['title'] = 'Categorías';
        $data['categories'] = $categoryRepository->listCategories();;
        $data['files_js'] = array('table_full_buttons.js?v=' . rand());
        $data['breadcrumbs'] = array(
            array('active' => true, 'title' => $data['title'])
        );

        return $this->render('secure/crud_category/abm_categories.html.twig', $data);
    }

    /**
     * @Route("/new", name="secure_crud_category_new", methods={"GET","POST"})
     */
    public function new(EntityManagerInterface $em, Request $request, FileUploader $fileUploader, CommunicationStatesBetweenPlatformsRepository $communicationStatesBetweenPlatformsRepository, SendCategoryTo3pl $sendCategoryTo3pl): Response
    {
        $data['title'] = 'Nueva categoría';
        $data['breadcrumbs'] = array(
            array('path' => 'secure_crud_category_index', 'title' => 'Categorías'),
            array('active' => true, 'title' => $data['title'])
        );
        $data['category'] = new Category();
        $form = $this->createForm(CategoryType::class, $data['category']);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $data['category']->setStatusSent3pl($communicationStatesBetweenPlatformsRepository->find(Constants::CBP_STATUS_PENDING));
            $imageFile = $form->get('image')->getData();
            if ($imageFile) {
                $imageFileName = $fileUploader->upload($imageFile, $form->get('name')->getData(), $this->pathImg);
                $data['category']->setImage($_ENV['AWS_S3_URL'] . '/' . $this->pathImg . '/' . $imageFileName);
            }
            $entityManager = $em;
            $entityManager->persist($data['category']);
            $entityManager->flush();
            $sendCategoryTo3pl->send($data['category']);


            return $this->redirectToRoute('secure_crud_category_index');
        }
        $data['form'] = $form;

        return $this->renderForm('secure/crud_category/form_categories.html.twig', $data);
    }

    /**
     * @Route("/{id}/edit", name="secure_crud_category_edit", methods={"GET","POST"})
     */
    public function edit($id, EntityManagerInterface $em, Request $request, CategoryRepository $categoryRepository, FileUploader $fileUploader, CommunicationStatesBetweenPlatformsRepository $communicationStatesBetweenPlatformsRepository, SendCategoryTo3pl $sendCategoryTo3pl): Response
    {
        $data['title'] = 'Editar categoría';
        $data['breadcrumbs'] = array(
            array('path' => 'secure_crud_category_index', 'title' => 'Categorías'),
            array('active' => true, 'title' => $data['title'])
        );
        $data['category'] = $categoryRepository->find($id);
        $data['old_name'] = $data['category']->getName();
        $form = $this->createForm(CategoryType::class, $data['category']);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $imageFile = $form->get('image')->getData();
            if ($imageFile) {
                $imageFileName = $fileUploader->upload($imageFile, $form->get('name')->getData(), $this->pathImg);
                $data['category']->setImage($_ENV['AWS_S3_URL'] . '/' . $this->pathImg . '/' . $imageFileName);
            }
            if ($data['old_name'] !== $data['category']->getName()) {
                $data['category']->setStatusSent3pl($communicationStatesBetweenPlatformsRepository->find(Constants::CBP_STATUS_PENDING));
                $data['category']->setAttemptsSend3pl(0);
                $em->flush();
                $sendCategoryTo3pl->send($data['category'], 'PUT', 'update');
            } else {
                $em->flush();
            }

            return $this->redirectToRoute('secure_crud_category_index');
        }
        $data['form'] = $form;

        return $this->renderForm('secure/crud_category/form_categories.html.twig', $data);
    }

    /**
     * @Route("/updateVisible/category", name="secure_category_update_visible", methods={"post"})
     */
    public function updateVisible(EntityManagerInterface $em, Request $request, CategoryRepository $categoryRepository): Response
    {
        $id = (int)$request->get('id');
        $visible = $request->get('visible');


        $entity_object = $categoryRepository->find($id);

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
}
