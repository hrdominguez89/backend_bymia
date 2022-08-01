<?php

namespace App\Controller\Secure;

use App\Entity\Advertisements;
use App\Form\AdvertisementsType;
use App\Helpers\FileUploader;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/advertisements")
 */
class CrudAdvertisementsController extends AbstractController
{

    /**
     * @Route("/", name="secure_crud_advertisements_new", methods={"GET","POST"})
     */
    public function new(EntityManagerInterface $em, Request $request, FileUploader $fileUploader): Response
    {
        $arr_advertisement = $em->getRepository(Advertisements::class)->findAll();
        if (empty($arr_advertisement))
            $advertisement = new Advertisements();
        else
            $advertisement = $arr_advertisement[0];
        $form = $this->createForm(AdvertisementsType::class, $advertisement);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $src1 = $form->get('src1')->getData();
            $srcSm1 = $form->get('srcSm1')->getData();
            $src2 = $form->get('src2')->getData();
            $srcSm2 = $form->get('srcSm2')->getData();
            $src3 = $form->get('src3')->getData();
            $srcSm3 = $form->get('srcSm3')->getData();
            if (isset($src1)) {
                $imageFileName = $fileUploader->upload($src1);
                $advertisement->setSrc1($_ENV['SITE_URL'].'/uploads/images/' . $imageFileName);
            }
            if (isset($srcSm1)) {
                $imageFileName = $fileUploader->upload($srcSm1);
                $advertisement->setSrcSm1($_ENV['SITE_URL'].'/uploads/images/' . $imageFileName);
            }
            if (isset($src2)) {
                $imageFileName = $fileUploader->upload($src2);
                $advertisement->setSrc2($_ENV['SITE_URL'].'/uploads/images/' . $imageFileName);
            }
            if (isset($srcSm2)) {
                $imageFileName = $fileUploader->upload($srcSm2);
                $advertisement->setSrcSm2($_ENV['SITE_URL'].'/uploads/images/' . $imageFileName);
            }
            if (isset($src3)) {
                $imageFileName = $fileUploader->upload($src3);
                $advertisement->setSrc3($_ENV['SITE_URL'].'/uploads/images/' . $imageFileName);
            }
            if (isset($srcSm3)) {
                $imageFileName = $fileUploader->upload($srcSm3);
                $advertisement->setSrcSm3($_ENV['SITE_URL'].'/uploads/images/' . $imageFileName);
            }

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($advertisement);
            $entityManager->flush();
        }

        $data = [
            'src1'=>$advertisement->getSrc1(),
            'src2'=>$advertisement->getSrc2(),
            'src3'=>$advertisement->getSrc3(),
            'srcSm1'=>$advertisement->getSrcSm1(),
            'srcSm2'=>$advertisement->getSrcSm2(),
            'srcSm3'=>$advertisement->getSrcSm3(),
        ];

        return $this->renderForm('secure/crud_advertisements/new.html.twig', [
            'advertisement' => $advertisement,
            'form' => $form,
            'data'=>$data
        ]);
    }
}
