<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use App\Service\FileUploader;

use App\Entity\Weamon;
use App\Repository\WeamonRepository;
use App\Form\WeamonType;

class WeamonController extends AbstractController
{

    /**
     * @Route("/weamon/list", name="weamon_list")
     */
    public function list()
    {
        $weamons = $this->getDoctrine()
            ->getRepository(Weamon::class)
            ->findAll();

        //codi de prova per visualitzar l'array de weamons
        //dump($weamons);exit();

        return $this->render('weamon/list.html.twig', ['weamons' => $weamons]);
    }

    /**
    * @Route("/weamon/new", name="weamon_new")
    */
    public function new(Request $request, FileUploader $fileUploader)
    {
        $weamon = new Weamon();
        //podem personalitzar el text del botó passant una opció 'submit' al builder de la classe weamonType
        $form = $this->createForm(WeamonType::class, $weamon, array('submit'=>'Crear weamon'));

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

          // recollim els camps del formulari en l'objecte weamon
            $weamon = $form->getData();

            $brochureFile = $form->get('Img')->getData();
            if ($brochureFile) {
                $brochureFileName = $fileUploader->upload($brochureFile, $weamon->getNom());
                $weamon->setImg($brochureFileName);
            }
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($weamon);
            $entityManager->flush();

            $this->addFlash(
                'notice',
                'Nou weamon '.$weamon->getId().' creat!'
            );

            return $this->redirectToRoute('weamon_list');
        }

        return $this->render('weamon/weamon.html.twig', array(
            'form' => $form->createView(),
            'title' => 'Nou weamon',
        ));
    }

    /**
     * @Route("/weamon/delete/{id}", name="weamon_delete", requirements={"id"="\d+"})
     */
    public function delete($id, Request $request)
    {
        $weamonRepository = $this->getDoctrine()
        ->getRepository(Weamon::class);
        $weamon = $weamonRepository
            ->find($id);

        $status = $weamonRepository
            ->remove($weamon);

        if (($status >= 200) && ($status < 300)) {
          $this->addFlash(
            'notice',
            'weamon '.$weamon->getCodi().' eliminat!'
          );
        } else {
          $this->addFlash(
            'notice',
            'ERROR '.$status
          );
        }
        return $this->redirectToRoute('weamon_list');
    }

    /**
     * @Route("/weamon/edit/{id<\d+>}", name="weamon_edit")
     */
    public function edit($id, Request $request)
    {
        $weamonRepository = $this->getDoctrine()
        ->getRepository(Weamon::class);
        $weamon = $weamonRepository
            ->find($id);

        //podem personalitzar el text del botó passant una opció 'submit' al builder de la classe weamonType
        $form = $this->createForm(WeamonType::class, $weamon, array('submit'=>'Desar'));
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            // recollim els camps del formulari en l'objecte weamon
            $weamon = $form->getData();

            $status = $weamonRepository
                ->add($weamon);

            if (($status >= 200) && ($status < 300)) {
            	$this->addFlash(
                'notice',
                'weamon '.$weamon->getCodi().' desat!'
              );
            } else {
              $this->addFlash(
                'notice',
                'ERROR '.$status
              );
            }

            return $this->redirectToRoute('weamon_list');
        }

        return $this->render('weamon/weamon.html.twig', array(
            'form' => $form->createView(),
            'title' => 'Editar weamon',
        ));
    }
}
?>