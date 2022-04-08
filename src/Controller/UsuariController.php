<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use App\Service\FileUploader;

use App\Entity\Usuari;
use App\Repository\UsuariRepository;
use App\Form\UsuariType;

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
}

   

?>