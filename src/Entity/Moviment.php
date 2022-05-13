<?php

namespace App\Entity;

use App\Repository\MovimentRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=MovimentRepository::class)
 */
class Moviment
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
     * @ORM\ManyToOne(targetEntity=Tipus::class, inversedBy="moviments")
     * @ORM\JoinColumn(nullable=false)
     */
    private $Tipus;

    /**
     * @ORM\ManyToMany(targetEntity=Weamon::class, mappedBy="Moviments")
     */
    private $weamons;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $animation;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $descripcion;

    public function __construct()
    {
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
            $weamon->addMoviment($this);
        }

        return $this;
    }

    public function removeWeamon(Weamon $weamon): self
    {
        if ($this->weamons->removeElement($weamon)) {
            $weamon->removeMoviment($this);
        }

        return $this;
    }

    public function getAnimation(): ?string
    {
        return $this->animation;
    }

    public function setAnimation(string $animation): self
    {
        $this->animation = $animation;
        return $this;
    }
    public function getDescripcion(): ?string
    {
        return $this->descripcion;
    }

    public function setDescripcion(string $descripcion): self
    {
        $this->descripcion = $descripcion;

        return $this;
    }
}
