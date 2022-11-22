<?php

namespace App\Controller\Api;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/api")
 */
class UserApiController extends AbstractController
{
    /**
     * @Route("/user", name="app_user_api",methods={"GET"})
     */
    public function index(): Response
    {
        return $this->json(['ok']);
    }
}
