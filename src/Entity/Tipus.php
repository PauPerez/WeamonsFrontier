<?php

namespace App\Entity;

use App\Repository\TipusRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=TipusRepository::class)
 */
class Tipus
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
     * @ORM\OneToMany(targetEntity=Moviment::class, mappedBy="Tipus", orphanRemoval=true)
     */
    private $moviments;

    /**
     * @ORM\OneToMany(targetEntity=Weamon::class, mappedBy="Tipus")
     */
    private $weamons;

    public function __construct()
    {
        $this->moviments = new ArrayCollection();
        $this->weamons = new ArrayCollection();
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

    /**
     * @return Collection<int, Moviment>
     */
    public function getMoviments(): Collection
    {
        return $this->moviments;
    }

    public function addMoviment(Moviment $moviment): self
    {
        if (!$this->moviments->contains($moviment)) {
            $this->moviments[] = $moviment;
            $moviment->setTipus($this);
        }

        return $this;
    }

    public function removeMoviment(Moviment $moviment): self
    {
        if ($this->moviments->removeElement($moviment)) {
            // set the owning side to null (unless already changed)
            if ($moviment->getTipus() === $this) {
                $moviment->setTipus(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Weamon>
     */
    public function getWeamons(): Collection
    {
        return $this->weamons;
    }

    public function addWeamon(Weamon $weamon): self
    {
        if (!$this->weamons->contains($weamon)) {
            $this->weamons[] = $weamon;
            $weamon->setTipus($this);
        }

        return $this;
    }

    public function removeWeamon(Weamon $weamon): self
    {
        if ($this->weamons->removeElement($weamon)) {
            // set the owning side to null (unless already changed)
            if ($weamon->getTipus() === $this) {
                $weamon->setTipus(null);
            }
        }

        return $this;
    }
}
