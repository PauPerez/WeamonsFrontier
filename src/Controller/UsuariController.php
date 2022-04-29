<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use App\Service\FileUploader;
use Symfony\Component\HttpFoundation\Cookie;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

use App\Entity\Usuari;
use App\Repository\UsuariRepository;
use App\Form\UsuariType;

use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;

class UsuariController extends AbstractController
{

    /**
     * @Route("/admin/usuari/list", name="usuari_list")
     */
    public function list()
    {
        $usuaris = $this->getDoctrine()
            ->getRepository(Usuari::class)
            ->findAll();

        //codi de prova per visualitzar l'array de usuaris
        //dump($usuaris);exit();

        return $this->render('admin/usuari/list.html.twig', ['usuaris' => $usuaris]);
    }

    /**
    * @Route("/admin/usuari/new", name="usuari_new")
    */
    public function new(Request $request, FileUploader $fileUploader)
    {
        $usuari = new Usuari();
        //podem personalitzar el text del botó passant una opció 'submit' al builder de la classe usuariType
        $form = $this->createForm(UsuariType::class, $usuari, array('submit'=>'Crear usuari'));

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

          // recollim els camps del formulari en l'objecte usuari
            $usuari = $form->getData();

            $brochureFile = $form->get('Img')->getData();
            if ($brochureFile) {
                $brochureFileName = $fileUploader->upload($brochureFile, $usuari->getUsername());
                $usuari->setImg($brochureFileName);
            }
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($usuari);
            $entityManager->flush();

            $this->addFlash(
                'notice',
                'Nou usuari '.$usuari->getId().' creat!'
            );

            return $this->redirectToRoute('usuari_list');
        }

        return $this->render('admin/usuari/usuari.html.twig', array(
            'form' => $form->createView(),
            'title' => 'Nou usuari',
        ));
    }

    /**
     * @Route("/admin/usuari/delete/{id}", name="usuari_delete", requirements={"id"="\d+"})
     */
    public function delete($id, Request $request)
    {
        $usuariRepository = $this->getDoctrine()
        ->getRepository(Usuari::class);
        $usuari = $usuariRepository
            ->find($id);

        $status = $usuariRepository
            ->remove($usuari);

        if (($status >= 200) && ($status < 300)) {
          $this->addFlash(
            'notice',
            'usuari '.$usuari->getCodi().' eliminat!'
          );
        } else {
          $this->addFlash(
            'notice',
            'ERROR '.$status
          );
        }
        return $this->redirectToRoute('usuari_list');
    }

    /**
     * @Route("/admin/usuari/edit/{id<\d+>}", name="usuari_edit")
     */
    public function edit($id, Request $request)
    {
        $usuariRepository = $this->getDoctrine()
        ->getRepository(Usuari::class);
        $usuari = $usuariRepository
            ->find($id);

        //podem personalitzar el text del botó passant una opció 'submit' al builder de la classe usuariType
        $form = $this->createForm(UsuariType::class, $usuari, array('submit'=>'Desar'));
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            // recollim els camps del formulari en l'objecte usuari
            $usuari = $form->getData();

            $status = $usuariRepository
                ->add($usuari);

            if (($status >= 200) && ($status < 300)) {
            	$this->addFlash(
                'notice',
                'usuari '.$usuari->getCodi().' desat!'
              );
            } else {
              $this->addFlash(
                'notice',
                'ERROR '.$status
              );
            }

            return $this->redirectToRoute('usuari_list');
        }

        return $this->render('admin/usuari/usuari.html.twig', array(
            'form' => $form->createView(),
            'title' => 'Editar usuari',
        ));
    }

    /**
    * @Route("/registration", name="registration")
    */
    public function register(MailerInterface $mailer, UserPasswordHasherInterface $passwordHasher): Response
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
            ->subject('Verifica el compte!')
            ->text('Sending emails is fun again!')
            //->html('<p>See Twig integration for better HTML integration!</p>');
            ->htmlTemplate('verificarCuenta.html.twig')
            ->context(['link' => $this->getParameter('link_servidor'),
              'token' => $usuari->getVerificationToken(),
              'mail' => $usuari->getEmail()]);
        
        //var_dump($email);die;
        $mailer->send($email);
        
        // Guardem les dades a la base de dades
        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->persist($usuari);
        $entityManager->flush();

        return $this->redirectToRoute('principal');
      }

      return $this->redirectToRoute('register');
    }

    /**
    * @Route("/accountVerified", name="accountVerified")
    */
    public function accountVerified(Request $request): Response
    {
      //var_dump($request->query->get('token'));die;
      $token = $request->query->get('token');

      $usuariRepository = $this->getDoctrine()
        ->getRepository(Usuari::class);
      $usuari = $usuariRepository
        ->findByToken($token);
      
      if ($usuari != null) {
        $usuari->setVerificationToken(null);
        $usuari->setIsVerified(true);

        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->persist($usuari);
        $entityManager->flush();

        return $this->redirectToRoute('principal');
      }

      return $this->redirectToRoute('weadex');
    }

    /**
    * @Route("/pruebas/authentication", name="authentication")
    */
    /*public function authentication(): Response
    {
      $usuaris = $this->getDoctrine()
        ->getRepository(Usuari::class)
        ->findAll();
      
      $trobat = false;
      $correcte = false;
      $user = new Usuari();
      foreach ($usuaris as $posicion=>$usuari)
        if ($usuari->getIsVerified()) {
          if ($usuari->getUsername() == $_POST['_username'] || $usuari->getEmail() == $_POST['_username'])
          $trobat = true;

          if ($trobat == true && $usuari->getPassword() == $_POST['_password']) {
            $correcte = true;
            $user = $usuari;
          }
        }

      if ($correcte) {
        $stringUser = serialize($user);
        $response = $this->redirectToRoute('principal');
        $response->headers->setCookie(Cookie::create('Authetication', $stringUser, time() + 3600));
        return $response;
      } else {
        return $this->redirectToRoute('login');
      }
        
    }*/
}
?>