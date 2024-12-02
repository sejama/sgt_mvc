<?php

namespace App\Entity;

use App\Repository\JugadorRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: JugadorRepository::class)]
#[ORM\HasLifecycleCallbacks]
class Jugador
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 128)]
    private ?string $nombre = null;

    #[ORM\Column(length: 128)]
    private ?string $apellido = null;

    #[ORM\Column(length: 12)]
    private ?string $tipoDocumento = null;

    #[ORM\Column(length: 15)]
    private ?string $numeroDocumento = null;

    #[ORM\Column(nullable: true)]
    private ?\DateTimeImmutable $nacimiento = null;

    #[ORM\ManyToOne(inversedBy: 'jugadores')]
    private ?Equipo $equipo = null;

    #[ORM\Column]
    private ?bool $responsable = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $email = null;

    #[ORM\Column(length: 32, nullable: true)]
    private ?string $celular = null;

    #[ORM\Column]
    private ?\DateTimeImmutable $createdAt = null;

    #[ORM\Column]
    private ?\DateTimeImmutable $updatedAt = null;

    #[ORM\Column(length: 64)]
    private ?string $tipo = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNombre(): ?string
    {
        return $this->nombre;
    }

    public function setNombre(string $nombre): static
    {
        $this->nombre = $nombre;

        return $this;
    }

    public function getApellido(): ?string
    {
        return $this->apellido;
    }

    public function setApellido(string $apellido): static
    {
        $this->apellido = $apellido;

        return $this;
    }

    public function getTipoDocumento(): ?string
    {
        return $this->tipoDocumento;
    }

    public function setTipoDocumento(string $tipoDocumento): static
    {
        $this->tipoDocumento = $tipoDocumento;

        return $this;
    }

    public function getNumeroDocumento(): ?string
    {
        return $this->numeroDocumento;
    }

    public function setNumeroDocumento(string $numeroDocumento): static
    {
        $this->numeroDocumento = $numeroDocumento;

        return $this;
    }

    public function getNacimiento(): ?\DateTimeImmutable
    {
        return $this->nacimiento;
    }

    public function setNacimiento(\DateTimeImmutable $nacimiento): static
    {
        $this->nacimiento = $nacimiento;

        return $this;
    }

    public function getEquipo(): ?Equipo
    {
        return $this->equipo;
    }

    public function setEquipo(?Equipo $equipo): static
    {
        $this->equipo = $equipo;

        return $this;
    }

    public function isResponsable(): ?bool
    {
        return $this->responsable;
    }

    public function setResponsable(bool $responsable): static
    {
        $this->responsable = $responsable;

        return $this;
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

    public function getCelular(): ?string
    {
        return $this->celular;
    }

    public function setCelular(?string $celular): static
    {
        $this->celular = $celular;

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

    public function getTipo(): ?string
    {
        return $this->tipo;
    }

    public function setTipo(string $tipo): static
    {
        $this->tipo = $tipo;

        return $this;
    }
}
