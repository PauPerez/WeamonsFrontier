<?php
namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\Routing\Annotation\Route;

class MailerController extends AbstractController
{
    /**
     * @Route("/gmail", name="gmail")
     */
    public function sendEmail(MailerInterface $mailer): Response
    {
        $email = (new TemplatedEmail())
            ->from('hello@example.com')
            ->to('diego_aguilarlopez@iescarlesvallbona.cat')
            ->subject('Verifica el compte!')
            ->text('Sending emails is fun again!')
            //->html('<p>See Twig integration for better HTML integration!</p>');
            ->htmlTemplate('pruebas/verificarCuenta.html.twig');

        $mailer->send($email);

        return $this->render('pruebas/principal.html.twig', [
            'controller_name' => 'PruebasController',
        ]);
    }
}