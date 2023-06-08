<?php

namespace App\Controller\Secure;

use App\Entity\Brand;
use App\Form\BrandType;
use App\Repository\BrandRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Service\FileUploader;
use App\Repository\CommunicationStatesBetweenPlatformsRepository;
use App\Constants\Constants;
use App\Helpers\SendBrandTo3pl;
use Doctrine\ORM\EntityManagerInterface;

/**
 * @Route("/brand")
 */
class CrudBrandController extends AbstractController
{

    private $pathImg = 'brands';


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
        return $this->render('secure/crud_brand/abm_brand.html.twig', $data);
    }

    /**
     * @Route("/new", name="secure_crud_brand_new", methods={"GET","POST"})
     */
    public function new(EntityManagerInterface $em, Request $request, FileUploader $fileUploader, CommunicationStatesBetweenPlatformsRepository $communicationStatesBetweenPlatformsRepository, SendBrandTo3pl $sendBrandTo3pl): Response
    {
        $data['title'] = 'Nueva marca';
        $data['breadcrumbs'] = array(
            array('path' => 'secure_crud_brand_index', 'title' => 'Marcas'),
            array('active' => true, 'title' => $data['title'])
        );

        $data['brand'] = new Brand;
        $form = $this->createForm(BrandType::class, $data['brand']);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $data['brand']->setStatusSent3pl($communicationStatesBetweenPlatformsRepository->find(Constants::CBP_STATUS_PENDING));
            $imageFile = $form->get('image')->getData();
            if ($imageFile) {
                $imageFileName = $fileUploader->upload($imageFile, $form->get('name')->getData(), $this->pathImg);
                $data['brand']->setImage($_ENV['AWS_S3_URL'] . '/' . $this->pathImg . '/' . $imageFileName);
            }
            $entityManager = $em;
            $entityManager->persist($data['brand']);
            $entityManager->flush();
            $sendBrandTo3pl->send($data['brand']);

            return $this->redirectToRoute('secure_crud_brand_index');
        }
        $data['form'] = $form;
        return $this->renderForm('secure/crud_brand/form_brand.html.twig', $data);
    }

    /**
     * @Route("/{id}/edit", name="secure_crud_brand_edit", methods={"GET","POST"})
     */
    public function edit(EntityManagerInterface $em,$id, Request $request, BrandRepository $brandRepository, FileUploader $fileUploader, CommunicationStatesBetweenPlatformsRepository $communicationStatesBetweenPlatformsRepository, SendBrandTo3pl $sendBrandTo3pl): Response
    {
        $data['title'] = 'Editar marca';
        $data['breadcrumbs'] = array(
            array('path' => 'secure_crud_brand_index', 'title' => 'Marcas'),
            array('active' => true, 'title' => $data['title'])
        );
        $data['brand'] = $brandRepository->find($id);
        $data['old_name'] = $data['brand']->getName();
        $form = $this->createForm(BrandType::class, $data['brand']);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $imageFile = $form->get('image')->getData();
            if ($imageFile) {
                $imageFileName = $fileUploader->upload($imageFile, $form->get('name')->getData(), $this->pathImg);
                $data['brand']->setImage($_ENV['AWS_S3_URL'] . '/' . $this->pathImg . '/' . $imageFileName);
            }

            $data['brand']->setStatusSent3pl($communicationStatesBetweenPlatformsRepository->find(Constants::CBP_STATUS_PENDING));
            $data['brand']->setAttemptsSend3pl(0);
            $em->flush();
            $sendBrandTo3pl->send($data['brand'], 'PUT', 'update');

            return $this->redirectToRoute('secure_crud_brand_index');
        }
        $data['form'] = $form;

        return $this->renderForm('secure/crud_brand/form_brand.html.twig', $data);
    }

    /**
     * @Route("/{id}", name="secure_crud_brand_delete", methods={"POST"})
     */
    public function delete(Request $request, $id, BrandRepository $brandRepository,EntityManagerInterface $em): Response
    {
        $brand = $brandRepository->find($id);
        if ($this->isCsrfTokenValid('delete' . $brand->getId(), $request->request->get('_token'))) {
            $entityManager = $em;
            $entityManager->remove($brand);
            $entityManager->flush();
        }

        return $this->redirectToRoute('secure_crud_brand_index', [], Response::HTTP_SEE_OTHER);
    }

    /**
     * @Route("/updateVisible/brand", name="secure_brand_update_visible", methods={"post"})
     */
    public function updateVisible(Request $request, BrandRepository $brandRepository,EntityManagerInterface $em): Response
    {
        $id = (int)$request->get('id');
        $visible = $request->get('visible');


        $entity_object = $brandRepository->find($id);

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
