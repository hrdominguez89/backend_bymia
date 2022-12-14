<?php

namespace App\Security;

use App\Entity\ApiClients;
use App\Repository\ApiClientsRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Core\Exception\UserNotFoundException;
use Symfony\Component\Security\Http\Authenticator\AbstractAuthenticator;
use Symfony\Component\Security\Http\Authenticator\Passport\Badge\UserBadge;
use Symfony\Component\Security\Http\Authenticator\Passport\Credentials\CustomCredentials;
use Symfony\Component\Security\Http\Authenticator\Passport\Credentials\PasswordCredentials;
use Symfony\Component\Security\Http\Authenticator\Passport\Passport;
use Symfony\Component\Security\Http\Authenticator\Passport\PassportInterface;

class ApiAuthenticator extends AbstractAuthenticator
{
    private ApiClientsRepository $apiClientsRepository;

    public function __construct(ApiClientsRepository $apiClientsRepository)
    {
        $this->apiClientsRepository = $apiClientsRepository;
    }

    public function supports(Request $request): ?bool
    {
        return $request->headers->has('Authorization') && (0 === strpos($request->headers->get('Authorization'), 'Bearer ') || 0 === strpos($request->headers->get('Authorization'), 'Basic '));
    }

    public function authenticate(Request $request): PassportInterface
    {
        $token = explode(' ', $request->headers->get('Authorization'))[1];
        if (0 === strpos($request->headers->get('Authorization'), 'Basic ')) {
            [$apiClient, $password] = explode(':', base64_decode($token));
        } else {
            // strpos($request->headers->get('Authorization'), 'Basic ')
        }
        return new Passport(
            new UserBadge($apiClient, function ($apiClientIdentifier) {
                $userApiClient = $this->apiClientsRepository->findOneBy(['api_client_id' => $apiClientIdentifier]);

                if (!$userApiClient) {
                    throw new UserNotFoundException();
                }
                return $userApiClient;
            }),
            new PasswordCredentials($password)
        );
        // TODO: Implement authenticate() method.
    }

    public function onAuthenticationSuccess(Request $request, TokenInterface $token, string $firewallName): ?Response
    {
        // TODO: Implement onAuthenticationSuccess() method.
        return null;
    }

    public function onAuthenticationFailure(Request $request, AuthenticationException $exception): ?Response
    {
        $response = new Response();
        $response->setStatusCode(Response::HTTP_FORBIDDEN);
        $response->setContent(
            json_encode([
                'message'=>'Usuario y/o Password incorrecto.'
            ])
        );
        $response->headers->set('Content-Type', 'application/json');
        return $response;
        // TODO: Implement onAuthenticationFailure() method.
    }

    //    public function start(Request $request, AuthenticationException $authException = null): Response
    //    {
    //        /*
    //         * If you would like this class to control what happens when an anonymous user accesses a
    //         * protected page (e.g. redirect to /login), uncomment this method and make this class
    //         * implement Symfony\Component\Security\Http\EntryPoint\AuthenticationEntryPointInterface.
    //         *
    //         * For more details, see https://symfony.com/doc/current/security/experimental_authenticators.html#configuring-the-authentication-entry-point
    //         */
    //    }
}
