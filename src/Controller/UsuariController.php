<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use App\Service\FileUploader;
use Symfony\Component\HttpFoundation\Cookie;

use App\Entity\Usuari;
use App\Repository\UsuariRepository;
use App\Form\UsuariType;

use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;

class UsuariController extends AbstractController
{

    /**
     * @Route("/usuari/list", name="usuari_list")
     */
    public function list()
    {
        $usuaris = $this->getDoctrine()
            ->getRepository(Usuari::class)
            ->findAll();

        //codi de prova per visualitzar l'array de usuaris
        //dump($usuaris);exit();

        return $this->render('usuari/list.html.twig', ['usuaris' => $usuaris]);
    }

    /**
    * @Route("/usuari/new", name="usuari_new")
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

        return $this->render('usuari/usuari.html.twig', array(
            'form' => $form->createView(),
            'title' => 'Nou usuari',
        ));
    }

    /**
     * @Route("/usuari/delete/{id}", name="usuari_delete", requirements={"id"="\d+"})
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
     * @Route("/usuari/edit/{id<\d+>}", name="usuari_edit")
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

        return $this->render('usuari/usuari.html.twig', array(
            'form' => $form->createView(),
            'title' => 'Editar usuari',
        ));
    }

    /**
    * @Route("/pruebas/registration", name="registration")
    */
    public function register(MailerInterface $mailer): Response
    {
      // Recollim els camps del formulari en l'objecte usuari
      $usuari = new Usuari();
      $usuari->setUsername($_POST['username']);
      $usuari->setMail($_POST['email']);
      $usuari->setPassword($_POST['password']);
      $usuari->setRol("F2P");
      $usuari->setImg("avatares/trainer_male_C.png");
      $usuari->setVerified(false);

      // Enviem email de confirmacio de creacio
      $email = (new TemplatedEmail())
          ->from('hello@example.com')
          ->to($_POST['email'])
          ->subject('Verifica el compte!')
          ->text('Sending emails is fun again!')
          //->html('<p>See Twig integration for better HTML integration!</p>');
          ->htmlTemplate('pruebas/verificarCuenta.html.twig');

      $mailer->send($email);
      
      // Guardem les dades a la base de dades
      $entityManager = $this->getDoctrine()->getManager();
      $entityManager->persist($usuari);
      $entityManager->flush();

      return $this->redirectToRoute('principal');
    }

    /**
    * @Route("/pruebas/accountVerified", name="accountVerified")
    */
    public function accountVerified(): Response
    {

      return $this->redirectToRoute('principal');
    }

    /**
    * @Route("/pruebas/authentication", name="authentication")
    */
    public function authentication(): Response
    {
      $usuaris = $this->getDoctrine()
        ->getRepository(Usuari::class)
        ->findAll();
      
      $trobat = false;
      $correcte = false;
      $user = new Usuari();
      foreach ($usuaris as $posicion=>$usuari)
        if ($usuari->getVerified()) {
          if ($usuari->getUsername() == $_POST['username'] || $usuari->getMail() == $_POST['username'])
          $trobat = true;

          if ($trobat == true && $usuari->getPassword() == $_POST['password']) {
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
        
    }
}
?>