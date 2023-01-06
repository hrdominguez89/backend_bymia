<?php

namespace App\Helpers;

use Symfony\Contracts\HttpClient\HttpClientInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;

class Login3pl
{
    private $client;

    public function __construct(
        HttpClientInterface $client
    ) {
        $this->client = $client;
    }

    public function Login()
    {
        try {
            $response = $this->client->request(
                'POST',
                $_ENV['ML_API'] . '/login',
                [
                    'headers'   => [
                        'Content-Type'  => 'application/x-www-form-urlencoded',
                    ],
                    'body'      => [
                        'email' => $_ENV['ML_USER'],
                        'password' => $_ENV['ML_PASSWORD']
                    ]
                ]
            );
            $body = $response->getContent(false);
            $data_response = json_decode($body, true);
            switch ($response->getStatusCode()) {
                case Response::HTTP_OK:
                    return [
                        'status' => true,
                        '3pl_data' => $data_response,
                    ];
                    break;

                case Response::HTTP_UNAUTHORIZED:
                    return [
                        'status' => false,
                        'code' => $response->getStatusCode(),
                        'message' => 'Usuario no autorizado'
                    ];
                    //nada para leer, (inventar error)
                    break;
                default:
                    return [
                        'status' => false,
                        'code' => $response->getStatusCode(),
                        'message' => 'Error'
                    ];
                    break;
            }
            return false;
        } catch (TransportExceptionInterface $e) {
            return [
                'status' => false,
                'code' => $response->getStatusCode(),
                'message' => $e->getMessage()
            ];
        }
    }
}
