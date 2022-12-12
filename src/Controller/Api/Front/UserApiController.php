<?php

namespace App\Controller\Api\Front;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;


/**
 * @Route("/api/front")
 */
class UserApiController extends AbstractController
{
    /**
     * @Route("/user", name="app_user_api",methods={"GET|POST"})
     */
    public function index(Request $request): Response
    {
        $parametros = json_decode($request->getContent(),true);
        $parametros = $request->headers;
        return $this->json([dd($parametros->get('authorization'))]);
    }
}
