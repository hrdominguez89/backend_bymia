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
use App\Repository\SubcategoryRepository;

class SendSubcategoryTo3pl
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
    private $subcategoryRepository;

    public function __construct(
        HttpClientInterface $client,
        Login3pl $login3pl,
        RequestStack $requestStack,
        CommunicationStatesBetweenPlatformsRepository $communicationStatesBetweenPlatformsRepository,
        EntityManagerInterface $em,
        SubcategoryRepository $subcategoryRepository
    ) {
        $this->client = $client;
        $this->login3pl = $login3pl;
        $this->requestStack = $requestStack;
        $this->communicationStatesBetweenPlatformsRepository = $communicationStatesBetweenPlatformsRepository;
        $this->em = $em;
        $this->attempts = 0;
        $this->unauthorized = false;
        $this->subcategoryRepository = $subcategoryRepository;
    }

    public function send($subcategory, $method = 'POST', $endpoint = 'create', $command_execute = false)
    {
        $this->date = new DateTime;
        $subcategory->incrementAttemptsToSendSubcategoryTo3pl();

        if ($command_execute) {
            $response_login = $this->login3pl->Login();
        } else {
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
                    $_ENV['ML_API'] . '/subCategories/' . $endpoint,
                    [
                        'headers'   => [
                            'Authorization' => 'Bearer ' . $response_login['3pl_data']['access_token'],
                            'Content-Type'  => 'application/json',
                        ],
                        'json'  => [
                            'category_id' => $subcategory->getCategory()->getId3pl(), //id3pl de categoria
                            'category_sub' => $subcategory->getName(),
                            'id' => $subcategory->getId3pl() ? $subcategory->getId3pl() : null, //id3pl de subcategoria
                        ],
                    ]
                );
                $body = $response->getContent(false);
                $data_response = json_decode($body, true);
                switch ($response->getStatusCode()) {
                    case Response::HTTP_CREATED:
                        $subcategory->setId3pl($data_response['id']);
                        $subcategory->setErrorMessage3pl('code: ' . $response->getStatusCode() . ' date: ' . $this->date->format('Y-m-d H:i:s') . ' - Message: Subcategoria creada correctamente');
                        $subcategory->setStatusSent3pl($this->communicationStatesBetweenPlatformsRepository->find(Constants::CBP_STATUS_SENT));
                        break;

                    case Response::HTTP_OK:
                        $subcategory->setErrorMessage3pl('code: ' . $response->getStatusCode() . ' date: ' . $this->date->format('Y-m-d H:i:s') . ' - Message: Subcategoria actualizada correctamente');
                        $subcategory->setStatusSent3pl($this->communicationStatesBetweenPlatformsRepository->find(Constants::CBP_STATUS_SENT));
                        break;

                    case Response::HTTP_UNAUTHORIZED:
                        $this->unauthorized = true;
                        $this->attempts++;
                        $subcategory->setErrorMessage3pl('code: ' . $response->getStatusCode() . ' date: ' . $this->date->format('Y-m-d H:i:s') . ' - Message: Usuario no autorizado, verifique las credenciales');
                        $subcategory->setStatusSent3pl($this->communicationStatesBetweenPlatformsRepository->find(Constants::CBP_STATUS_ERROR));
                        //nada para leer, (inventar error)
                        break;
                    default:
                        //leer error
                        $this->attempts++;
                        $subcategory->setErrorMessage3pl('code: ' . $response->getStatusCode() . ' date: ' . $this->date->format('Y-m-d H:i:s') . ' - Message: Error');
                        $subcategory->setStatusSent3pl($this->communicationStatesBetweenPlatformsRepository->find(Constants::CBP_STATUS_ERROR));
                        break;
                }
            } catch (TransportExceptionInterface $e) {
                $this->attempts++;
                $subcategory->setStatusSent3pl($this->communicationStatesBetweenPlatformsRepository->find(Constants::CBP_STATUS_ERROR));
                $subcategory->setErrorMessage3pl('code: ' . $response->getStatusCode() . ' date: ' . $this->date->format('Y-m-d H:i:s') . ' - Message: ' . $e->getMessage());
            }
        } else {
            $subcategory->setStatusSent3pl($this->communicationStatesBetweenPlatformsRepository->find(Constants::CBP_STATUS_ERROR));
            $subcategory->setErrorMessage3pl('code: ' . $response_login['code'] . ' date: ' . $this->date->format('Y-m-d H:i:s') . ' - Message: ' . $response_login['message']);
        }
        //grabo en base
        $this->em->persist($subcategory);
        $this->em->flush();
        if (!$command_execute && $this->unauthorized && $this->attempts < 2) {
            $response_login = $this->login3pl->Login();
            if (isset($response_login['3pl_data'])) {
                $this->session->set('3pl_data', $response_login['3pl_data']);
                $this->session->save();
                $this->send($subcategory);
            }
        }
    }

    public function sendSubcategoryPendings()
    {
        $subcategories = $this->subcategoryRepository->findSubcategoriesToSendTo3pl([Constants::CBP_STATUS_PENDING, Constants::CBP_STATUS_ERROR], ['created_at' => 'ASC'], $_ENV['MAX_LIMIT_SUBCATEGORY_TO_SYNC']);
        foreach ($subcategories as $subcategory) {
            if ($subcategory->getId3pl()) {
                $this->send($subcategory, 'PUT', 'update', true);
            } else {
                $this->send($subcategory, 'POST', 'create', true);
            }
        }
    }
}
