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
use App\Repository\BrandRepository;

class SendBrandTo3pl
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
    private $brandRepository;

    public function __construct(
        HttpClientInterface $client,
        Login3pl $login3pl,
        RequestStack $requestStack,
        CommunicationStatesBetweenPlatformsRepository $communicationStatesBetweenPlatformsRepository,
        EntityManagerInterface $em,
        BrandRepository $brandRepository
    ) {
        $this->client = $client;
        $this->login3pl = $login3pl;
        $this->requestStack = $requestStack;
        $this->communicationStatesBetweenPlatformsRepository = $communicationStatesBetweenPlatformsRepository;
        $this->em = $em;
        $this->attempts = 0;
        $this->unauthorized = false;
        $this->brandRepository = $brandRepository;
    }

    public function send($brand, $method = 'POST', $endpoint = 'create', $command_execute = false)
    {
        $this->date = new DateTime;
        $brand->incrementAttemptsToSendBrandTo3pl();

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
                    $_ENV['ML_API'] . '/brands/' . $endpoint,
                    [
                        'headers'   => [
                            'Authorization' => 'Bearer ' . $response_login['3pl_data']['access_token'],
                            'Content-Type'  => 'application/json',
                        ],
                        'json'  => [
                            'brand' => $brand->getName(),
                            'id' => $brand->getId3pl() ? $brand->getId3pl() : null,
                        ],
                    ]
                );

                $body = $response->getContent(false);
                $data_response = json_decode($body, true);

                switch ($response->getStatusCode()) {
                    case Response::HTTP_CREATED:
                        $brand->setId3pl($data_response['id']);
                        $brand->setErrorMessage3pl('code: ' . $response->getStatusCode() . ' date: ' . $this->date->format('Y-m-d H:i:s') . ' - Message: Marca creada correctamente');
                        $brand->setStatusSent3pl($this->communicationStatesBetweenPlatformsRepository->find(Constants::CBP_STATUS_SENT));
                        break;

                    case Response::HTTP_OK:
                        $brand->setErrorMessage3pl('code: ' . $response->getStatusCode() . ' date: ' . $this->date->format('Y-m-d H:i:s') . ' - Message: Marca actualizada correctamente');
                        $brand->setStatusSent3pl($this->communicationStatesBetweenPlatformsRepository->find(Constants::CBP_STATUS_SENT));
                        break;

                    case Response::HTTP_UNAUTHORIZED:
                        $this->unauthorized = true;
                        $this->attempts++;
                        $brand->setErrorMessage3pl('code: ' . $response->getStatusCode() . ' date: ' . $this->date->format('Y-m-d H:i:s') . ' - Message: Usuario no autorizado, verifique las credenciales');
                        $brand->setStatusSent3pl($this->communicationStatesBetweenPlatformsRepository->find(Constants::CBP_STATUS_ERROR));
                        //nada para leer, (inventar error)
                        break;
                    default:
                        //leer error
                        $this->attempts++;
                        $brand->setErrorMessage3pl('code: ' . $response->getStatusCode() . ' date: ' . $this->date->format('Y-m-d H:i:s') . ' - Message: Error');
                        $brand->setStatusSent3pl($this->communicationStatesBetweenPlatformsRepository->find(Constants::CBP_STATUS_ERROR));
                        break;
                }
            } catch (TransportExceptionInterface $e) {
                $this->attempts++;
                $brand->setStatusSent3pl($this->communicationStatesBetweenPlatformsRepository->find(Constants::CBP_STATUS_ERROR));
                $brand->setErrorMessage3pl('code: ' . $response->getStatusCode() . ' date: ' . $this->date->format('Y-m-d H:i:s') . ' - Message: ' . $e->getMessage());
            }
        } else {
            $brand->setStatusSent3pl($this->communicationStatesBetweenPlatformsRepository->find(Constants::CBP_STATUS_ERROR));
            $brand->setErrorMessage3pl('code: ' . $response_login['code'] . ' date: ' . $this->date->format('Y-m-d H:i:s') . ' - Message: ' . $response_login['message']);
        }

        //grabo en base
        $this->em->persist($brand);
        $this->em->flush();
        
        if (!$command_execute && $this->unauthorized && $this->attempts < 2) {
            $response_login = $this->login3pl->Login();
            if (isset($response_login['3pl_data'])) {
                $this->session->set('3pl_data', $response_login['3pl_data']);
                $this->session->save();
                $this->send($brand);
            }
        }
    }

    public function sendBrandPendings()
    {
        $brands = $this->brandRepository->findBrandsToSendTo3pl([Constants::CBP_STATUS_PENDING, Constants::CBP_STATUS_ERROR], ['created_at' => 'ASC'], $_ENV['MAX_LIMIT_BRAND_TO_SYNC']);
        foreach ($brands as $brand) {
            if ($brand->getId3pl()) {
                $this->send($brand, 'PUT', 'update', true);
            } else {
                $this->send($brand, 'POST', 'create', true);
            }
        }
    }
}
