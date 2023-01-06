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
use App\Repository\CategoryRepository;

class SendCategoryTo3pl
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
    private $categoryRepository;

    public function __construct(
        HttpClientInterface $client,
        Login3pl $login3pl,
        RequestStack $requestStack,
        CommunicationStatesBetweenPlatformsRepository $communicationStatesBetweenPlatformsRepository,
        EntityManagerInterface $em,
        CategoryRepository $categoryRepository
    ) {
        $this->client = $client;
        $this->login3pl = $login3pl;
        $this->requestStack = $requestStack;
        $this->communicationStatesBetweenPlatformsRepository = $communicationStatesBetweenPlatformsRepository;
        $this->em = $em;
        $this->attempts = 0;
        $this->unauthorized = false;
        $this->categoryRepository = $categoryRepository;
    }

    public function send($category, $method = 'POST', $endpoint = 'create', $command_execute = false)
    {
        $this->date = new DateTime;
        $category->incrementAttemptsToSendCategoryTo3pl();

        if ($command_execute) {
            $response_login = $this->login3pl->Login();
        } else {
            $this->session = $this->requestStack->getSession();
            if (!$this->session->get('3pl_data')) {
                $response_login = $this->login3pl->Login();
                $this->session->set('3pl_data', $response_login['3pl_data']);
            } else {
                $response_login['status'] = true;
            }
        }

        if (!$response_login['status']) {
            $category->setStatusSent3pl($this->communicationStatesBetweenPlatformsRepository->find(Constants::CBP_STATUS_ERROR));
            $category->setErrorMessage3pl('code: ' . $response_login['code'] . ' date: ' . $this->date->format('Y-m-d H:i:s') . ' - Message: ' . $response_login['message']);
        } else {
            try {
                $response = $this->client->request(
                    $method,
                    $_ENV['ML_API'] . '/categories/' . $endpoint,
                    [
                        'headers'   => [
                            'Authorization' => 'Bearer ' . ($command_execute ? $response_login['3pl_data']['access_token'] : $this->session->get('3pl_data')['access_token']),
                            'Content-Type'  => 'application/json',
                        ],
                        'json'  => [
                            'client_id' => ($command_execute ? $response_login['3pl_data']['clientId'] : $this->session->get('3pl_data')['clientId']),
                            'category' => $category->getName(),
                            'id' => $category->getId3pl() ? $category->getId3pl() : null,
                        ],
                    ]
                );
                $body = $response->getContent(false);
                $data_response = json_decode($body, true);
                switch ($response->getStatusCode()) {
                    case Response::HTTP_CREATED:
                        $category->setId3pl($data_response['id']);
                        $category->setErrorMessage3pl('code: ' . $response->getStatusCode() . ' date: ' . $this->date->format('Y-m-d H:i:s') . ' - Message: Marca creada correctamente');
                        $category->setStatusSent3pl($this->communicationStatesBetweenPlatformsRepository->find(Constants::CBP_STATUS_SENT));
                        break;

                    case Response::HTTP_OK:
                        $category->setErrorMessage3pl('code: ' . $response->getStatusCode() . ' date: ' . $this->date->format('Y-m-d H:i:s') . ' - Message: Marca actualizada correctamente');
                        $category->setStatusSent3pl($this->communicationStatesBetweenPlatformsRepository->find(Constants::CBP_STATUS_SENT));
                        break;

                    case Response::HTTP_UNAUTHORIZED:
                        $this->unauthorized = true;
                        $this->attempts++;
                        $category->setErrorMessage3pl('code: ' . $response->getStatusCode() . ' date: ' . $this->date->format('Y-m-d H:i:s') . ' - Message: Usuario no autorizado, verifique las credenciales');
                        $category->setStatusSent3pl($this->communicationStatesBetweenPlatformsRepository->find(Constants::CBP_STATUS_ERROR));
                        //nada para leer, (inventar error)
                        break;
                    default:
                        //leer error
                        $category->setErrorMessage3pl('code: ' . $response->getStatusCode() . ' date: ' . $this->date->format('Y-m-d H:i:s') . ' - Message: Error');
                        $category->setStatusSent3pl($this->communicationStatesBetweenPlatformsRepository->find(Constants::CBP_STATUS_ERROR));
                        break;
                }
            } catch (TransportExceptionInterface $e) {
                $category->setStatusSent3pl($this->communicationStatesBetweenPlatformsRepository->find(Constants::CBP_STATUS_ERROR));
                $category->setErrorMessage3pl('code: ' . $response->getStatusCode() . ' date: ' . $this->date->format('Y-m-d H:i:s') . ' - Message: ' . $e->getMessage());
            }
        }
        //grabo en base
        $this->em->persist($category);
        $this->em->flush();
        if ($this->unauthorized && $this->attempts < 2) {
            if (!$command_execute) {
                $response_login = $this->login3pl->Login();
                $this->session->set('3pl_data', $response_login['3pl_data']);
                $this->session->save();
            }
            $this->send($category);
        }
    }

    public function sendCategoryPendings()
    {
        $categories = $this->categoryRepository->findCategoriesToSendTo3pl([Constants::CBP_STATUS_PENDING, Constants::CBP_STATUS_ERROR], ['created_at' => 'ASC'], $_ENV['MAX_LIMIT_CATEGORY_TO_SYNC']);
        foreach ($categories as $category) {
            if ($category->getId3pl()) {
                $this->send($category, 'PUT', 'update', true);
            } else {
                $this->send($category, 'POST', 'create', true);
            }
        }
    }
}
