<?php

namespace App\Entity;

use App\Enum\Genero;
use App\Repository\CategoriaRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

#[ORM\Entity(repositoryClass: CategoriaRepository::class)]
#[ORM\HasLifecycleCallbacks]
#[UniqueEntity(
    fields: ['nombre', 'genero', 'torneo', 'nombreCorto'],
    message: 'Ya existe esa categoría para este torneo.'
)]
#[UniqueEntity(
    fields: ['nombreCorto', 'torneo'],
    message: 'Ya existe una categoría con ese nombre corto.'
)]
class Categoria
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 128)]
    private ?string $nombre = null;

    #[ORM\Column(length: 255, type: "string", enumType: Genero::class)]
    private ?Genero $genero = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $disputa = null;

    #[ORM\ManyToOne(inversedBy: 'categorias')]
    private ?Torneo $torneo = null;

    #[ORM\Column]
    private ?\DateTimeImmutable $createdAt = null;

    #[ORM\Column]
    private ?\DateTimeImmutable $updatedAt = null;

    #[ORM\Column(length: 8)]
    private ?string $nombreCorto = null;

    /**
     * @var Collection<int, Equipo>
     */
    #[ORM\OneToMany(targetEntity: Equipo::class, mappedBy: 'categoria')]
    private Collection $equipos;

    public function __construct()
    {
        $this->equipos = new ArrayCollection();
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

    public function getGenero(): ?Genero
    {
        return $this->genero;
    }

    public function setGenero(Genero $genero): static
    {
        $this->genero = $genero;

        return $this;
    }

    public function getDisputa(): ?string
    {
        return $this->disputa;
    }

    public function setDisputa(?string $disputa): static
    {
        $this->disputa = $disputa;

        return $this;
    }

    public function getTorneo(): ?Torneo
    {
        return $this->torneo;
    }

    public function setTorneo(?Torneo $torneo): static
    {
        $this->torneo = $torneo;

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

    public function getNombreCorto(): ?string
    {
        return $this->nombreCorto;
    }

    public function setNombreCorto(string $nombreCorto): static
    {
        $this->nombreCorto = $nombreCorto;

        return $this;
    }

    /**
     * @return Collection<int, Equipo>
     */
    public function getEquipos(): Collection
    {
        return $this->equipos;
    }

    public function addEquipo(Equipo $equipo): static
    {
        if (!$this->equipos->contains($equipo)) {
            $this->equipos->add($equipo);
            $equipo->setCategoria($this);
        }

        return $this;
    }

    public function removeEquipo(Equipo $equipo): static
    {
        if ($this->equipos->removeElement($equipo)) {
            // set the owning side to null (unless already changed)
            if ($equipo->getCategoria() === $this) {
                $equipo->setCategoria(null);
            }
        }

        return $this;
    }
}
