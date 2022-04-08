<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use App\Service\FileUploader;

use App\Entity\Equip;
use App\Repository\EquipRepository;
use App\Form\EquipType;

class EquipController extends AbstractController
{

    /**
     * @Route("/equip/list", name="equip_list")
     */
    public function list()
    {
        $equips = $this->getDoctrine()
            ->getRepository(Equip::class)
            ->findAll();

        //codi de prova per visualitzar l'array de equips
        //dump($equips);exit();

        return $this->render('equip/list.html.twig', ['equips' => $equips]);
    }

    /**
    * @Route("/equip/new", name="equip_new")
    */
    public function new(Request $request, FileUploader $fileUploader)
    {
        $equip = new Equip();
        //podem personalitzar el text del botó passant una opció 'submit' al builder de la classe EquipType
        $form = $this->createForm(EquipType::class, $equip, array('submit'=>'Crear equip'));

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

          // recollim els camps del formulari en l'objecte equip
            $equip = $form->getData();

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($equip);
            $entityManager->flush();

            $this->addFlash(
                'notice',
                'Nou equip '.$equip->getId().' creat!'
            );

            return $this->redirectToRoute('equip_list');
        }

        return $this->render('equip/equip.html.twig', array(
            'form' => $form->createView(),
            'title' => 'Nou equip',
        ));
    }
}

   

?>