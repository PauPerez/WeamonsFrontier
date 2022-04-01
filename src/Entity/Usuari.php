<?php

namespace App\Entity;

use App\Repository\UsuariRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=UsuariRepository::class)
 */
class Usuari
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
    private $Username;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $Password;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $Rol;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $Img;

    /**
     * @ORM\OneToMany(targetEntity=Historial::class, mappedBy="Usuari", orphanRemoval=true)
     */
    private $historials;

    /**
     * @ORM\OneToMany(targetEntity=Equip::class, mappedBy="Usuari", orphanRemoval=true)
     */
    private $equips;

    public function __construct()
    {
        $this->historials = new ArrayCollection();
        $this->equips = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUsername(): ?string
    {
        return $this->Username;
    }

    public function setUsername(string $Username): self
    {
        $this->Username = $Username;

        return $this;
    }

    public function getPassword(): ?string
    {
        return $this->Password;
    }

    public function setPassword(string $Password): self
    {
        $this->Password = $Password;

        return $this;
    }

    public function getRol(): ?string
    {
        return $this->Rol;
    }

    public function setRol(string $Rol): self
    {
        $this->Rol = $Rol;

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

    /**
     * @return Collection<int, Historial>
     */
    public function getHistorials(): Collection
    {
        return $this->historials;
    }

    public function addHistorial(Historial $historial): self
    {
        if (!$this->historials->contains($historial)) {
            $this->historials[] = $historial;
            $historial->setUsuari($this);
        }

        return $this;
    }

    public function removeHistorial(Historial $historial): self
    {
        if ($this->historials->removeElement($historial)) {
            // set the owning side to null (unless already changed)
            if ($historial->getUsuari() === $this) {
                $historial->setUsuari(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Equip>
     */
    public function getEquips(): Collection
    {
        return $this->equips;
    }

    public function addEquip(Equip $equip): self
    {
        if (!$this->equips->contains($equip)) {
            $this->equips[] = $equip;
            $equip->setUsuari($this);
        }

        return $this;
    }

    public function removeEquip(Equip $equip): self
    {
        if ($this->equips->removeElement($equip)) {
            // set the owning side to null (unless already changed)
            if ($equip->getUsuari() === $this) {
                $equip->setUsuari(null);
            }
        }

        return $this;
    }
}
