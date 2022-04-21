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

use App\Form\BakuganType;
use App\Form\ElementoType;

use App\Entity\Bakugan;
use App\Entity\Elemento;

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
    public function login(): Response
    {
        return $this->render('pruebas/login.html.twig', [
            'controller_name' => 'PruebasController',
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
    public function weadex(): Response
    {
        return $this->render('pruebas/weadex.html.twig', [
            'controller_name' => 'PruebasController',
        ]);
    }

}
