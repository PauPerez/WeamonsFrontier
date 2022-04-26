<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\String\Slugger\SluggerInterface;
use App\Services\FileUploader;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Knp\Component\Pager\PaginatorInterface;

use App\Form\WeamonType;

use App\Entity\Weamon;

class PruebasController extends AbstractController
{
    /**
     * @Route("/pruebas/register", name="register")
     */
    public function register(): Response
    {
        return $this->render('pruebas/register.html.twig', [
            'controller_name' => 'PruebasController',
        ]);
    }

    /**
     * @Route("/pruebas/login", name="login")
     */
    public function login(AuthenticationUtils $authenticationUtils): Response
    {
        // get the login error if there is one
        $error = $authenticationUtils->getLastAuthenticationError();
        if ($error != null)
            $error = "* Credenciales invalidas!";

        // last username entered by the user
        $lastUsername = $authenticationUtils->getLastUsername();

        return $this->render('pruebas/login.html.twig', [
            'last_username' => $lastUsername,
            'error' => $error
        ]);
    }

    /**
     * @Route("/pruebas/principal", name="principal")
     */
    public function principal(): Response
    {
        return $this->render('pruebas/principal.html.twig', [
            'controller_name' => 'PruebasController',
        ]);
    }

    /**
     * @Route("/pruebas/weadex", name="weadex")
     */
    public function weadex(Request $request, PaginatorInterface $paginator)
    {
        $allWeamons = $this->getDoctrine()
            ->getRepository(Weamon::class)
            ->findAll();
        
        // Paginate the results of the query
        $weamons = $paginator->paginate(
            // Doctrine Query, not results
            $allWeamons,
            // Define the page parameter
            $request->query->getInt('page', 1),
            // Items per page
            12
        );
        
        // Render the twig view
        return $this->render('pruebas/weadex.html.twig', [
            'weamons' => $weamons
        ]);
    }

}
