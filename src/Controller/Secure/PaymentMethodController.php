<?php

namespace App\Controller\Secure;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class PaymentMethodController
 * @package App\Controller\Secure
 * @Route("/payment-method")
 */
class PaymentMethodController extends AbstractController
{
    /**
     * @Route("/", name="secure_payment_method")
     */
    public function index(): Response
    {

        return $this->render('secure/payment_method/index.html.twig', [
            'controller_name' => 'PaymentMethodController',
        ]);
    }
}
