<?php

namespace App\Helpers;

use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Address;
use Symfony\Component\Mime\Email;
use Symfony\Component\Mailer\Exception\TransportExceptionInterface;

class SendMail
{
    private MailerInterface $mailer;

    public function __construct(MailerInterface $mailer)
    {
        $this->mailer = $mailer;
    }

    public function __invoke(
        $mailTo,
        $mailToAlias,
        $subject,
        $msg
    ) {
        $body = "
            <table cellspacing='0' cellpadding='0' border='0' style='color:#333;background:#fff;padding:0;margin:0;width:100%;font:15px/1.25em 'Helvetica Neue',Arial,Helvetica'>
                <tbody>
                    <tr width='100%'> 
                        <td valign='top' align='left' style='background:#eef0f1;font:15px/1.25em 'Helvetica Neue',Arial,Helvetica'> 
                            <table style='border:none;padding:0 18px;margin:50px auto;width:500px'>
                                <tbody>
                                    <tr width='100%' height='60'> 
                                    </tr> 
                                    <tr width='100%'>
                                        <td valign='top' align='left' style='background:#fff;padding:18px'>
                                            <img height='auto' width='auto' src='https://miacargo.do/assets/images/bymia_png.png' title='Trello' style='font-weight:bold;font-size:18px;color:#fff;vertical-align:top' class='CToWUd'>
                                            <h1 style='text-align:center !important; font-size:20px;margin:16px 0;color:#333;'> Hola $mailToAlias, <br> </h1>
                                            <p style='text-align:center !important;'> $msg</p>
                                                <p style='text-align:center; font:14px/1.25em 'Helvetica Neue',Arial,Helvetica;color:#333'> 
                                                </p>
                                        </td>
                                    </tr>
                                </tbody>
                            </table> 
                        </td> 
                    </tr>
                </tbody> 
            </table>";

        $email = (new Email())
            ->from(new Address('noreply@bymia.do', 'MIACARGO'))
            ->to($mailTo)
            ->subject($subject)
            ->html($body);

        try {
            $this->mailer->send($email);
        } catch (TransportExceptionInterface $e) {
            // some error prevented the email sending; display an
            // error message or try to resend the message
            echo "Message could not be sent. Mailer Error: {$e->getDebug()}";
        }
    }
}
