<?php

namespace App\Entity;

use App\Repository\UsuarioRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;

#[ORM\Entity(repositoryClass: UsuarioRepository::class)]
#[ORM\UniqueConstraint(name: 'UNIQ_IDENTIFIER_USERNAME', fields: ['username'])]
#[ORM\HasLifecycleCallbacks]
class Usuario implements UserInterface, PasswordAuthenticatedUserInterface
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(type: 'string', length: 180, unique: true)]
    private ?string $username = null;

    /**
     * @var list<string> The user roles
     */
    #[ORM\Column]
    private array $roles = [];

    /**
     * @var string The hashed password
     */
    #[ORM\Column]
    private ?string $password = null;

    #[ORM\Column(length: 128, nullable: true)]
    private ?string $email = null;

    #[ORM\Column(length: 128, nullable: true)]
    private ?string $nombre = null;

    #[ORM\Column(length: 128, nullable: true)]
    private ?string $apellido = null;

    #[ORM\Column]
    private ?\DateTimeImmutable $createdAt = null;

    #[ORM\Column]
    private ?\DateTimeImmutable $updatedAt = null;

    /**
     * @var Collection<int, Torneo>
     */
    #[ORM\OneToMany(targetEntity: Torneo::class, mappedBy: 'creador')]
    private Collection $torneosCreados;

    /**
     * @var Collection<int, Torneo>
     */
    #[ORM\ManyToMany(targetEntity: Torneo::class, mappedBy: 'colaborador')]
    private Collection $torneosColaborador;

    public function __construct()
    {
        $this->torneosCreados = new ArrayCollection();
        $this->torneosColaborador = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUsername(): ?string
    {
        return $this->username;
    }

    public function setUsername(string $username): static
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
        return (string) $this->username;
    }

    /**
     * @see UserInterface
     *
     * @return list<string>
     */
    public function getRoles(): array
    {
        $roles = $this->roles;
        // guarantee every user at least has ROLE_USER
        //$roles[] = 'ROLE_USER';

        return array_unique($roles);
    }

    /**
     * @param list<string> $roles
     */
    public function setRoles(array $roles): static
    {
        $this->roles = $roles;

        return $this;
    }

    /**
     * @see PasswordAuthenticatedUserInterface
     */
    public function getPassword(): ?string
    {
        return $this->password;
    }

    public function setPassword(string $password): static
    {
        $this->password = $password;

        return $this;
    }

    /**
     * @see UserInterface
     */
    public function eraseCredentials(): void
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(?string $email): static
    {
        $this->email = $email;

        return $this;
    }

    public function getNombre(): ?string
    {
        return $this->nombre;
    }

    public function setNombre(?string $nombre): static
    {
        $this->nombre = $nombre;

        return $this;
    }

    public function getApellido(): ?string
    {
        return $this->apellido;
    }

    public function setApellido(?string $apellido): static
    {
        $this->apellido = $apellido;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeImmutable
    {
        return $this->createdAt;
    }

    #[ORM\PrePersist]
    public function setCreatedAt(): static
    {
        $this->createdAt = new \DateTimeImmutable('now', new \DateTimeZone('America/Argentina/Buenos_Aires'));

        return $this;
    }

    public function getUpdatedAt(): ?\DateTimeImmutable
    {
        return $this->updatedAt;
    }

    #[ORM\PrePersist]
    #[ORM\PreUpdate]
    public function setUpdatedAt(): static
    {
        $this->updatedAt = new \DateTimeImmutable('now', new \DateTimeZone('America/Argentina/Buenos_Aires'));

        return $this;
    }

    /**
     * @return Collection<int, Torneo>
     */
    public function getTorneosCreados(): Collection
    {
        return $this->torneosCreados;
    }

    public function addTorneosCreado(Torneo $torneosCreado): static
    {
        if (!$this->torneosCreados->contains($torneosCreado)) {
            $this->torneosCreados->add($torneosCreado);
            $torneosCreado->setCreador($this);
        }

        return $this;
    }

    public function removeTorneosCreado(Torneo $torneosCreado): static
    {
        if ($this->torneosCreados->removeElement($torneosCreado)) {
            // set the owning side to null (unless already changed)
            if ($torneosCreado->getCreador() === $this) {
                $torneosCreado->setCreador(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Torneo>
     */
    public function getTorneosColaborador(): Collection
    {
        return $this->torneosColaborador;
    }

    public function addTorneosColaborador(Torneo $torneosColaborador): static
    {
        if (!$this->torneosColaborador->contains($torneosColaborador)) {
            $this->torneosColaborador->add($torneosColaborador);
            $torneosColaborador->addColaborador($this);
        }

        return $this;
    }

    public function removeTorneosColaborador(Torneo $torneosColaborador): static
    {
        if ($this->torneosColaborador->removeElement($torneosColaborador)) {
            $torneosColaborador->removeColaborador($this);
        }

        return $this;
    }
}
