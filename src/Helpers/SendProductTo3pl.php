<?php

namespace App\Helpers;

use App\Constants\Constants;
use DateTime;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Contracts\HttpClient\HttpClientInterface;
use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;
use App\Repository\CommunicationStatesBetweenPlatformsRepository;
use App\Repository\ProductRepository;

class SendProductTo3pl
{
    private $client;
    private $login3pl;
    private $session;
    private $communicationStatesBetweenPlatformsRepository;
    private $date;
    private $em;
    private $attempts;
    private $unauthorized;
    private $requestStack;
    private $productRepository;

    public function __construct(
        HttpClientInterface $client,
        Login3pl $login3pl,
        RequestStack $requestStack,
        CommunicationStatesBetweenPlatformsRepository $communicationStatesBetweenPlatformsRepository,
        EntityManagerInterface $em,
        ProductRepository $productRepository
    ) {
        $this->client = $client;
        $this->login3pl = $login3pl;
        $this->requestStack = $requestStack;
        $this->communicationStatesBetweenPlatformsRepository = $communicationStatesBetweenPlatformsRepository;
        $this->em = $em;
        $this->attempts = 0;
        $this->unauthorized = false;
        $this->productRepository = $productRepository;
    }

    public function send($product, $method = 'POST', $endpoint = 'create', $command_execute = false)
    {
        $this->date = new DateTime;
        $product->incrementAttemptsToSendProductTo3pl();

        if ($command_execute) {
            $response_login = $this->login3pl->Login();
        } else {
            //si no es ejecucion x comando cargo la sesion
            $this->session = $this->requestStack->getSession();

            if ($this->session->get('3pl_data')) { //si existe la sesion la guardo con su status true.
                $response_login = [
                    'status' => true,
                    '3pl_data' => $this->session->get('3pl_data')
                ];
            } else { //si no existe logueo y guardo la info en la variable, y si existe 3pl_data lo guardo en una variable de sesion
                $response_login = $this->login3pl->Login();
                if (isset($response_login['3pl_data'])) {
                    $this->session->set('3pl_data', $response_login['3pl_data']);
                    $this->session->save();
                }
            }
        }

        if ($response_login['status']) {
            try {
                $response = $this->client->request(
                    $method,
                    $_ENV['ML_API'] . '/products/' . $endpoint,
                    [
                        'headers'   => [
                            'Authorization' => 'Bearer ' . $response_login['3pl_data']['access_token'],
                            'Content-Type'  => 'application/json',
                        ],
                        'json'  => $product->getProductTo3pl($method == 'PUT' ? true : false),
                    ]
                );

                $body = $response->getContent(false);
                $data_response = json_decode($body, true);

                switch ($response->getStatusCode()) {
                    case Response::HTTP_CREATED:
                        $product->setId3pl($data_response['id']);
                        $product->setErrorMessage3pl('code: ' . $response->getStatusCode() . ' date: ' . $this->date->format('Y-m-d H:i:s') . ' - Message: Producto creado correctamente');
                        $product->setStatusSent3pl($this->communicationStatesBetweenPlatformsRepository->find(Constants::CBP_STATUS_SENT));
                        break;

                    case Response::HTTP_OK:
                        if (@$data_response['error']) {
                            $error3pl = '';
                            foreach ($data_response['errors'] as $error) {
                                $error3pl = $error3pl . ' / ' . $error;
                            }
                            $product->setStatusSent3pl($this->communicationStatesBetweenPlatformsRepository->find(Constants::CBP_STATUS_ERROR));
                            $product->setErrorMessage3pl('code: ' . $response->getStatusCode() . ' date: ' . $this->date->format('Y-m-d H:i:s') . ' - Message: ' . $error3pl);
                        } else {
                            $product->setErrorMessage3pl('code: ' . $response->getStatusCode() . ' date: ' . $this->date->format('Y-m-d H:i:s') . ' - Message: Producto actualizado correctamente');
                            $product->setStatusSent3pl($this->communicationStatesBetweenPlatformsRepository->find(Constants::CBP_STATUS_SENT));
                        }
                        break;

                    case Response::HTTP_UNAUTHORIZED:
                        $this->unauthorized = true;
                        $this->attempts++;
                        $product->setErrorMessage3pl('code: ' . $response->getStatusCode() . ' date: ' . $this->date->format('Y-m-d H:i:s') . ' - Message: Usuario no autorizado, verifique las credenciales');
                        $product->setStatusSent3pl($this->communicationStatesBetweenPlatformsRepository->find(Constants::CBP_STATUS_ERROR));
                        //nada para leer, (inventar error)
                        break;
                    default:
                        //leer error
                        $this->attempts++;
                        $product->setStatusSent3pl($this->communicationStatesBetweenPlatformsRepository->find(Constants::CBP_STATUS_ERROR));
                        if (@$data_response['error']) {
                            $error3pl = '';
                            foreach ($data_response['errors'] as $error) {
                                $error3pl = $error3pl . ' / ' . $error;
                            }
                            $product->setErrorMessage3pl('code: ' . $response->getStatusCode() . ' date: ' . $this->date->format('Y-m-d H:i:s') . ' - Message: ' . $error3pl);
                        } else {
                            $product->setErrorMessage3pl('code: ' . $response->getStatusCode() . ' date: ' . $this->date->format('Y-m-d H:i:s') . ' - Message: Error');
                        }
                        break;
                }
            } catch (TransportExceptionInterface $e) {
                $this->attempts++;
                $product->setStatusSent3pl($this->communicationStatesBetweenPlatformsRepository->find(Constants::CBP_STATUS_ERROR));
                $product->setErrorMessage3pl('code: ' . $response->getStatusCode() . ' date: ' . $this->date->format('Y-m-d H:i:s') . ' - Message: ' . $e->getMessage());
            }
        } else {
            $product->setStatusSent3pl($this->communicationStatesBetweenPlatformsRepository->find(Constants::CBP_STATUS_ERROR));
            $product->setErrorMessage3pl('code: ' . $response_login['code'] . ' date: ' . $this->date->format('Y-m-d H:i:s') . ' - Message: ' . $response_login['message']);
        }

        //grabo en base
        $this->em->persist($product);
        $this->em->flush();

        if (!$command_execute && $this->unauthorized && $this->attempts < 2) {
            $response_login = $this->login3pl->Login();
            if (isset($response_login['3pl_data'])) {
                $this->session->set('3pl_data', $response_login['3pl_data']);
                $this->session->save();
                $this->send($product);
            }
        }
    }

    public function sendProductsPendings()
    {
        $products = $this->productRepository->findProductsToSendTo3pl([Constants::CBP_STATUS_PENDING, Constants::CBP_STATUS_ERROR], ['created_at' => 'ASC'], $_ENV['MAX_LIMIT_PRODUCT_TO_SYNC']);
        foreach ($products as $product) {
            if ($product->getId3pl()) {
                $this->send($product, 'PUT', 'update', true);
            } else {
                $this->send($product, 'POST', 'create', true);
            }
        }
    }
}
