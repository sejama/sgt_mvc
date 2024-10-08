<?php

namespace App\Entity;

use App\Repository\SedeRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: SedeRepository::class)]
#[ORM\HasLifecycleCallbacks]
class Sede
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $nombre = null;

    #[ORM\Column(length: 255)]
    private ?string $domicilio = null;

    #[ORM\ManyToOne(inversedBy: 'sedes')]
    private ?Torneo $torneo = null;

    #[ORM\Column]
    private ?\DateTimeImmutable $createdAt = null;

    #[ORM\Column]
    private ?\DateTimeImmutable $updatedAt = null;

    /**
     * @var Collection<int, Cancha>
     */
    #[ORM\OneToMany(targetEntity: Cancha::class, mappedBy: 'sede')]
    private Collection $canchas;

    public function __construct()
    {
        $this->canchas = new ArrayCollection();
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

    public function getDomicilio(): ?string
    {
        return $this->domicilio;
    }

    public function setDomicilio(string $domicilio): static
    {
        $this->domicilio = $domicilio;

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
        $this->createdAt = new \DateTimeImmutable('now');

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
        $this->updatedAt = new \DateTimeImmutable('now');

        return $this;
    }

    /**
     * @return Collection<int, Cancha>
     */
    public function getCanchas(): Collection
    {
        return $this->canchas;
    }

    public function addCancha(Cancha $cancha): static
    {
        if (!$this->canchas->contains($cancha)) {
            $this->canchas->add($cancha);
            $cancha->setSede($this);
        }

        return $this;
    }

    public function removeCancha(Cancha $cancha): static
    {
        if ($this->canchas->removeElement($cancha)) {
            // set the owning side to null (unless already changed)
            if ($cancha->getSede() === $this) {
                $cancha->setSede(null);
            }
        }

        return $this;
    }
}
