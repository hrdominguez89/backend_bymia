<?php

namespace App\Controller\Secure;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/product-images")
 */
class ProductImagesController extends AbstractController
{
    /**
     * @Route("/", name="product_images")
     */
    public function index(): Response
    {
        return $this->render('secure/product_images/index.html.twig', [
            'controller_name' => 'ProductImagesController',
        ]);
    }
}
