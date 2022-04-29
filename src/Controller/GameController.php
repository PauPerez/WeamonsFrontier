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
use App\Entity\Weamon;
use App\Repository\WeamonRepository;

class GameController extends AbstractController
{
    /**
     * @Route("/game/pre", name="pregame")
     */
    public function game()
    {
        $weamons = $this->getDoctrine()
            ->getRepository(Weamon::class)
            ->findAll();

        return $this->render('game/game.html.twig',["weamons"=>$weamons]);
    }
}
