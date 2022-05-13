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
    public function new(Request $request, FileUploader $fileUploader)
    {
        $usuari = new Usuari();
        //podem personalitzar el text del botó passant una opció 'submit' al builder de la classe usuariType
        $form = $this->createForm(UsuariType::class, $usuari, array('submit'=>'Crear usuari'));

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

          // recollim els camps del formulari en l'objecte usuari
            $usuari = $form->getData();

            $brochureFile = $form->get('img')->getData();
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

}
?>