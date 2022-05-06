<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\String\Slugger\SluggerInterface;
use App\Services\FileUploader;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Knp\Component\Pager\PaginatorInterface;

use App\Entity\Weamon;
use App\Repository\WeamonRepository;
use App\Form\WeamonType;

use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;

class MasterController extends AbstractController
{
    /**
     * @Route("/register", name="register")
     */
    public function register(): Response
    {
        return $this->render('register.html.twig', [
            'controller_name' => 'PruebasController',
        ]);
    }

    /**
     * @Route("/login", name="login")
     */
    public function login(AuthenticationUtils $authenticationUtils): Response
    {
        // get the login error if there is one
        $error = $authenticationUtils->getLastAuthenticationError();
        if ($error != null)
            $error = "* Credenciales invalidas!";

        // last username entered by the user
        $lastUsername = $authenticationUtils->getLastUsername();

        return $this->render('login.html.twig', [
            'last_username' => $lastUsername,
            'error' => $error
        ]);
    }

    /**
     * @Route("/principal", name="principal")
     */
    public function principal(MailerInterface $mailer): Response
    {
        $user = $this->getUser();
        if ($user == null)
          $username = "";
        else
          $username = $user->getUsername();

        // Enviem email de confirmacio de creacio
        /*$email = (new TemplatedEmail())
            ->from('hello@example.com')
            ->to('diego_aguilarlopez@iescarlesvallbona.cat')
            ->subject('Verifica el compte!')
            ->text('Sending emails is fun again!')
            ->htmlTemplate('verificarCuenta.html.twig')
            ->context(['link' => $this->getParameter('link_servidor'),
              'token' => 'xd',
              'mail' => 'diego_aguilarlopez@iescarlesvallbona.cat']);
        
        //var_dump($email);die;
        $mailer->send($email);*/

        return $this->render('principal.html.twig', [
            'username' => $username,
        ]);
    }

    /**
     * @Route("/user/perfil", name="perfil")
     */
    public function perfil(): Response
    {
        $user = $this->getUser();

        return $this->render('user/perfil.html.twig', [
            'username' => $user->getUsername(),
            'user'     => $user,
        ]);
    }

    /**
     * @Route("/user/weadex", name="weadex")
     */
    public function weadex(Request $request, PaginatorInterface $paginator)
    {
        $user = $this->getUser();
        $allWeamons = $this->getDoctrine()
            ->getRepository(Weamon::class)
            ->findAll();
        
        // Paginate the results of the query
        $weamons = $paginator->paginate(
            // Doctrine Query, not results
            $allWeamons,
            // Define the page parameter
            $request->query->getInt('page', 1),
            // Items per page
            12
        );
        
        // Render the twig view
        return $this->render('user/weadex.html.twig', [
            'weamons' => $weamons,
            'username' => $user->getUsername(),
        ]);
    }

    /**
     * @Route("/user/weamon-info/{id<\d+>}", name="weamon-info")
     */
    public function weamon_info($id): Response
    {
        $user = $this->getUser();
        $weamonRepository = $this->getDoctrine()
        ->getRepository(Weamon::class);

        $weamon = $weamonRepository
            ->find($id);

        $weamons = $this->getDoctrine()
            ->getRepository(Weamon::class)
            ->findAll();

        for ($i=0; $i < count($weamons); $i++) { 
            if ($weamons[$i] == $weamon) {
                switch ($weamon->getNEvolucion()) {
                    case 1:
                        $primera = $weamons[$i];
                        if ($weamons[$i+1]->getNEvolucion() == 2) {
                            $segunda = $weamons[$i+1];
                        if ($weamons[$i+2]->getNEvolucion() == 3)
                            $tercera = $weamons[$i+2];
                        }
                        break;
                    case 2:
                        $segunda = $weamons[$i];
                        if ($weamons[$i-1]->getNEvolucion() == 1) {
                            $primera = $weamons[$i-1];
                        if ($weamons[$i+1]->getNEvolucion() == 3)
                            $tercera = $weamons[$i+1];
                        }
                        break;
                    case 3:
                        $tercera = $weamons[$i];
                        if ($weamons[$i-1]->getNEvolucion() == 2)
                            $segunda = $weamons[$i-1];
                        if ($weamons[$i-2]->getNEvolucion() == 1)
                            $primera = $weamons[$i-2];
                        break;
                }        
            }
        }

        return $this->render('user/weamon-info.html.twig', [
            'username' => $user->getUsername(),
            'weamon'   => $weamon,
            'primera'  => $primera,
            'segunda'  => $segunda,
            'tercera'  => $tercera
        ]);
    }

}
