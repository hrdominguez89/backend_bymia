<?php

namespace App\Controller\Secure;

use App\Entity\Brand;
use App\Form\BrandType;
use App\Helpers\FileUploader;
use App\Repository\BrandRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/brand")
 */
class CrudBrandController extends AbstractController
{
    /**
     * @Route("/", name="secure_crud_brand_index", methods={"GET"})
     */
    public function index(BrandRepository $brandRepository): Response
    {
        $data['title'] = 'Marcas';
        $data['brands'] = $brandRepository->findAll();
        $data['files_js'] = array('table_full_buttons.js?v=' . rand());
        $data['breadcrumbs'] = array(
            array('active' => true, 'title' => $data['title'])
        );
        return $this->render('secure/crud_brand/index.html.twig', $data);
    }

    /**
     * @Route("/new", name="secure_crud_brand_new", methods={"GET","POST"})
     */
    public function new(Request $request, FileUploader $fileUploader): Response
    {
        $brand = new Brand();
        $form = $this->createForm(BrandType::class, $brand);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $imageFile = $form->get('image')->getData();
            if ($imageFile) {
                $imageFileName = $fileUploader->upload($imageFile);
                $brand->setImage($_ENV['SITE_URL'] . '/uploads/images/' . $imageFileName);
            }
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($brand);
            $entityManager->flush();

            return $this->redirectToRoute('secure_crud_brand_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('secure/crud_brand/new.html.twig', [
            'brand' => $brand,
            'form' => $form,
        ]);
    }

    /**
     * @Route("/{id}", name="secure_crud_brand_show", methods={"GET"})
     */
    public function show(BrandRepository $brandRepository, Brand $brand): Response
    {
        return $this->render('secure/crud_brand/show.html.twig', [
            'brand' => $brand,
            'cantDelete' => count($brandRepository->getCantProductByBrand($brand->getId())) > 0 ? false : true,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="secure_crud_brand_edit", methods={"GET","POST"})
     */
    public function edit($id, Request $request, BrandRepository $brandRepository, FileUploader $fileUploader): Response
    {

        $data['brand'] = $brandRepository->find($id);
        dump($data['brand']->getApiId());
        die();
        $form = $this->createForm(BrandType::class, $data['brand']);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $imageFile = $form->get('image')->getData();
            if ($imageFile) {
                $imageFileName = $fileUploader->upload($imageFile);
                $data['brand']->setImage($_ENV['SITE_URL'] . '/uploads/images/' . $imageFileName);
            }
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('secure_crud_brand_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('secure/crud_brand/edit.html.twig', [
            'brand' => $data['brand'],
            'form' => $form,
        ]);
    }

    /**
     * @Route("/{id}", name="secure_crud_brand_delete", methods={"POST"})
     */
    public function delete(Request $request, Brand $brand): Response
    {
        if ($this->isCsrfTokenValid('delete' . $brand->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($brand);
            $entityManager->flush();
        }

        return $this->redirectToRoute('secure_crud_brand_index', [], Response::HTTP_SEE_OTHER);
    }
}
