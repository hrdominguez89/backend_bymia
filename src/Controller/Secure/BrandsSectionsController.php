<?php

namespace App\Controller\Secure;

use App\Entity\BrandsSections;
use App\Form\BrandsSectionsType;
use App\Repository\BrandsSectionsRepository;
use App\Service\FileUploader;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/brands_sections")
 */
class BrandsSectionsController extends AbstractController
{

    private $pathImg = 'brands';

    /**
     * @Route("/", name="secure_brands_sections_index", methods={"GET","POST"})
     */
    public function new(BrandsSectionsRepository $brandsSections, Request $request, FileUploader $fileUploader, EntityManagerInterface $em): Response
    {

        $data['title'] = 'Marcas';

        $data['breadcrumbs'] = array(
            array('active' => true, 'title' => $data['title'])
        );

        $arr_brands = $brandsSections->findAll();
        if (empty($arr_brands))
            $data['brands'] = new BrandsSections;
        else
            $data['brands'] = $arr_brands[0];

        $form = $this->createForm(BrandsSectionsType::class, $data['brands']);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {

            $brandName1 = $form->get('brandName1')->getData();
            $brandName2 = $form->get('brandName2')->getData();
            $brandName3 = $form->get('brandName3')->getData();
            $brandName4 = $form->get('brandName4')->getData();
            $brandName5 = $form->get('brandName5')->getData();
            $brandName6 = $form->get('brandName6')->getData();
            $brandImage1 = $form->get('brandImage1')->getData();
            $brandImage2 = $form->get('brandImage2')->getData();
            $brandImage3 = $form->get('brandImage3')->getData();
            $brandImage4 = $form->get('brandImage4')->getData();
            $brandImage5 = $form->get('brandImage5')->getData();
            $brandImage6 = $form->get('brandImage6')->getData();


            if (isset($brandImage1)) {
                $imageFileName = $fileUploader->upload($brandImage1, $brandName1, $this->pathImg);
                $data['brands']->setBrandImage1($_ENV['AWS_S3_URL'] . '/' . $this->pathImg . '/' . $imageFileName);
            }
            if (isset($brandImage2)) {
                $imageFileName = $fileUploader->upload($brandImage2, $brandName2, $this->pathImg);
                $data['brands']->setBrandImage2($_ENV['AWS_S3_URL'] . '/' . $this->pathImg . '/' . $imageFileName);
            }
            if (isset($brandImage3)) {
                $imageFileName = $fileUploader->upload($brandImage3, $brandName3, $this->pathImg);
                $data['brands']->setBrandImage3($_ENV['AWS_S3_URL'] . '/' . $this->pathImg . '/' . $imageFileName);
            }
            if (isset($brandImage4)) {
                $imageFileName = $fileUploader->upload($brandImage4, $brandName4, $this->pathImg);
                $data['brands']->setBrandImage4($_ENV['AWS_S3_URL'] . '/' . $this->pathImg . '/' . $imageFileName);
            }
            if (isset($brandImage5)) {
                $imageFileName = $fileUploader->upload($brandImage5, $brandName5, $this->pathImg);
                $data['brands']->setBrandImage5($_ENV['AWS_S3_URL'] . '/' . $this->pathImg . '/' . $imageFileName);
            }
            if (isset($brandImage6)) {
                $imageFileName = $fileUploader->upload($brandImage6, $brandName6, $this->pathImg);
                $data['brands']->setBrandImage6($_ENV['AWS_S3_URL'] . '/' . $this->pathImg . '/' . $imageFileName);
            }

            // $originalImagePath = $_ENV['APP_ENV'] === 'dev' ? 'testing/' . $this->pathImg . '/' . $productNameSlug . '-' . uniqid() . '.jpg' : $this->pathImg . '/' . $productNameSlug . '-' . uniqid() . '.jpg';

            $entityManager = $em;
            $entityManager->persist($data['brands']);
            $entityManager->flush();

            $message['type'] = 'modal';
            $message['alert'] = 'success';
            $message['title'] = 'Éxito';
            $message['message'] = '
                Cambios guardados con éxito
                ';
            $this->addFlash('message', $message);
            return $this->redirectToRoute('secure_brands_sections_index');
        }

        $data['data_img'] = [
            'brandImage1' => $data['brands']->getBrandImage1(),
            'brandImage2' => $data['brands']->getBrandImage2(),
            'brandImage3' => $data['brands']->getBrandImage3(),
            'brandImage4' => $data['brands']->getBrandImage4(),
            'brandImage5' => $data['brands']->getBrandImage5(),
            'brandImage6' => $data['brands']->getBrandImage6(),
        ];

        $data['breadcrumbs'] = array(
            array('active' => true, 'title' => $data['title'])
        );
        $data['form'] = $form;

        return $this->renderForm('secure/brands_sections/brands_sections_form.html.twig', $data);
    }
}
