<?php

namespace App\Helpers;

use App\Repository\CustomerRepository;
use App\Constants\Constants;
use App\Repository\CommunicationStatesBetweenPlatformsRepository;
use DateTime;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Contracts\HttpClient\HttpClientInterface;
use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;

class SendCustomerToCrm
{
    private $client;
    private $customerRepository;
    private $communicationStatesBetweenPlatformsRepository;
    private $em;
    private $date;

    public function __construct(
        HttpClientInterface $client,
        CustomerRepository $customerRepository,
        CommunicationStatesBetweenPlatformsRepository $communicationStatesBetweenPlatformsRepository,
        EntityManagerInterface $em
    ) {
        $this->client = $client;
        $this->customerRepository = $customerRepository;
        $this->communicationStatesBetweenPlatformsRepository = $communicationStatesBetweenPlatformsRepository;
        $this->em = $em;
    }

    public function SendCustomerToCrm($customer)
    {
        $this->date = new DateTime;
        if ($customer) {
            $customer->incrementAttemptsToSendCustomerToCrm();
            try {
                $response = $this->client->request(
                    'POST',
                    $_ENV['CRM_API'] . '/acustomer/',
                    [
                        'headers'   => [
                            'Authorization' => $_ENV['CRM_AUTHORIZATION'],
                            'Content-Type'  => $_ENV['CRM_CONTENT_TYPE'],
                            'Cookie'        => $_ENV['CRM_COOKIE'],
                        ],
                        'json'  => $customer->getCustomerTotalInfo(),
                    ]
                );
                $body = $response->getContent(false);
                $data_response = json_decode($body, true);
                switch ($response->getStatusCode()) {
                    case Response::HTTP_CREATED:
                    case Response::HTTP_OK:

                        //leer status
                        $customer->setErrorMessageCrm('code: ' . $response->getStatusCode() . ' date: ' . $this->date->format('Y-m-d H:i:s') . ' - Message: ' . $data_response['status']);
                        $customer->setStatusSentCrm($this->communicationStatesBetweenPlatformsRepository->find(Constants::CBP_STATUS_SENT));
                        break;

                    case Response::HTTP_UNPROCESSABLE_ENTITY:
                        //Leer msg y p
                        $customer->setErrorMessageCrm('code: ' . $response->getStatusCode() . ' date: ' . $this->date->format('Y-m-d H:i:s') . ' - Message: ' . $data_response['msg'] . ' - ' . $data_response['p']);
                        $customer->setStatusSentCrm($this->communicationStatesBetweenPlatformsRepository->find(Constants::CBP_STATUS_ERROR));
                        break;

                    case Response::HTTP_UNAUTHORIZED:
                        $customer->setErrorMessageCrm('code: ' . $response->getStatusCode() . ' date: ' . $this->date->format('Y-m-d H:i:s') . ' - Message: Usuario no autorizado, verifique las credenciales');
                        $customer->setStatusSentCrm($this->communicationStatesBetweenPlatformsRepository->find(Constants::CBP_STATUS_ERROR));
                        //nada para leer, (inventar error)
                        break;
                    default:
                        //leer error
                        $customer->setErrorMessageCrm('code: ' . $response->getStatusCode() . ' date: ' . $this->date->format('Y-m-d H:i:s') . ' - Message: ' . $data_response['error']);
                        $customer->setStatusSentCrm($this->communicationStatesBetweenPlatformsRepository->find(Constants::CBP_STATUS_ERROR));
                        break;
                }
            } catch (TransportExceptionInterface $e) {
                $customer->setStatusSentCrm($this->communicationStatesBetweenPlatformsRepository->find(Constants::CBP_STATUS_ERROR));
                $customer->setErrorMessageCrm('code: ' . $response->getStatusCode() . ' date: ' . $this->date->format('Y-m-d H:i:s') . ' - Message: ' . $e->getMessage());
            }
            //grabo en base
            $this->em->persist($customer);
            $this->em->flush();
        }
    }

    public function SendCustomerPendingToCrm()
    {
        $customers = $this->customerRepository->findCustomersToSendToCrm([Constants::CBP_STATUS_PENDING, Constants::CBP_STATUS_ERROR], ['registration_date' => 'ASC'], $_ENV['MAX_LIMIT_CUSTOMER_TO_SYNC']);
        foreach ($customers as $customer) {
            $this->SendCustomerToCrm($customer);
        }
    }
}
