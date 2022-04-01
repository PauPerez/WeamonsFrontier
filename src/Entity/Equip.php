<?php

namespace App\Entity;

use App\Repository\EquipRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=EquipRepository::class)
 */
class Equip
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity=Usuari::class, inversedBy="equips")
     * @ORM\JoinColumn(nullable=false)
     */
    private $Usuari;

    /**
     * @ORM\ManyToMany(targetEntity=Weamon::class)
     */
    private $Weamons;

    public function __construct()
    {
        $this->Weamons = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
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

    /**
     * @return Collection<int, Weamon>
     */
    public function getWeamons(): Collection
    {
        return $this->Weamons;
    }

    public function addWeamon(Weamon $weamon): self
    {
        if (!$this->Weamons->contains($weamon)) {
            $this->Weamons[] = $weamon;
        }

        return $this;
    }

    public function removeWeamon(Weamon $weamon): self
    {
        $this->Weamons->removeElement($weamon);

        return $this;
    }
}
