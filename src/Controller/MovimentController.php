<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;

use App\Service\FileUploader;
use App\Entity\Moviment;
use App\Repository\MovimentRepository;
use App\Form\MovimentType;

class MovimentController extends AbstractController
{

    /**
     * @Route("/admin/moviment/list", name="moviment_list")
     */
    public function list()
    {
        $moviment = $this->getDoctrine()
            ->getRepository(Moviment::class)
            ->findAll();

        //codi de prova per visualitzar l'array de moviments

        return $this->render('admin/moviment/list.html.twig', ['moviments' => $moviment]);
    }

    /**
    * @Route("/admin/moviment/new", name="moviment_new")
    */
    public function new(Request $request, FileUploader $fileUploader)
    {
        $moviment = new Moviment();
        //podem personalitzar el text del botó passant una opció 'submit' al builder de la classe movimentType
        $form = $this->createForm(MovimentType::class, $moviment, array('submit'=>'Crear moviment'));

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

          // recollim els camps del formulari en l'objecte moviment
            $moviment = $form->getData();

            $brochureFile = $form->get('Animation')->getData();
            if ($brochureFile) {
                $brochureFileName = $fileUploader->upload($brochureFile, $moviment->getNom());
                $moviment->setAnimation("animations/".$brochureFileName);
            }else{
                $this->addFlash(
                    'notice',
                    "has d'afegir una imatge!"
                );
                return $this->redirectToRoute('moviment_new');
            }

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($moviment);
            $entityManager->flush();

            $this->addFlash(
                'notice',
                'Nou moviment '.$moviment->getId().' creat!'
            );

            return $this->redirectToRoute('moviment_list');
        }

        return $this->render('admin/moviment/moviment.html.twig', array(
            'form' => $form->createView(),
            'title' => 'Nou moviment',
        ));
    }

    /**
     * @Route("/admin/moviment/delete/{id}", name="moviment_delete", requirements={"id"="\d+"})
     */
    public function delete($id, Request $request)
    {
        $movimentRepository = $this->getDoctrine()
        ->getRepository(Moviment::class);
        $moviment = $movimentRepository
            ->find($id);

        $status = $movimentRepository
            ->remove($moviment);

        if (($status >= 200) && ($status < 300)) {
          $this->addFlash(
            'notice',
            'moviment '.$moviment->getCodi().' eliminat!'
          );
        } else {
          $this->addFlash(
            'notice',
            'ERROR '.$status
          );
        }
        return $this->redirectToRoute('moviment_list');
    }

    /**
     * @Route("/admin/moviment/edit/{id<\d+>}", name="moviment_edit")
     */
    public function edit($id, Request $request)
    {
        $movimentRepository = $this->getDoctrine()
        ->getRepository(Moviment::class);
        $moviment = $movimentRepository
            ->find($id);

        //podem personalitzar el text del botó passant una opció 'submit' al builder de la classe movimentType
        $form = $this->createForm(MovimentType::class, $moviment, array('submit'=>'Desar'));
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            // recollim els camps del formulari en l'objecte moviment
            $moviment = $form->getData();

            $status = $movimentRepository
                ->add($moviment);

            if (($status >= 200) && ($status < 300)) {
            	$this->addFlash(
                'notice',
                'moviment '.$moviment->getCodi().' desat!'
              );
            } else {
              $this->addFlash(
                'notice',
                'ERROR '.$status
              );
            }

            return $this->redirectToRoute('moviment_list');
        }

        return $this->render('admin/moviment/moviment.html.twig', array(
            'form' => $form->createView(),
            'title' => 'Editar moviment',
        ));
    }
}
?>