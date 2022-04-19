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
use App\Form\AddWeamonType;

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
    /**
     * @Route("/equip/delete/{id}", name="equip_delete", requirements={"id"="\d+"})
     */
    public function delete($id, Request $request)
    {
        $equipRepository = $this->getDoctrine()
        ->getRepository(Equip::class);
        $equip = $equipRepository
            ->find($id);

        $status = $equipRepository
            ->remove($equip);

        if (($status >= 200) && ($status < 300)) {
          $this->addFlash(
            'notice',
            'equip '.$equip->getCodi().' eliminat!'
          );
        } else {
          $this->addFlash(
            'notice',
            'ERROR '.$status
          );
        }
        return $this->redirectToRoute('equip_list');
    }

    /**
     * @Route("/equip/edit/{id<\d+>}", name="equip_edit")
     */
    public function edit($id, Request $request)
    {
        $equipRepository = $this->getDoctrine()
        ->getRepository(Equip::class);
        $equip = $equipRepository
            ->find($id);

        //podem personalitzar el text del botó passant una opció 'submit' al builder de la classe equipType
        $form = $this->createForm(equipType::class, $equip, array('submit'=>'Desar'));
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            // recollim els camps del formulari en l'objecte equip
            $equip = $form->getData();

            $status = $equipRepository
                ->add($equip);

            if (($status >= 200) && ($status < 300)) {
            	$this->addFlash(
                'notice',
                'equip '.$equip->getCodi().' desat!'
              );
            } else {
              $this->addFlash(
                'notice',
                'ERROR '.$status
              );
            }

            return $this->redirectToRoute('equip_list');
        }

        return $this->render('equip/equip.html.twig', array(
            'form' => $form->createView(),
            'title' => 'Editar equip',
        ));
    }

    /**
    * @Route("/equip/add/{id<\d+>}", name="equip_add")
    */
    public function add($id, Request $request)
    {
      $equipRepository = $this->getDoctrine()
      ->getRepository(Equip::class);
      $equip = $equipRepository
          ->find($id);

        $weamons = $equip->getWeamons();
        if (count($weamons) == 4) {
          $this->addFlash(
            'notice',
            "L'equip no pot tenir més de 4 weamons!"
          );

          return $this->redirectToRoute('equip_list');
        }else{
        //podem personalitzar el text del botó passant una opció 'submit' al builder de la classe EquipType
        $form = $this->createForm(AddWeamonType::class, $equip, array('submit'=>'Afegir weamon'));

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
}