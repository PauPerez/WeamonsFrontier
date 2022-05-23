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
use App\Entity\Equip;
use App\Entity\Weamon;
use App\Repository\UsuariRepository;
use App\Repository\WeamonRepository;
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
     * @Route("/admin/usuari/backoffice", name="admin_backoffice")
     */
    public function office()
    {
        //codi de prova per visualitzar l'array de usuaris
        //dump($usuaris);exit();

        return $this->render('admin/backoffice.html.twig');
    }

    /**
    * @Route("/admin/usuari/new", name="usuari_new")
    */
    public function new(Request $request, FileUploader $fileUploader, UserPasswordHasherInterface $passwordHasher)
    {
        $usuari = new Usuari();
        //podem personalitzar el text del botó passant una opció 'submit' al builder de la classe usuariType
        $form = $this->createForm(UsuariType::class, $usuari, array('submit'=>'Crear usuari'));

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
	$allWeamons = $this->getDoctrine()
          ->getRepository(Weamon::class)
          ->findAll();
          // recollim els camps del formulari en l'objecte usuari
            $usuari->setUsername($form->get('username')->getData());
        $usuari->setEmail($form->get('email')->getData());
        $usuari->setRoles($form->get('Roles')->getData());
        $usuari->setImg("avatares/trainer_male_C.png");
        $usuari->setIsVerified($form->get('is_verified')->getData());

            $hashedPassword = $passwordHasher->hashPassword(
          $usuari,
          $form->get('password')->getData()
        );
        $usuari->setPassword($hashedPassword);
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($usuari);
            $entityManager->flush();

for ($i=0; $i < 4; $i++) { 
          $equip = new Equip();
          $equip->setUsuari($usuari);
          $equip->setUsuari2($usuari);
          for ($j=1; $j <= count($allWeamons); $j++) {
            if (count($equip->getWeamons()) < 4)
              $equip->addWeamon($allWeamons[$j]);
          }
            $entityManager->persist($equip);
            $entityManager->flush();
        }
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
        $equipRepository = $this->getDoctrine()
        ->getRepository(Equip::class);
        $usuari = $usuariRepository
            ->find($id);

        $equips = $usuari->getEquips();
        if (count($equips) > 0) {
            for ($i=0; $i < count($equips); $i++) { 
                $equipRepository->remove($equips[$i], false);
            }
        }
        
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
    public function edit($id, Request $request, FileUploader $fileUploader,UserPasswordHasherInterface $passwordHasher)
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
            $usuari->setUsername($form->get('username')->getData());
        $usuari->setEmail($form->get('email')->getData());
        $usuari->setRoles($form->get('Roles')->getData());
        $usuari->setIsVerified($form->get('is_verified')->getData());
            
      $hashedPassword = $passwordHasher->hashPassword(
          $usuari,
          $form->get('password')->getData()
        );
        $usuari->setPassword($hashedPassword);
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

}
?>
