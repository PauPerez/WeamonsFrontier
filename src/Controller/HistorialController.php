<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use App\Service\FileUploader;

use App\Entity\Historial;
use App\Entity\Usuari;
use App\Repository\HistorialRepository;
use App\Repository\UsuariRepository;
use App\Form\HistorialType;

class HistorialController extends AbstractController
{

    /**
     * @Route("/admin/historial/list", name="historial_list")
     */
    public function list()
    {
        $historials = $this->getDoctrine()
            ->getRepository(Historial::class)
            ->findAll();

        //codi de prova per visualitzar l'array de historials
        //dump($historials);exit();

        return $this->render('admin/historial/list.html.twig', ['historials' => $historials]);
    }

    /**
    * @Route("/admin/historial/new", name="historial_new")
    */
    public function new(Request $request, FileUploader $fileUploader)
    {
        $historial = new Historial();
        //podem personalitzar el text del botó passant una opció 'submit' al builder de la classe historialType
        $form = $this->createForm(HistorialType::class, $historial, array('submit'=>'Crear historial'));

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $idOrigen = $form->get('Usuari')->getData();
          $idDestinacio = $form->get('Contrincant')->getData();
          // recollim els camps del formulari en l'objecte historial
          $usuari = $this->getDoctrine()
            ->getRepository(Usuari::class)
            ->find($idOrigen);
          $contrincant = $this->getDoctrine()
            ->getRepository(Usuari::class)
            ->find($idDestinacio);

          /* si els usuaris origen i destinacio son el mateix, recarreguem el
            formulari amb un missatge de validacio */
          if ($usuari == $contrincant) {
            $this->addFlash(
                'notice',
                "L'usuari i contrincant han de ser diferents"
            );
            return $this->redirectToRoute('historial_list');
            }

            $historial = $form->getData();
            
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($historial);
            $entityManager->flush();

            $this->addFlash(
                'notice',
                'Nou historial '.$historial->getId().' creat!'
            );

            return $this->redirectToRoute('historial_list');
        }

        return $this->render('admin/historial/historial.html.twig', array(
            'form' => $form->createView(),
            'title' => 'Nou historial',
        ));
    }

  /**
   * @Route("/user/historial/create/{victory}", name="historial_create")
   */
  public function create($victory)
  {
    $historial = new Historial();
    $user = $this->getDoctrine()
    ->getRepository(Usuari::class)
    ->find($this->getUser()->getId());

    $historial->setUsuari($user);
    $historial->setUsuariP($user);
    $historial->setContrincant($user);
    $historial->setResultat($victory);

    $notification ="";
    $color="";
    if ($victory == 1) {
      $notification="YOU WIN!";
      $color="green";
    }else {
      $notification="YOU LOSE!";
      $color="red";
    }

    $entityManager = $this->getDoctrine()->getManager();
    $entityManager->persist($historial);
    $entityManager->flush();

    //codi de prova per visualitzar l'array de historials
    //dump($historials);exit();

    return $this->render("user/postgame.html.twig", ["username"=>$user->getUsername(), 'notificacion'=>$notification, 'color'=>$color]);
  }

  /**
 * @Route("/admin/historial/delete/{id}", name="historial_delete", requirements={"id"="\d+"})
 */
public function delete($id, Request $request)
{
    $historialRepository = $this->getDoctrine()
    ->getRepository(Historial::class);
    $historial = $historialRepository
        ->find($id);

    $status = $historialRepository
        ->remove($historial);

    if (($status >= 200) && ($status < 300)) {
      $this->addFlash(
        'notice',
        'historial '.$historial->getCodi().' eliminat!'
      );
    } else {
      $this->addFlash(
        'notice',
        'ERROR '.$status
      );
    }
    return $this->redirectToRoute('historial_list');
}
}



?>