<?php

namespace App\Entity;

use App\Repository\GrupoRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: GrupoRepository::class)]
#[ORM\HasLifecycleCallbacks]
class Grupo
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 25)]
    private ?string $nombre = null;

    /**
     * @var Collection<int, Equipo>
     */
    #[ORM\OneToMany(targetEntity: Equipo::class, mappedBy: 'grupo')]
    private Collection $equipo;

    #[ORM\Column]
    private ?int $clasificaOro = null;

    #[ORM\Column(nullable: true)]
    private ?int $clasificaPlata = null;

    #[ORM\Column(nullable: true)]
    private ?int $clasificaBronce = null;

    #[ORM\Column]
    private ?\DateTimeImmutable $createdAt = null;

    #[ORM\Column]
    private ?\DateTimeImmutable $updatedAt = null;

    #[ORM\ManyToOne(inversedBy: 'grupos')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Categoria $categoria = null;

    /**
     * @var Collection<int, Partido>
     */
    #[ORM\OneToMany(targetEntity: Partido::class, mappedBy: 'grupo')]
    private Collection $partidos;

    #[ORM\Column(length: 32)]
    private ?string $estado = null;

    public function __construct()
    {
        $this->equipo = new ArrayCollection();
        $this->partidos = new ArrayCollection();
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

    /**
     * @return Collection<int, Equipo>
     */
    public function getEquipo(): Collection
    {
        return $this->equipo;
    }

    public function addEquipo(Equipo $equipo): static
    {
        if (!$this->equipo->contains($equipo)) {
            $this->equipo->add($equipo);
            $equipo->setGrupo($this);
        }

        return $this;
    }

    public function removeEquipo(Equipo $equipo): static
    {
        if ($this->equipo->removeElement($equipo)) {
            // set the owning side to null (unless already changed)
            if ($equipo->getGrupo() === $this) {
                $equipo->setGrupo(null);
            }
        }

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

    public function getClasificaOro(): ?int
    {
        return $this->clasificaOro;
    }

    public function setClasificaOro(int $clasificaOro): static
    {
        $this->clasificaOro = $clasificaOro;

        return $this;
    }

    public function getClasificaPlata(): ?int
    {
        return $this->clasificaPlata;
    }

    public function setClasificaPlata(?int $clasificaPlata): static
    {
        $this->clasificaPlata = $clasificaPlata;

        return $this;
    }

    public function getClasificaBronce(): ?int
    {
        return $this->clasificaBronce;
    }

    public function setClasificaBronce(?int $clasificaBronce): static
    {
        $this->clasificaBronce = $clasificaBronce;

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

    /**
     * @return Collection<int, Partido>
     */
    public function getPartidos(): Collection
    {
        return $this->partidos;
    }

    public function addPartido(Partido $partido): static
    {
        if (!$this->partidos->contains($partido)) {
            $this->partidos->add($partido);
            $partido->setGrupo($this);
        }

        return $this;
    }

    public function removePartido(Partido $partido): static
    {
        if ($this->partidos->removeElement($partido)) {
            // set the owning side to null (unless already changed)
            if ($partido->getGrupo() === $this) {
                $partido->setGrupo(null);
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
