<?php
namespace App\Service;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;

class SendMailServiceHome
{
    private $mailer;

    public function __construct(MailerInterface $mailer)
    {
        $this->mailer = $mailer;
    }

    public function send(
        $from ,
        $to,
        $message,
        $name
      
      
    ) : void
    {
       
        // On crée le mail
        $email = (new Email())
          
            ->from($from)
            ->to($to)
            ->subject("Plus d'informations ou des problèmes")
            ->html($message."<br><br><span style='font-weight:bold'>Ce message est envoyé par l'utilisateur de site Investir <br>Nom utilisateur. : ".$name." <br>Email utilisateur: ".$from."  </span>");
          
          
        // On envoie le mail
        $this->mailer->send($email);
    }
}