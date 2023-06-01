<?php

namespace App\Helpers;

use App\Repository\OrdersRepository;
use App\Constants\Constants;
use App\Repository\CommunicationStatesBetweenPlatformsRepository;
use DateTime;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Contracts\HttpClient\HttpClientInterface;
use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;

class SendOrderToCrm
{
    private $client;
    private $ordersRepository;
    private $communicationStatesBetweenPlatformsRepository;
    private $em;
    private $date;

    public function __construct(
        HttpClientInterface $client,
        OrdersRepository $ordersRepository,
        CommunicationStatesBetweenPlatformsRepository $communicationStatesBetweenPlatformsRepository,
        EntityManagerInterface $em
    ) {
        $this->client = $client;
        $this->ordersRepository = $ordersRepository;
        $this->communicationStatesBetweenPlatformsRepository = $communicationStatesBetweenPlatformsRepository;
        $this->em = $em;
    }

    public function SendOrderToCrm($order)
    {
        $this->date = new DateTime;
        if ($order) {
            $order->incrementAttemptsToSendOrderToCrm();
            $communication_status = [
                'status' => false,
                'order_json' => $order->generateOrderToCRM(),
                'status_code' => '',
                'message' => ''
            ];

            try {
                $response_crm = $this->client->request(
                    'POST',
                    $_ENV['CRM_API'] . '/aorder/',
                    [
                        'headers'   => [
                            'Authorization' => $_ENV['CRM_AUTHORIZATION'],
                            'Content-Type'  => $_ENV['CRM_CONTENT_TYPE'],
                            'Cookie'        => $_ENV['CRM_COOKIE'],
                        ],
                        'json'  => $order->generateOrderToCRM(),
                    ]
                );
                $body_crm = $response_crm->getContent(false);
                $data_response_crm = json_decode($body_crm, true);

                switch ($response_crm->getStatusCode()) {
                    case Response::HTTP_CREATED:
                    case Response::HTTP_OK:

                        //leer status
                        $order->setErrorMessageCrm('code: ' . $response_crm->getStatusCode() . ' date: ' . $this->date->format('Y-m-d H:i:s') . ' - Message: ' . @$data_response_crm['status']);
                        $order->setStatusSentCrm($this->communicationStatesBetweenPlatformsRepository->find(Constants::CBP_STATUS_SENT));
                        $communication_status['status'] = true;
                        break;

                    case Response::HTTP_UNPROCESSABLE_ENTITY:
                        //Leer msg y p
                        $order->setErrorMessageCrm('code: ' . $response_crm->getStatusCode() . ' date: ' . $this->date->format('Y-m-d H:i:s') . ' - Message: ' . @$data_response_crm['msg'] . ' - ' . @$data_response_crm['p']);
                        $order->setStatusSentCrm($this->communicationStatesBetweenPlatformsRepository->find(Constants::CBP_STATUS_ERROR));
                        break;

                    case Response::HTTP_UNAUTHORIZED:
                        $order->setErrorMessageCrm('code: ' . $response_crm->getStatusCode() . ' date: ' . $this->date->format('Y-m-d H:i:s') . ' - Message: Usuario no autorizado, verifique las credenciales');
                        $order->setStatusSentCrm($this->communicationStatesBetweenPlatformsRepository->find(Constants::CBP_STATUS_ERROR));
                        //nada para leer, (inventar error)
                        break;
                    default:
                        //leer error
                        $order->setErrorMessageCrm('code: ' . $response_crm->getStatusCode() . ' date: ' . $this->date->format('Y-m-d H:i:s') . ' - Message: ' . @$data_response_crm['error']);
                        $order->setStatusSentCrm($this->communicationStatesBetweenPlatformsRepository->find(Constants::CBP_STATUS_ERROR));
                        break;
                }
                $communication_status['message'] = $order->getErrorMessageCrm();
                $communication_status['status_code'] = $response_crm;
            } catch (TransportExceptionInterface $e) {
                $order->setStatusSentCrm($this->communicationStatesBetweenPlatformsRepository->find(Constants::CBP_STATUS_ERROR));
                $order->setErrorMessageCrm('code: ' . $response_crm->getStatusCode() . ' date: ' . $this->date->format('Y-m-d H:i:s') . ' - Message: ' . $e->getMessage());
                $communication_status['status_code'] = $response_crm->getStatusCode();
                $communication_status['message'] = $e->getMessage();
            }

            //grabo en base
            $this->em->persist($order);
            $this->em->flush();
            return $communication_status;
        }
    }

    public function SendOrderPendingToCrm()
    {
        $orders = $this->ordersRepository->findOrdersToSendToCrm([Constants::CBP_STATUS_PENDING, Constants::CBP_STATUS_ERROR], ['registration_date' => 'ASC'], $_ENV['MAX_LIMIT_ORDER_TO_SYNC']);
        foreach ($orders as $order) {
            $this->SendOrderToCrm($order);
        }
    }
}
