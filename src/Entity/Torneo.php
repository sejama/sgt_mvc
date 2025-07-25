<?php

namespace App\Entity;

use App\Repository\TorneoRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

#[ORM\Entity(repositoryClass: TorneoRepository::class)]
#[ORM\HasLifecycleCallbacks]
#[UniqueEntity(fields: ['nombre'], message: 'Ya existe un torneo con ese nombre.')]
#[UniqueEntity(fields: ['ruta'], message: 'Ya existe un torneo con esa ruta.')]
class Torneo
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255, unique: true)]
    private ?string $nombre = null;

    #[ORM\Column(length: 32, unique: true)]
    private ?string $ruta = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $descripcion = null;

    #[ORM\Column]
    private ?\DateTimeImmutable $fechaInicioInscripcion = null;

    #[ORM\Column]
    private ?\DateTimeImmutable $fechaFinInscripcion = null;

    #[ORM\Column]
    private ?\DateTimeImmutable $fechaInicioTorneo = null;

    #[ORM\Column]
    private ?\DateTimeImmutable $fechaFinTorneo = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $reglamento = null;

    #[ORM\ManyToOne(inversedBy: 'torneosCreados')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Usuario $creador = null;

    /**
     * @var Collection<int, Usuario>
     */
    #[ORM\ManyToMany(targetEntity: Usuario::class, inversedBy: 'torneosColaborador')]
    private Collection $colaborador;

    #[ORM\Column]
    private ?\DateTimeImmutable $createdAt = null;

    #[ORM\Column]
    private ?\DateTimeImmutable $updatedAt = null;

    /**
     * @var Collection<int, Categoria>
     */
    #[ORM\OneToMany(targetEntity: Categoria::class, mappedBy: 'torneo')]
    private Collection $categorias;

    /**
     * @var Collection<int, Sede>
     */
    #[ORM\OneToMany(targetEntity: Sede::class, mappedBy: 'torneo')]
    private Collection $sedes;

    #[ORM\Column(length: 32)]
    private ?string $estado = null;

    public function __construct()
    {
        $this->categorias = new ArrayCollection();
        $this->sedes = new ArrayCollection();
        $this->colaborador = new ArrayCollection();
    }

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

    public function getRuta(): ?string
    {
        return $this->ruta;
    }

    public function setRuta(string $ruta): static
    {
        $this->ruta = $ruta;

        return $this;
    }

    public function getFechaInicioInscripcion(): ?\DateTimeImmutable
    {
        return $this->fechaInicioInscripcion;
    }

    public function setFechaInicioInscripcion(\DateTimeImmutable $fechaInicioInscripcion): static
    {
        $this->fechaInicioInscripcion = $fechaInicioInscripcion;

        return $this;
    }

    public function getFechaFinInscripcion(): ?\DateTimeImmutable
    {
        return $this->fechaFinInscripcion;
    }

    public function setFechaFinInscripcion(\DateTimeImmutable $fechaFinInscripcion): static
    {
        $this->fechaFinInscripcion = $fechaFinInscripcion;

        return $this;
    }

    public function getFechaInicioTorneo(): ?\DateTimeImmutable
    {
        return $this->fechaInicioTorneo;
    }

    public function setFechaInicioTorneo(\DateTimeImmutable $fechaInicioTorneo): static
    {
        $this->fechaInicioTorneo = $fechaInicioTorneo;

        return $this;
    }

    public function getFechaFinTorneo(): ?\DateTimeImmutable
    {
        return $this->fechaFinTorneo;
    }

    public function setFechaFinTorneo(\DateTimeImmutable $fechaFinTorneo): static
    {
        $this->fechaFinTorneo = $fechaFinTorneo;

        return $this;
    }

    public function getReglamento(): ?string
    {
        return $this->reglamento;
    }

    public function setReglamento(?string $reglamento): static
    {
        $this->reglamento = $reglamento;

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
     * @return Collection<int, Categoria>
     */
    public function getCategorias(): Collection
    {
        return $this->categorias;
    }

    public function addCategoria(Categoria $categoria): static
    {
        if (!$this->categorias->contains($categoria)) {
            $this->categorias->add($categoria);
            $categoria->setTorneo($this);
        }

        return $this;
    }

    public function removeCategoria(Categoria $categoria): static
    {
        if ($this->categorias->removeElement($categoria)) {
            // set the owning side to null (unless already changed)
            if ($categoria->getTorneo() === $this) {
                $categoria->setTorneo(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Sede>
     */
    public function getSedes(): Collection
    {
        return $this->sedes;
    }

    public function addSede(Sede $sede): static
    {
        if (!$this->sedes->contains($sede)) {
            $this->sedes->add($sede);
            $sede->setTorneo($this);
        }

        return $this;
    }

    public function removeSede(Sede $sede): static
    {
        if ($this->sedes->removeElement($sede)) {
            // set the owning side to null (unless already changed)
            if ($sede->getTorneo() === $this) {
                $sede->setTorneo(null);
            }
        }

        return $this;
    }

    public function getCreador(): ?Usuario
    {
        return $this->creador;
    }

    public function setCreador(?Usuario $creador): static
    {
        $this->creador = $creador;

        return $this;
    }

    /**
     * @return Collection<int, Usuario>
     */
    public function getColaborador(): Collection
    {
        return $this->colaborador;
    }

    public function addColaborador(Usuario $colaborador): static
    {
        if (!$this->colaborador->contains($colaborador)) {
            $this->colaborador->add($colaborador);
        }

        return $this;
    }

    public function removeColaborador(Usuario $colaborador): static
    {
        $this->colaborador->removeElement($colaborador);

        return $this;
    }

    public function getDescripcion(): ?string
    {
        return $this->descripcion;
    }

    public function setDescripcion(?string $descripcion): static
    {
        $this->descripcion = $descripcion;

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
