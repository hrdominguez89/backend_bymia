<?php

namespace App\Controller\Secure;

use App\Entity\Category;
use App\Form\CategoryType;
use App\Helpers\FileUploader;
use App\Repository\CategoryRepository;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/category")
 */
class CrudCategoryController extends AbstractController
{
    /**
     * @Route("/", name="secure_crud_category_index", methods={"GET"})
     */
    public function index(CategoryRepository $categoryRepository, PaginatorInterface $pagination, Request $request): Response
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
    public function new(Request $request, FileUploader $fileUploader): Response
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
            $imageFile = $form->get('image')->getData();
            if ($imageFile) {
                $imageFileName = $fileUploader->upload($imageFile);
                $data['category']->setImage($_ENV['SITE_URL'] . '/uploads/images/' . $imageFileName);
            }
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('secure_crud_category_index', [], Response::HTTP_SEE_OTHER);
        }
        $data['form'] = $form;

        return $this->renderForm('secure/crud_category/form_categories.html.twig', $data);
    }

    /**
     * @Route("/{id}/edit", name="secure_crud_category_edit", methods={"GET","POST"})
     */
    public function edit($id, Request $request, CategoryRepository $categoryRepository, FileUploader $fileUploader): Response
    {
        $data['title'] = 'Editar categoría';
        $data['breadcrumbs'] = array(
            array('path' => 'secure_crud_category_index', 'title' => 'Categorías'),
            array('active' => true, 'title' => $data['title'])
        );
        $data['category'] = $categoryRepository->find($id);
        $form = $this->createForm(CategoryType::class, $data['category']);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $imageFile = $form->get('image')->getData();
            if ($imageFile) {
                $imageFileName = $fileUploader->upload($imageFile);
                $data['category']->setImage($_ENV['SITE_URL'] . '/uploads/images/' . $imageFileName);
            }
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('secure_crud_category_index', [], Response::HTTP_SEE_OTHER);
        }
        $data['form'] = $form;

        return $this->renderForm('secure/crud_category/form_categories.html.twig', $data);
    }

    /**
     * @Route("/{id}", name="secure_crud_category_delete", methods={"POST"})
     */
    public function delete(Request $request, Category $category): Response
    {
        if ($this->isCsrfTokenValid('delete' . $category->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($category);
            $entityManager->flush();
        }

        return $this->redirectToRoute('secure_crud_category_index', [], Response::HTTP_SEE_OTHER);
    }
}
