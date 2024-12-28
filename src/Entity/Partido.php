<?php

namespace App\Entity;

use App\Repository\PartidoRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: PartidoRepository::class)]
#[ORM\HasLifecycleCallbacks]
class Partido
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'partidos')]
    private ?Cancha $cancha = null;

    #[ORM\Column(nullable: true)]
    private ?\DateTimeImmutable $horario = null;

    #[ORM\ManyToOne(inversedBy: 'partidos')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Grupo $grupo = null;

    #[ORM\ManyToOne(inversedBy: 'partidosLocal')]
    #[ORM\JoinColumn(nullable: true)]
    private ?Equipo $equipoLocal = null;

    #[ORM\Column(type: Types::SMALLINT, nullable: true)]
    private ?int $localSet1 = null;

    #[ORM\Column(type: Types::SMALLINT, nullable: true)]
    private ?int $localSet2 = null;

    #[ORM\Column(type: Types::SMALLINT, nullable: true)]
    private ?int $localSet3 = null;

    #[ORM\Column(type: Types::SMALLINT, nullable: true)]
    private ?int $localSet4 = null;

    #[ORM\Column(type: Types::SMALLINT, nullable: true)]
    private ?int $localSet5 = null;

    #[ORM\ManyToOne(inversedBy: 'partidosVisitante')]
    #[ORM\JoinColumn(nullable: true)]
    private ?Equipo $equipoVisitante = null;

    #[ORM\Column(type: Types::SMALLINT, nullable: true)]
    private ?int $visitanteSet1 = null;

    #[ORM\Column(type: Types::SMALLINT, nullable: true)]
    private ?int $visitanteSet2 = null;

    #[ORM\Column(type: Types::SMALLINT, nullable: true)]
    private ?int $visitanteSet3 = null;

    #[ORM\Column(type: Types::SMALLINT, nullable: true)]
    private ?int $visitanteSet4 = null;

    #[ORM\Column(type: Types::SMALLINT, nullable: true)]
    private ?int $visitanteSet5 = null;

    #[ORM\Column]
    private ?\DateTimeImmutable $createdAt = null;

    #[ORM\Column]
    private ?\DateTimeImmutable $updatedAt = null;

    #[ORM\Column(length: 32)]
    private ?string $estado = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCancha(): ?Cancha
    {
        return $this->cancha;
    }

    public function setCancha(?Cancha $cancha): static
    {
        $this->cancha = $cancha;

        return $this;
    }

    public function getHorario(): ?\DateTimeImmutable
    {
        return $this->horario;
    }

    public function setHorario(?\DateTimeImmutable $horario): static
    {
        $this->horario = $horario;

        return $this;
    }

    public function getGrupo(): ?Grupo
    {
        return $this->grupo;
    }

    public function setGrupo(?Grupo $grupo): static
    {
        $this->grupo = $grupo;

        return $this;
    }

    public function getEquipoLocal(): ?Equipo
    {
        return $this->equipoLocal;
    }

    public function setEquipoLocal(?Equipo $equipoLocal): static
    {
        $this->equipoLocal = $equipoLocal;

        return $this;
    }

    public function getEquipoVisitante(): ?Equipo
    {
        return $this->equipoVisitante;
    }

    public function setEquipoVisitante(?Equipo $equipoVisitante): static
    {
        $this->equipoVisitante = $equipoVisitante;

        return $this;
    }

    public function getLocalSet1(): ?int
    {
        return $this->localSet1;
    }

    public function setLocalSet1(?int $localSet1): static
    {
        $this->localSet1 = $localSet1;

        return $this;
    }

    public function getLocalSet2(): ?int
    {
        return $this->localSet2;
    }

    public function setLocalSet2(?int $localSet2): static
    {
        $this->localSet2 = $localSet2;

        return $this;
    }

    public function getLocalSet3(): ?int
    {
        return $this->localSet3;
    }

    public function setLocalSet3(?int $localSet3): static
    {
        $this->localSet3 = $localSet3;

        return $this;
    }

    public function getLocalSet4(): ?int
    {
        return $this->localSet4;
    }

    public function setLocalSet4(?int $localSet4): static
    {
        $this->localSet4 = $localSet4;

        return $this;
    }

    public function getLocalSet5(): ?int
    {
        return $this->localSet5;
    }

    public function setLocalSet5(?int $localSet5): static
    {
        $this->localSet5 = $localSet5;

        return $this;
    }

    public function getVisitanteSet1(): ?int
    {
        return $this->visitanteSet1;
    }

    public function setVisitanteSet1(?int $visitanteSet1): static
    {
        $this->visitanteSet1 = $visitanteSet1;

        return $this;
    }

    public function getVisitanteSet2(): ?int
    {
        return $this->visitanteSet2;
    }

    public function setVisitanteSet2(?int $visitanteSet2): static
    {
        $this->visitanteSet2 = $visitanteSet2;

        return $this;
    }

    public function getVisitanteSet3(): ?int
    {
        return $this->visitanteSet3;
    }

    public function setVisitanteSet3(?int $visitanteSet3): static
    {
        $this->visitanteSet3 = $visitanteSet3;

        return $this;
    }

    public function getVisitanteSet4(): ?int
    {
        return $this->visitanteSet4;
    }

    public function setVisitanteSet4(?int $visitanteSet4): static
    {
        $this->visitanteSet4 = $visitanteSet4;

        return $this;
    }

    public function getVisitanteSet5(): ?int
    {
        return $this->visitanteSet5;
    }

    public function setVisitanteSet5(?int $visitanteSet5): static
    {
        $this->visitanteSet5 = $visitanteSet5;

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

    public function getEstado(): ?string
    {
        return $this->estado;
    }

    public function setEstado(string $estado): static
    {
        $this->estado = $estado;

        return $this;
    }
}
