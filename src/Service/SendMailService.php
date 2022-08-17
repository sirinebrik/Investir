<?php
namespace App\Service;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;

class SendMailService
{
    private $mailer;

    public function __construct(MailerInterface $mailer)
    {
        $this->mailer = $mailer;
    }

    public function send(
        $from ,
        $to
      
      
    ) : void
    {
       
        // On crée le mail
        $email = (new Email())
          
            ->from($from->getEmail())
            ->to($to->getEmail())
            ->subject('Activation compte')
            ->html("Bonjour <span style='font-weight:bold'>".$to->getUsername()."</span>,<br>Bienvenue chez  <span style='font-weight:bold'> Investir </span>!<br> Votre compte est activé , vous pouvez maintenant <a href='http://127.0.0.1:8000/login' > se connecter</a> 
            à l'aide de votre adresse e-mail et de votre mot de passe. <br><br>Cordialement.<br><span style='font-weight:bold'>Investir </span>");
          
          
        // On envoie le mail
        $this->mailer->send($email);
    }
}