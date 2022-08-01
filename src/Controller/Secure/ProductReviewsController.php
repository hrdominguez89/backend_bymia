<?php

namespace App\Controller\Secure;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

 /**
 * @Route("/product-reviews")
 */
class ProductReviewsController extends AbstractController
{
    /**
     * @Route("/", name="product_reviews")
     */
    public function index(): Response
    {
        return $this->render('secure/product_reviews/index.html.twig', [
            'controller_name' => 'ProductReviewsController',
        ]);
    }
}
