<?php

namespace App\Helpers;

use App\Entity\EmailQueue;
use App\Repository\EmailStatusTypeRepository;
use App\Repository\EmailTypesRepository;
use App\Constants\Constants;
use App\Repository\EmailQueueRepository;
use DateTime;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\Mailer\Exception\TransportExceptionInterface;

class EnqueueEmail
{
    private $mailer;
    private $emailQueueRepository;
    private $emailTypesRepository;
    private $emailStatusTypeRepository;
    private $em;
    private $email;

    public function __construct(
        MailerInterface $mailer,
        EmailQueueRepository $emailQueueRepository,
        EmailTypesRepository $emailTypesRepository,
        EmailStatusTypeRepository $emailStatusTypeRepository,
        EntityManagerInterface $em
    ) {
        $this->mailer = $mailer;
        $this->emailQueueRepository = $emailQueueRepository;
        $this->emailTypesRepository = $emailTypesRepository;
        $this->emailStatusTypeRepository = $emailStatusTypeRepository;
        $this->em = $em;
    }

    public function enqueue(int $email_type, string $email_to, array $parameters): int
    {
        //ENCOLO LOS CORREOS EN LA BASE DE DATOS.
        $email_queue = new EmailQueue;
        $email_queue->setEmailType($this->emailTypesRepository->find($email_type))
            ->setEmailTo($email_to)
            ->setParameters($parameters)
            ->setEmailStatus($this->emailStatusTypeRepository->find(Constants::EMAIL_STATUS_PENDING));

        $this->em->persist($email_queue);
        $this->em->flush();
        return $email_queue->getId();
    }

    public function sendEnqueue($id = null)
    {
        //busca los correos o el correo en la cola de la base de datos y llama al metodo send para enviar 1 a 1 los correos
        if ($id) {
            $this->email = $this->emailQueueRepository->find($id);
            $this->send();
        } else {
            $emails = $this->emailQueueRepository->findEmailsByStatus([Constants::EMAIL_STATUS_PENDING, Constants::EMAIL_STATUS_ERROR], ['created_at' => 'ASC'], $_ENV['MAX_LIMIT_EMAIL_TO_SEND']);
            foreach ($emails as $this->email) {
                $this->send();
            }
        }
    }

    protected function send()
    {
        //envia 1 a 1 los correos, y actualiza su estado en la base
        $email_to_send = (new TemplatedEmail())
            ->from($_ENV['EMAIL_FROM'])
            ->to($this->email->getEmailTo())
            ->subject($this->email->getEmailType()->getName())
            ->htmlTemplate('email/' . $this->email->getEmailType()->getTemplateName() . '.html.twig')
            ->context([
                'parameters' => $this->email->getParameters(),
            ]);
        try {
            $this->mailer->send($email_to_send);
            $this->email->setSentOn(new DateTime());
            $this->email->setEmailStatus($this->emailStatusTypeRepository->find(Constants::EMAIL_STATUS_SENT));
        } catch (TransportExceptionInterface $e) {
            $this->email->setErrorMessage($e->getMessage());
            if ($this->email->getSendAttempts() + 1 >= $_ENV['MAX_ATTEMPTS_SEND_EMAIL']) {
                $this->email->setEmailStatus($this->emailStatusTypeRepository->find(Constants::EMAIL_STATUS_CANCELED));
            } else {
                $this->email->setEmailStatus($this->emailStatusTypeRepository->find(Constants::EMAIL_STATUS_ERROR));
            }
        }
        $this->email->incrementAttempts();
        $this->email->setUpdatedAt(new DateTime());
        $this->em->persist($this->email);
        $this->em->flush();
    }
}
