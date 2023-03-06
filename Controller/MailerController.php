<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;

class MailerController extends AbstractController
{
    #[Route('/email')]
    public function sendEmail(MailerInterface $mailer): Response
    {
        $email = (new TemplatedEmail())
            ->from('ibtihellebenyahmed@gmail.com')
            ->to('ibtihellebanyahmed@gmail.com')
            //->cc('cc@example.com')
            //->bcc('bcc@example.com')
            //->replyTo('fabien@example.com')
            //->priority(Email::PRIORITY_HIGH)
            ->subject('Time for Symfony Mailer!')
            ->text('Sending emails is fun again!')
            ->htmlTemplate('mailer/mail.html.twig')
            ->context([
                'username' => 'ibtihel',
                'user_email' => 'votre reclamation a été envoyé avec succés , on vous envoie un reponse le bientot possible',
            ]);

        $mailer->send($email);

        return new Response('Email sent successfully');
    }
}

