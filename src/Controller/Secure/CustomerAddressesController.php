<?php

namespace App\Controller\Secure;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/customeraddresses")
 */
class CustomerAddressesController extends AbstractController
{
    /**
     * @Route("/addresses", name="secure_customer_addresses")
     */
    public function index(): Response
    {
        return $this->render('secure/customer_addresses/index.html.twig', [
            'controller_name' => 'CustomerAddressesController',
        ]);
    }
}
