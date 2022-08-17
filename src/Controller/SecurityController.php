<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

/**
 * Class SecurityController
 * @package App\Controller
 * @author Yunior Pantoja Guerrero <ypguerrero123@gmail.com>
 *
 * @Route("/security")
 */
class SecurityController extends AbstractController
{
    /**
     * @param AuthenticationUtils $helper
     * @return Response
     *
     * @Route("/login", name="app_security_login", methods="GET|POST")
     */
    public function login(AuthenticationUtils $helper): Response
    {
        if($this->getUser()){
            return $this->redirectToRoute('app_homepage'); 
        }
        return $this->render(
            'security/login.html.twig',
            [
                // last username entered by the user (if any)
                'last_username' => $helper->getLastUsername(),
                // last authentication error (if any)
                'error' => $helper->getLastAuthenticationError(),
                'active' => 'security',
            ]
        );
    }

    /**
     * This controller will not be executed,
     * as the route is handled by the Security system
     *
     * @throws \Exception
     *
     * @Route("/logout", name="app_security_logout")
     */
    public function logout()
    {
        throw new \Exception('This should never be reached!');
    }
}
