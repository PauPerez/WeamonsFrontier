<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;

use App\Entity\Moviment;
use App\Repository\MovimentRepository;
use App\Form\MovimentType;

class MovimentController extends AbstractController
{

    /**
     * @Route("/moviment/list", name="moviment_list")
     */
    public function list()
    {
        $moviment = $this->getDoctrine()
            ->getRepository(Moviment::class)
            ->findAll();

        //codi de prova per visualitzar l'array de moviments

        return $this->render('moviment/list.html.twig', ['moviments' => $moviment]);
    }

    /**
    * @Route("/moviment/new", name="moviment_new")
    */
    public function new(Request $request)
    {
        $moviment = new Moviment();
        //podem personalitzar el text del botó passant una opció 'submit' al builder de la classe movimentType
        $form = $this->createForm(MovimentType::class, $moviment, array('submit'=>'Crear moviment'));

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

          // recollim els camps del formulari en l'objecte moviment
            $moviment = $form->getData();

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($moviment);
            $entityManager->flush();

            $this->addFlash(
                'notice',
                'Nou moviment '.$moviment->getId().' creat!'
            );

            return $this->redirectToRoute('moviment_list');
        }

        return $this->render('moviment/moviment.html.twig', array(
            'form' => $form->createView(),
            'title' => 'Nou moviment',
        ));
    }
}
?>