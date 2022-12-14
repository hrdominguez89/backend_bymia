<?php

namespace App\Controller\Api\Front;

use App\Repository\AboutUsRepository;
use App\Repository\CoverImageRepository;
use App\Repository\FaqsRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;


/**
 * @Route("/api/front")
 */
class FrontApiController extends AbstractController
{
    /**
     * @Route("/sliders", name="api_sliders",methods={"GET"})
     */
    public function sliders(CoverImageRepository $coverImageRepository): Response
    {

        $sliders = $coverImageRepository->findCoverImage();

        return $this->json(
            $sliders,
            Response::HTTP_OK,
            ['Content-Type' => 'application/json']
        );
    }

    /**
     * @Route("/about-us", name="api_sliders",methods={"GET"})
     */
    public function aboutUs(AboutUsRepository $aboutUsRepository): Response
    {

        $aboutUs = $aboutUsRepository->findAboutUsDescription();
        return $this->json(
            $aboutUs,
            Response::HTTP_OK,
            ['Content-Type' => 'application/json']
        );
    }

    /**
     * @Route("/faqs", name="api_sliders",methods={"GET"})
     */
    public function faqs(FaqsRepository $faqsRepository): Response
    {

        $faqs = $faqsRepository->findAboutUsDescription();
        return $this->json(
            $faqs,
            Response::HTTP_OK,
            ['Content-Type' => 'application/json']
        );
    }
}
