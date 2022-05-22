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
     * @Route("/user/game/{id}", name="game")
     */
    public function game($id)
    {
        $user = $this->getUser();
        $username = $user->getUsername();
        $equip = $this->getDoctrine()
        ->getRepository(Equip::class)
        ->find($id);

        $weamons = $this->getDoctrine()
            ->getRepository(Weamon::class)
            ->findAll();
        
        $enemics = array();
        $nums = array();
        $num = 0;
        $trobat = false;
        for ($i=0; $i < 4; $i++) { 
            $num = random_int(0,count($weamons)-1);
            for ($s=0; $s < count($nums); $s++) { 
                if ($nums[$s] == $num) {
                    $trobat = true;
                }
            }
            if ($trobat != true) {
                array_push($nums,$num);
                array_push($enemics,$weamons[$num]);
            }else {
                $trobat = false;
                $i = $i-1;
            }
            
        }

        return $this->render('user/game.html.twig',["weamons"=>$equip->getWeamons(), "enemics"=>$enemics, 'username'=>$username]);
    }
}
