<?php

namespace App\Entity;

use App\Repository\HistorialRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=HistorialRepository::class)
 */
class Historial
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="integer")
     */
    private $Resultat;

    /**
     * @ORM\ManyToOne(targetEntity=Usuari::class, inversedBy="historials")
     * @ORM\JoinColumn(nullable=false)
     */
    private $Usuari;

    /**
     * @ORM\ManyToOne(targetEntity=Usuari::class, inversedBy="historials")
     */
    private $usuari;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getResultat(): ?int
    {
        return $this->Resultat;
    }

    public function setResultat(int $Resultat): self
    {
        $this->Resultat = $Resultat;

        return $this;
    }

    public function getUsuari(): ?Usuari
    {
        return $this->Usuari;
    }

    public function setUsuari(?Usuari $Usuari): self
    {
        $this->Usuari = $Usuari;

        return $this;
    }

    public function setUsuariP(?Usuari $Usuari): self
    {
        $this->usuari = $Usuari;

        return $this;
    }
}
