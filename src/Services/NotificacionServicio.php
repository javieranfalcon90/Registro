<?php

namespace App\Services;

use Symfony\Component\HttpFoundation\Request;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;

class NotificacionServicio
{

    protected $em;
    protected $mailer;

    public function __construct(EntityManagerInterface $entityManager, MailerInterface $mailer)
    {
        $this->em = $entityManager;
        $this->mailer = $mailer;
    }


    public function sendEmail($destinatario): Response
    {


        dd($destinatario);
        $email = (new Email())
            ->from('hello@example.com')
            ->to($destinatario->getEmail())
            //->cc('cc@example.com')
            //->bcc('bcc@example.com')
            //->replyTo('fabien@example.com')
            //->priority(Email::PRIORITY_HIGH)
            ->subject('Notificación del Sistema de Ensayos')
            ->html('<p>See Twig integration for better HTML integration!</p>');





        $this->mailer->send($email);




        return true;
    }


}