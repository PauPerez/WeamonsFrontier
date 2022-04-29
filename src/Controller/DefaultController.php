<?php
namespace App\Controller;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class DefaultController extends AbstractController
{
  /**
   * @Route("/", name="home")
   */
  public function home()
  {
    $user = $this->getUser();
    if ($user == null)
      $username = "";
    else
      $username = $user->getUsername();

    return $this->render('principal.html.twig', [
        'username' => $username,
    ]);
  }

}
