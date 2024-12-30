<?php

namespace App\Entity;

use App\Repository\LocalizacionRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: LocalizacionRepository::class)]
class Localizacion
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column]
    private ?int $codigo = null;

    #[ORM\Column(length: 80)]
    private ?string $nombre = null;

    #[ORM\ManyToOne(inversedBy: 'localizacions')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Provincia $provincia = null;

    #[ORM\OneToMany(targetEntity: Experiencia::class, mappedBy: 'localizacion')]
    private Collection $experiencias;

    public function __construct()
    {
        $this->experiencias = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCodigo(): ?int
    {
        return $this->codigo;
    }

    public function setCodigo(int $codigo): static
    {
        $this->codigo = $codigo;

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

    public function getProvincia(): ?Provincia
    {
        return $this->provincia;
    }

    public function setProvincia(?Provincia $provincia): static
    {
        $this->provincia = $provincia;

        return $this;
    }

    /**
     * @return Collection<int, Experiencia>
     */
    public function getExperiencias(): Collection
    {
        return $this->experiencias;
    }

    public function addExperiencia(Experiencia $experiencia): static
    {
        if (!$this->experiencias->contains($experiencia)) {
            $this->experiencias->add($experiencia);
            $experiencia->setLocalizacion($this);
        }

        return $this;
    }

    public function removeExperiencia(Experiencia $experiencia): static
    {
        if ($this->experiencias->removeElement($experiencia)) {
            // set the owning side to null (unless already changed)
            if ($experiencia->getLocalizacion() === $this) {
                $experiencia->setLocalizacion(null);
            }
        }

        return $this;
    }
}
