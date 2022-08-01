<?php

namespace App\Controller\Secure;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/currency")
 */
class CurrencyController extends AbstractController
{
    /**
     * @Route("/", name="currency")
     */
    public function index(): Response
    {
        return $this->render('secure/currency/index.html.twig', [
            'controller_name' => 'CurrencyController',
        ]);
    }
}
