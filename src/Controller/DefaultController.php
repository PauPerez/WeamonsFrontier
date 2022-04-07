<?php
namespace App\Controller;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class DefaultController extends AbstractController
{
  /*
  public function index(): Response
  {
    return new Response('Hola que tal');
  }*/

  /**
   * @Route("/", name="home")
   */
  public function home()
  {
    return $this->render('pruebas/principal.html.twig');
  }

}
