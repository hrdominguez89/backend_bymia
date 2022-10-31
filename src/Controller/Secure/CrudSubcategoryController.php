<?php

namespace App\Controller\Secure;

use App\Entity\Category;
use App\Entity\Subcategory;
use App\Form\SubcategoryType;
use App\Helpers\FileUploader;
use App\Repository\SubcategoryRepository;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/subcategory")
 */
class CrudSubcategoryController extends AbstractController
{
    /**
     * @Route("/", name="secure_crud_subcategory_index", methods={"GET"})
     */
    public function index(EntityManagerInterface $em, SubcategoryRepository $subcategoryRepository): Response
    {
        $data['subcategories'] = $subcategoryRepository->listSubcategories();
        $data['title'] = 'Subcategorías';
        $data['files_js'] = array('table_full_buttons.js?v=' . rand());
        $data['breadcrumbs'] = array(
            array('active' => true, 'title' => $data['title'])
        );
        return $this->render('secure/crud_subcategory/abm_subcategory.html.twig', $data);
    }

    /**
     * @Route("/new", name="secure_crud_subcategory_new", methods={"GET","POST"})
     */
    public function new(EntityManagerInterface $em, Request $request, FileUploader $fileUploader): Response
    {
        /** @var Category $objCategory */
        $subcategory = new Subcategory();
        $form = $this->createForm(SubcategoryType::class, $subcategory);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $imageFile = $form->get('image')->getData();
            if ($imageFile) {
                $imageFileName = $fileUploader->upload($imageFile);
            }
            $entityManager->persist($subcategory);
            $entityManager->flush();

            return $this->redirectToRoute('secure_crud_subcategory_index');
        }

        return $this->renderForm('secure/crud_subcategory/new.html.twig', [
            'subcategory' => $subcategory,
            'form' => $form,
        ]);
    }


    /**
     * @Route("/subcategory/{subcategory_id}/edit", name="secure_crud_subcategory_edit", methods={"GET","POST"})
     */
    public function edit($subcategory_id, Request $request,SubcategoryRepository $subcategoryRepository, FileUploader $fileUploader): Response
    {
        $subcategory = $subcategoryRepository->find($subcategory_id);
        $form = $this->createForm(SubcategoryType::class, $subcategory);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $imageFile = $form->get('image')->getData();
            if ($imageFile) {
                $imageFileName = $fileUploader->upload($imageFile);
            }
            $entityManager->persist($subcategory);
            $entityManager->flush();

            return $this->redirectToRoute('secure_crud_subcategory_index');
        }

        return $this->renderForm('secure/crud_subcategory/new.html.twig', [
            'subcategory' => $subcategory,
            'form' => $form,
        ]);
    }

}
