<?php

namespace App\Entity;

use App\Repository\WeamonRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=WeamonRepository::class)
 */
class Weamon
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $Nom;

    /**
     * @ORM\Column(type="integer")
     */
    private $Vida;

    /**
     * @ORM\Column(type="integer")
     */
    private $Atac;

    /**
     * @ORM\Column(type="integer")
     */
    private $Velocitat;

    /**
     * @ORM\Column(type="boolean")
     */
    private $Shiny;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $Img;

    /**
     * @ORM\ManyToOne(targetEntity=Tipus::class, inversedBy="weamons")
     * @ORM\JoinColumn(nullable=false)
     */
    private $Tipus;

    /**
     * @ORM\ManyToMany(targetEntity=Moviment::class, inversedBy="weamons")
     */
    private $Moviments;

    public function __construct()
    {
        $this->Moviments = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNom(): ?string
    {
        return $this->Nom;
    }

    public function setNom(string $Nom): self
    {
        $this->Nom = $Nom;

        return $this;
    }

    public function getVida(): ?int
    {
        return $this->Vida;
    }

    public function setVida(int $Vida): self
    {
        $this->Vida = $Vida;

        return $this;
    }

    public function getAtac(): ?int
    {
        return $this->Atac;
    }

    public function setAtac(int $Atac): self
    {
        $this->Atac = $Atac;

        return $this;
    }

    public function getVelocitat(): ?int
    {
        return $this->Velocitat;
    }

    public function setVelocitat(int $Velocitat): self
    {
        $this->Velocitat = $Velocitat;

        return $this;
    }

    public function getShiny(): ?bool
    {
        return $this->Shiny;
    }

    public function setShiny(bool $Shiny): self
    {
        $this->Shiny = $Shiny;

        return $this;
    }

    public function getImg(): ?string
    {
        return $this->Img;
    }

    public function setImg(string $Img): self
    {
        $this->Img = $Img;

        return $this;
    }

    public function getTipus(): ?Tipus
    {
        return $this->Tipus;
    }

    public function setTipus(?Tipus $Tipus): self
    {
        $this->Tipus = $Tipus;

        return $this;
    }

    /**
     * @return Collection<int, Moviment>
     */
    public function getMoviments(): Collection
    {
        return $this->Moviments;
    }

    public function addMoviment(Moviment $moviment): self
    {
        if (!$this->Moviments->contains($moviment)) {
            $this->Moviments[] = $moviment;
        }

        return $this;
    }

    public function removeMoviment(Moviment $moviment): self
    {
        $this->Moviments->removeElement($moviment);

        return $this;
    }
}
