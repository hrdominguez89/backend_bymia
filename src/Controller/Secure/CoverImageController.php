<?php

namespace App\Controller\Secure;

use App\Entity\CoverImage;
use App\Form\CoverImageType;
use App\Repository\CoverImageRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

use App\Helpers\FileUploader;
use Symfony\Component\HttpFoundation\File\UploadedFile;

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
        return $this->render('secure/cover_image/index.html.twig', [
            'cover_images' => $coverImageRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="secure_cover_image_new", methods={"GET","POST"})
     */
    public function new(Request $request, CoverImageRepository $coverImageRepository, FileUploader $fileUploader): Response
    {
        $coverImage = new CoverImage();
        $form = $this->createForm(CoverImageType::class, $coverImage);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();

            if ($coverImage->isMain()) {
                $coverImageMain = $coverImageRepository->findOneBy(['main' => true]);
                if ($coverImageMain) {
                    $coverImageMain->setMain(false);
                    $entityManager->persist($coverImageMain);
                }
            }

            $imageFile = $form->get('imageLg')->getData();
            if ($imageFile) {
                $imageFileName = $fileUploader->upload($imageFile);
                $coverImage->setImageLg($_ENV['SITE_URL'].'/uploads/images/'.$imageFileName);
            }
            $imageFile = $form->get('imageSm')->getData();
            if ($imageFile) {
                $imageFileName = $fileUploader->upload($imageFile);
                $coverImage->setImageSm($_ENV['SITE_URL'].'/uploads/images/'.$imageFileName);
            }

            $entityManager->persist($coverImage);
            $entityManager->flush();

            return $this->redirectToRoute('secure_cover_image_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('secure/cover_image/new.html.twig', [
            'cover_image' => $coverImage,
            'form' => $form,
        ]);
    }

    /**
     * @Route("/{id}", name="secure_cover_image_show", methods={"GET"})
     */
    public function show(CoverImage $coverImage): Response
    {
        return $this->render('secure/cover_image/show.html.twig', [
            'cover_image' => $coverImage,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="secure_cover_image_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, CoverImageRepository $coverImageRepository, CoverImage $coverImage, FileUploader $fileUploader): Response
    {
        $form = $this->createForm(CoverImageType::class, $coverImage);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();

            if ($coverImage->isMain()) {
                $coverImageMain = $coverImageRepository->findOneBy(['main' => true]);
                if ($coverImageMain) {
                    $coverImageMain->setMain(false);
                    $entityManager->persist($coverImageMain);
                }
            }
            /** @var UploadedFile $imageFile */
            $imageFile = $form->get('imageLg')->getData();
            if ($imageFile) {
                $imageFileName = $fileUploader->upload($imageFile);
                $coverImage->setImageLg($_ENV['SITE_URL'].'/uploads/images/'.$imageFileName);
            }
            $imageFile = $form->get('imageSm')->getData();
            if ($imageFile) {
                $imageFileName = $fileUploader->upload($imageFile);
                $coverImage->setImageSm($_ENV['SITE_URL'].'/uploads/images/'.$imageFileName);
            }
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('secure_cover_image_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('secure/cover_image/edit.html.twig', [
            'cover_image' => $coverImage,
            'form' => $form,
        ]);
    }

    /**
     * @Route("/{id}", name="secure_cover_image_delete", methods={"POST"})
     */
    public function delete(Request $request, CoverImage $coverImage): Response
    {
        if ($this->isCsrfTokenValid('delete' . $coverImage->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($coverImage);
            $entityManager->flush();
        }

        return $this->redirectToRoute('secure_cover_image_index', [], Response::HTTP_SEE_OTHER);
    }
}
