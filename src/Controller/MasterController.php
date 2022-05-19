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
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

use App\Entity\Usuari;
use App\Repository\UsuariRepository;
use App\Form\UsuariType;

use App\Entity\Weamon;
use App\Repository\WeamonRepository;
use App\Form\WeamonType;

use App\Entity\Equip;
use App\Repository\EquipRepository;
use App\Form\EquipType;

use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;

class MasterController extends AbstractController
{

    /**
     * @Route("/login", name="login")
     */
    public function login(AuthenticationUtils $authenticationUtils): Response
    {
        // get the login error if there is one
        $error = $authenticationUtils->getLastAuthenticationError();
        if ($error != null)
            $error = "Credenciales invalidas!";

        // last username entered by the user
        $lastUsername = $authenticationUtils->getLastUsername();

        return $this->render('login.html.twig', [
            'last_username' => $lastUsername,
            'error' => $error
        ]);
    }

    /**
     * @Route("/register", name="register")
     */
    public function register(): Response
    {
        return $this->render('register.html.twig');
    }

    /**
    * @Route("/registration", name="registration")
    */
    public function registration(MailerInterface $mailer, UserPasswordHasherInterface $passwordHasher): Response
    {
      $usuariRepository = $this->getDoctrine()
        ->getRepository(Usuari::class);
      $user = $usuariRepository
        ->findByEmail($_POST['email']);

      if ($user == null) {
        // Recollim els camps del formulari en l'objecte usuari
        $usuari = new Usuari();
        $usuari->setUsername($_POST['username']);
        $usuari->setEmail($_POST['email']);
        $usuari->setRoles(['ROLE_USER']);
        $usuari->setImg("avatares/trainer_male_C.png");
        $usuari->setVerificationToken(bin2hex(random_bytes(64)));
        $usuari->setIsVerified(false);
        
        // Hashear la contrasenya de l'usuari
        $hashedPassword = $passwordHasher->hashPassword(
          $usuari,
          $_POST['password']
        );
        $usuari->setPassword($hashedPassword);

        // Enviem email de confirmacio de creacio
        $email = (new TemplatedEmail())
            ->from('hello@example.com')
            ->to($_POST['email'])
            ->subject('Verificar creación cuenta Weamon Frontiers')
            ->text('Sending emails is fun again!')
            ->htmlTemplate('sendVerificationToken.twig')
            ->context(['link' => $this->getParameter('link_servidor'),
              'token' => $usuari->getVerificationToken(),
              'mail' => $usuari->getEmail()]);
        
        $mailer->send($email);
        
        // Guardem les dades a la base de dades
        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->persist($usuari);
        $entityManager->flush();

        return $this->render('notificacion.html.twig', [
          'notificacion' => 'Se te a enviado un email de confirmación a tu correo electrónico!',
        ]);
      }

      return $this->render('register.html.twig', [
        'error' => 'El email introducido ya esta en uso!',
      ]);
    }

    /**
    * @Route("/accountVerified", name="accountVerified")
    */
    public function accountVerified(Request $request): Response
    {
      $token = $request->query->get('token');

      $usuariRepository = $this->getDoctrine()
        ->getRepository(Usuari::class);
      $usuari = $usuariRepository
        ->findByAccountVerifiedToken($token);
      
      if ($usuari != null) {
        $usuari->setVerificationToken(null);
        $usuari->setIsVerified(true);

        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->persist($usuari);
        $entityManager->flush();

        //Creació de 4 equips amb 4 weamons per default
        $allWeamons = $this->getDoctrine()
          ->getRepository(Weamon::class)
          ->findAll();
        
        $entityManager = $this->getDoctrine()->getManager();
          
        for ($i=0; $i < 4; $i++) { 
          $equip = new Equip();
          $equip->setUsuari($usuari);
          $equip->setUsuari2($usuari);
          for ($j=1; $j <= count($allWeamons); $j++) {
            if (count($equip->getWeamons()) < 4 && $allWeamons[$j]->getNEvolucion() % 3 == 0)
              $equip->addWeamon($allWeamons[$j]);
          }
            $entityManager->persist($equip);
            $entityManager->flush();
        }

        return $this->render('notificacion.html.twig', [
          'notificacion' => 'Cuenta verificada con éxito!',
        ]);
      }

      return $this->render('notificacion.html.twig', [
        'notificacion' => 'Su cuenta ya a sido verificada o el token dado es incorrecto!',
      ]);
    }

    /**
    * @Route("/emailChangePassword", name="emailChangePassword")
    */
    public function emailChangePassword(): Response
    {
      return $this->render('emailChangePassword.html.twig', [
        'error' => ''
      ]);
    }

    /**
    * @Route("/sendChangePassword", name="sendChangePassword")
    */
    public function sendChangePassword(MailerInterface $mailer): Response
    {
      $usuariRepository = $this->getDoctrine()
        ->getRepository(Usuari::class);
      $user = $usuariRepository
        ->findByEmail($_POST['_email']);

      if ($user != null) {
        // Recollim els camps del formulari en l'objecte user
        $user->setChangePasswordToken(bin2hex(random_bytes(64)));

        // Enviem email de confirmacio de creacio
        $email = (new TemplatedEmail())
            ->from('hello@example.com')
            ->to($_POST['_email'])
            ->subject('Petición de cambio de contraseña')
            ->text('Sending emails is fun again!')
            ->htmlTemplate('sendChangePasswordToken.html.twig')
            ->context(['link' => $this->getParameter('link_servidor'),
              'token' => $user->getChangePasswordToken(),
              'mail' => $user->getEmail()]);
        
        $mailer->send($email);
        
        // Guardem les dades a la base de dades
        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->persist($user);
        $entityManager->flush();

        return $this->render('notificacion.html.twig', [
          'notificacion' => 'Se te a enviado un email para cambiar la contraseña a tu correo electrónico!',
        ]);
      }

      return $this->render('emailChangePassword.html.twig', [
        /*'notificacion' => 'Su contraseña ya a sido cambiada o el token dado es incorrecto!',*/
        'error' => 'El email introducido es incorrecto!'
      ]);
    }

    /**
    * @Route("/formChangePassword", name="formChangePassword")
    */
    public function formChangePassword(Request $request): Response
    {
      $token = $request->query->get('token');

      $usuariRepository = $this->getDoctrine()
        ->getRepository(Usuari::class);
      $usuari = $usuariRepository
        ->findByChangePasswordToken($token);

      if ($usuari) {
        return $this->render('changePassword.html.twig', [
          'email' => $usuari->getEmail(),
        ]);
      }
      
      return $this->render('notificacion.html.twig', [
        'notificacion' => 'El token dado es incorrecto!',
      ]);
    }

    /**
    * @Route("/changePassword", name="changePassword")
    */
    public function changePassword(Request $request, UserPasswordHasherInterface $passwordHasher): Response
    {
      $usuariRepository = $this->getDoctrine()
        ->getRepository(Usuari::class);
      $user = $usuariRepository
        ->findByEmail($_POST['_email']);

      $user->setChangePasswordToken(null);
      // Hashear la contrasenya de l'usuari
      $hashedPassword = $passwordHasher->hashPassword(
        $user,
        $_POST['_password']
      );
      $user->setPassword($hashedPassword);

      // Guardem les dades a la base de dades
      $entityManager = $this->getDoctrine()->getManager();
      $entityManager->persist($user);
      $entityManager->flush();

      return $this->render('notificacion.html.twig', [
        'notificacion' => 'Se a cambiado la contraseña correctamente!',
      ]);
    }

    /**
     * @Route("/principal", name="principal")
     */
    public function principal(): Response
    {
        $user = $this->getUser();
        if ($user == null){
          $username = "";
          $roles=[];
        }
        else{
          $username = $user->getUsername();
          $roles = $user->getRoles();
        }
        // Enviem email de confirmacio de creacio
        

        return $this->render('principal.html.twig', [
            'username' => $username,
            'roles' => $roles,
        ]);
    }

    /**
     * @Route("/user/perfil", name="perfil")
     */
    public function perfil(): Response
    {
        $user = $this->getUser();
        $roles = $user->getRoles();

        return $this->render('user/perfil.html.twig', [
            'username' => $user->getUsername(),
            'user'     => $user,
            'roles' => $roles,
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
        $roles = $user->getRoles();
        // Render the twig view
        return $this->render('user/weadex.html.twig', [
            'weamons' => $weamons,
            'username' => $user->getUsername(),
            'roles' => $roles,
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

        $primera = null;
        $segunda = null;
        $tercera = null;
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
                        if ($weamons[$i-1]->getNEvolucion() == 1)
                            $primera = $weamons[$i-1];
    
                        if ($weamons[$i+1]->getNEvolucion() == 3)
                                $tercera = $weamons[$i+1];
                        break;
                    case 3:
                        $tercera = $weamons[$i];
                        if ($weamons[$i-1]->getNEvolucion() == 2) {
                            $segunda = $weamons[$i-1];
                            if ($weamons[$i-2]->getNEvolucion() == 1)
                                $primera = $weamons[$i-2];
                        }
                        break;
                }        
            }
        }

        $roles = $user->getRoles();
        return $this->render('user/weamon-info.html.twig', [
            'username' => $user->getUsername(),
            'weamon'   => $weamon,
            'primera'  => $primera,
            'segunda'  => $segunda,
            'tercera'  => $tercera,
            'roles' => $roles,
        ]);
    }

    /**
     * @Route("/user/pregame", name="pregame")
     */
    public function pregame(): Response
    {
        $user = $this->getUser();

        $allWeamons = $this->getDoctrine()
          ->getRepository(Weamon::class)
          ->findAll();

        $equipos = $user->getEquips();

        $roles = $user->getRoles();
        return $this->render('user/pregame.html.twig', [
            'username' => $user->getUsername(),
            'weamons'  => $allWeamons,
            'equipos'  => $equipos,
            'roles' => $roles,
        ]);
    }

    /**
     * @Route("/user/cambiarWeamons", name="cambiarWeamons")
     */
    public function cambiarWeamons(): Response
    {
        $user = $this->getUser();

        $equipRepository = $this->getDoctrine()
          ->getRepository(Equip::class);
        $weamonRepository = $this->getDoctrine()
          ->getRepository(Weamon::class);

        $equipos = $user->getEquips();

        $equipoSelec = $equipRepository->find($_POST['equipoSelec']);
        $posEquip = 0;
        for ($i=0; $i < count($equipos); $i++) { 
          if ($equipos[$i] == $equipoSelec)
            $posEquip = $i + 1;
        }
        
        $weamonsEquipo = $equipoSelec->getWeamons();

        for ($i=0; $i < count($weamonsEquipo); $i++) {
            $posWeamon = $i + 1;
            $weamonActual = $weamonsEquipo[$i];
            $weamonNuevo = $weamonRepository
              ->find($_POST["equipo" . "{$posEquip}-" . "$posWeamon"]);

            if ($weamonActual != $weamonNuevo) {
                $equipoSelec->cambiarWeamon($weamonNuevo, $i);
                $equipRepository
                    ->add($equipoSelec);
            }
        }

        $equipos = $user->getEquips();

        return $this->redirectToRoute('pregame');
    }

}
