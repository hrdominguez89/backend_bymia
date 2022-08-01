<?php

namespace App\Controller\Secure;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/currency-change", name="currency_change")
 */
class CurrencyChangeController extends AbstractController
{
    /**
     * @Route("/", name="currency_change")
     */
    public function index(): Response
    {
        return $this->render('secure/currency_change/index.html.twig', [
            'controller_name' => 'CurrencyChangeController',
        ]);
    }
}
