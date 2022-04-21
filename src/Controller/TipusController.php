<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;

use App\Entity\Tipus;
use App\Repository\TipusRepository;
use App\Form\TipusType;

class TipusController extends AbstractController
{

    /**
     * @Route("/tipus/list", name="tipus_list")
     */
    public function list()
    {
        $tipus = $this->getDoctrine()
            ->getRepository(Tipus::class)
            ->findAll();

        //codi de prova per visualitzar l'array de tipuss

        return $this->render('tipus/list.html.twig', ['tipuses' => $tipus]);
    }

    /**
    * @Route("/tipus/new", name="tipus_new")
    */
    public function new(Request $request)
    {
        $tipus = new Tipus();
        //podem personalitzar el text del botó passant una opció 'submit' al builder de la classe tipusType
        $form = $this->createForm(tipusType::class, $tipus, array('submit'=>'Crear tipus'));

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

          // recollim els camps del formulari en l'objecte tipus
            $tipus = $form->getData();

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($tipus);
            $entityManager->flush();

            $this->addFlash(
                'notice',
                'Nou tipus '.$tipus->getId().' creat!'
            );

            return $this->redirectToRoute('tipus_list');
        }

        return $this->render('tipus/tipus.html.twig', array(
            'form' => $form->createView(),
            'title' => 'Nou tipus',
        ));
    }

    /**
     * @Route("/tipus/delete/{id}", name="tipus_delete", requirements={"id"="\d+"})
     */
    public function delete($id, Request $request)
    {
        $tipusRepository = $this->getDoctrine()
        ->getRepository(Tipus::class);
        $tipus = $tipusRepository
            ->find($id);

        $status = $tipusRepository
            ->remove($tipus);

        if (($status >= 200) && ($status < 300)) {
          $this->addFlash(
            'notice',
            'tipus '.$tipus->getCodi().' eliminat!'
          );
        } else {
          $this->addFlash(
            'notice',
            'ERROR '.$status
          );
        }
        return $this->redirectToRoute('tipus_list');
    }

    /**
     * @Route("/tipus/edit/{id<\d+>}", name="tipus_edit")
     */
    public function edit($id, Request $request)
    {
        $tipusRepository = $this->getDoctrine()
        ->getRepository(Tipus::class);
        $tipus = $tipusRepository
            ->find($id);

        //podem personalitzar el text del botó passant una opció 'submit' al builder de la classe tipusType
        $form = $this->createForm(TipusType::class, $tipus, array('submit'=>'Desar'));
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            // recollim els camps del formulari en l'objecte tipus
            $tipus = $form->getData();

            $status = $tipusRepository
                ->add($tipus);

            if (($status >= 200) && ($status < 300)) {
            	$this->addFlash(
                'notice',
                'tipus '.$tipus->getCodi().' desat!'
              );
            } else {
              $this->addFlash(
                'notice',
                'ERROR '.$status
              );
            }

            return $this->redirectToRoute('tipus_list');
        }

        return $this->render('tipus/tipus.html.twig', array(
            'form' => $form->createView(),
            'title' => 'Editar tipus',
        ));
    }
}
?>