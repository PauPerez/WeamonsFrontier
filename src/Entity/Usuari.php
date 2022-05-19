<?php

namespace App\Entity;

use App\Repository\UsuariRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * @ORM\Entity(repositoryClass=UsuariRepository::class)
 */
class Usuari implements UserInterface, PasswordAuthenticatedUserInterface
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=180, unique=true)
     */
    private $email;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $username;

    /**
     * @ORM\Column(type="json")
     */
    private $roles = [];

    /**
     * @var string The hashed password
     * @ORM\Column(type="string")
     */
    private $password;


    /**
     * @ORM\Column(type="string", length=255)
     */
    private $img;

    /**
     * @ORM\OneToMany(targetEntity=Historial::class, mappedBy="usuari")
     */
    private $historials;

    /**
     * @ORM\OneToMany(targetEntity=Equip::class, mappedBy="usuari")
     */
    private $equips;

    /**
     * @ORM\Column(type="boolean")
     */
    private $is_verified;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $verificationToken;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $changePasswordToken;

    public function __construct()
    {
        $this->historials = new ArrayCollection();
        $this->equips = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }

    public function getUsername(): ?string
    {
        return $this->username;
    }

    public function setUsername(string $username): self
    {
        $this->username = $username;

        return $this;
    }

    /**
     * A visual identifier that represents this user.
     *
     * @see UserInterface
     */
    public function getUserIdentifier(): string
    {
        return (string) $this->email;
    }

    public function getRoles(): array
    {
        $roles = $this->roles;
        // guarantee every user at least has ROLE_USER
        $roles[] = 'ROLE_USER';

        return array_unique($roles);
    }

    public function setRoles(array $roles): self
    {
        $this->roles = $roles;

        return $this;
    }

    /**
     * @see PasswordAuthenticatedUserInterface
     */
    public function getPassword(): string
    {
        return $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    /**
     * Returning a salt is only needed, if you are not using a modern
     * hashing algorithm (e.g. bcrypt or sodium) in your security.yaml.
     *
     * @see UserInterface
     */
    public function getSalt(): ?string
    {
        return null;
    }

    /**
     * @see UserInterface
     */
    public function eraseCredentials()
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
    }

    public function getImg(): ?string
    {
        return $this->img;
    }

    public function setImg(string $img): self
    {
        $this->img = $img;

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
                $equip->setUsuari2(null);
            }
        }

        return $this;
    }

    public function getIsVerified(): ?bool
    {
        return $this->is_verified;
    }

    public function setIsVerified(bool $is_verified): self
    {
        $this->is_verified = $is_verified;

        return $this;
    }

    public function getVerificationToken(): ?string
    {
        return $this->verificationToken;
    }

    public function setVerificationToken(?string $verificationToken): self
    {
        $this->verificationToken = $verificationToken;

        return $this;
    }

    public function getChangePasswordToken(): ?string
    {
        return $this->changePasswordToken;
    }

    public function setChangePasswordToken(?string $changePasswordToken): self
    {
        $this->changePasswordToken = $changePasswordToken;

        return $this;
    }
}
