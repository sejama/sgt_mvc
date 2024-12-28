<?php

namespace App\Entity;

use App\Repository\EquipoRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: EquipoRepository::class)]
#[ORM\HasLifecycleCallbacks]
class Equipo
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 128)]
    private ?string $nombre = null;

    #[ORM\Column(length: 16)]
    private ?string $nombreCorto = null;

    #[ORM\Column(length: 128, nullable: true)]
    private ?string $pais = null;

    #[ORM\Column(length: 128, nullable: true)]
    private ?string $provincia = null;

    #[ORM\Column(length: 128, nullable: true)]
    private ?string $localidad = null;

    /**
     * @var Collection<int, Jugador>
     */
    #[ORM\OneToMany(targetEntity: Jugador::class, mappedBy: 'equipo')]
    private Collection $jugadores;

    #[ORM\ManyToOne(inversedBy: 'equipos')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Categoria $categoria = null;

    public function __construct()
    {
        $this->jugadores = new ArrayCollection();
        $this->partidosLocal = new ArrayCollection();
        $this->partidosVisitante = new ArrayCollection();
    }

    #[ORM\Column]
    private ?\DateTimeImmutable $createdAt = null;

    #[ORM\Column]
    private ?\DateTimeImmutable $updatedAt = null;

    #[ORM\ManyToOne(inversedBy: 'equipo')]
    private ?Grupo $grupo = null;

    /**
     * @var Collection<int, Partido>
     */
    #[ORM\OneToMany(targetEntity: Partido::class, mappedBy: 'equipoLocal')]
    private Collection $partidosLocal;

    /**
     * @var Collection<int, Partido>
     */
    #[ORM\OneToMany(targetEntity: Partido::class, mappedBy: 'equipoVisitante')]
    private Collection $partidosVisitante;

    #[ORM\Column(length: 32)]
    private ?string $estado = null;

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

    public function getNombreCorto(): ?string
    {
        return $this->nombreCorto;
    }

    public function setNombreCorto(string $nombreCorto): static
    {
        $this->nombreCorto = $nombreCorto;

        return $this;
    }

    public function getPais(): ?string
    {
        return $this->pais;
    }

    public function setPais(?string $pais): static
    {
        $this->pais = $pais;

        return $this;
    }

    public function getProvincia(): ?string
    {
        return $this->provincia;
    }

    public function setProvincia(?string $provincia): static
    {
        $this->provincia = $provincia;

        return $this;
    }

    public function getLocalidad(): ?string
    {
        return $this->localidad;
    }

    public function setLocalidad(?string $localidad): static
    {
        $this->localidad = $localidad;

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
     * @return Collection<int, Jugador>
     */
    public function getJugadores(): Collection
    {
        return $this->jugadores;
    }

    public function addJugadore(Jugador $jugadore): static
    {
        if (!$this->jugadores->contains($jugadore)) {
            $this->jugadores->add($jugadore);
            $jugadore->setEquipo($this);
        }

        return $this;
    }

    public function removeJugadore(Jugador $jugadore): static
    {
        if ($this->jugadores->removeElement($jugadore)) {
            // set the owning side to null (unless already changed)
            if ($jugadore->getEquipo() === $this) {
                $jugadore->setEquipo(null);
            }
        }

        return $this;
    }

    public function getCategoria(): ?Categoria
    {
        return $this->categoria;
    }

    public function setCategoria(?Categoria $categoria): static
    {
        $this->categoria = $categoria;

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

    /**
     * @return Collection<int, Partido>
     */
    public function getPartidosLocal(): Collection
    {
        return $this->partidosLocal;
    }

    public function addPartidosLocal(Partido $partidosLocal): static
    {
        if (!$this->partidosLocal->contains($partidosLocal)) {
            $this->partidosLocal->add($partidosLocal);
            $partidosLocal->setEquipoLocal($this);
        }

        return $this;
    }

    public function removePartidosLocal(Partido $partidosLocal): static
    {
        if ($this->partidosLocal->removeElement($partidosLocal)) {
            // set the owning side to null (unless already changed)
            if ($partidosLocal->getEquipoLocal() === $this) {
                $partidosLocal->setEquipoLocal(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Partido>
     */
    public function getPartidosVisitante(): Collection
    {
        return $this->partidosVisitante;
    }

    public function addPartidosVisitante(Partido $partidosVisitante): static
    {
        if (!$this->partidosVisitante->contains($partidosVisitante)) {
            $this->partidosVisitante->add($partidosVisitante);
            $partidosVisitante->setEquipoVisitante($this);
        }

        return $this;
    }

    public function removePartidosVisitante(Partido $partidosVisitante): static
    {
        if ($this->partidosVisitante->removeElement($partidosVisitante)) {
            // set the owning side to null (unless already changed)
            if ($partidosVisitante->getEquipoVisitante() === $this) {
                $partidosVisitante->setEquipoVisitante(null);
            }
        }

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
