<?php

namespace App\Entity;

use App\Repository\PartidoConfigRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: PartidoConfigRepository::class)]
#[ORM\HasLifecycleCallbacks]
class PartidoConfig
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\OneToOne(inversedBy: 'partidoConfig', cascade: ['persist', 'remove'])]
    #[ORM\JoinColumn(nullable: false)]
    private ?Partido $partido = null;

    #[ORM\OneToOne(cascade: ['persist', 'remove'])]
    private ?Grupo $grupoEquipo1 = null;

    #[ORM\Column(type: Types::SMALLINT, nullable: true)]
    private ?int $posicionEquipo1 = null;

    #[ORM\OneToOne(cascade: ['persist', 'remove'])]
    private ?grupo $grupoEquipo2 = null;

    #[ORM\Column(type: Types::SMALLINT, nullable: true)]
    private ?int $posicionEquipo2 = null;

    #[ORM\OneToOne(cascade: ['persist', 'remove'])]
    private ?Partido $ganadorPartido1 = null;

    #[ORM\OneToOne(cascade: ['persist', 'remove'])]
    private ?Partido $ganadorPartido2 = null;

    #[ORM\Column(length: 64)]
    private ?string $nombre = null;

    #[ORM\Column]
    private ?\DateTimeImmutable $createdAt = null;

    #[ORM\Column]
    private ?\DateTimeImmutable $updatedAt = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getPartido(): ?Partido
    {
        return $this->partido;
    }

    public function setPartido(Partido $partido): static
    {
        $this->partido = $partido;

        return $this;
    }

    public function getGrupoEquipo1(): ?Grupo
    {
        return $this->grupoEquipo1;
    }

    public function setGrupoEquipo1(?Grupo $grupoEquipo1): static
    {
        $this->grupoEquipo1 = $grupoEquipo1;

        return $this;
    }

    public function getPosicionEquipo1(): ?int
    {
        return $this->posicionEquipo1;
    }

    public function setPosicionEquipo1(?int $posicionEquipo1): static
    {
        $this->posicionEquipo1 = $posicionEquipo1;

        return $this;
    }

    public function getGrupoEquipo2(): ?grupo
    {
        return $this->grupoEquipo2;
    }

    public function setGrupoEquipo2(?grupo $grupoEquipo2): static
    {
        $this->grupoEquipo2 = $grupoEquipo2;

        return $this;
    }

    public function getPosicionEquipo2(): ?int
    {
        return $this->posicionEquipo2;
    }

    public function setPosicionEquipo2(?int $posicionEquipo2): static
    {
        $this->posicionEquipo2 = $posicionEquipo2;

        return $this;
    }

    public function getGanadorPartido1(): ?Partido
    {
        return $this->ganadorPartido1;
    }

    public function setGanadorPartido1(?Partido $ganadorPartido1): static
    {
        $this->ganadorPartido1 = $ganadorPartido1;

        return $this;
    }

    public function getGanadorPartido2(): ?Partido
    {
        return $this->ganadorPartido2;
    }

    public function setGanadorPartido2(?Partido $ganadorPartido2): static
    {
        $this->ganadorPartido2 = $ganadorPartido2;

        return $this;
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
}
