<?php

namespace App\Entity;

use App\Repository\ComunidadRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ComunidadRepository::class)]
class Comunidad
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column]
    private ?int $codigo = null;

    #[ORM\Column(length: 80)]
    private ?string $nombre = null;

    #[ORM\OneToMany(targetEntity: Provincia::class, mappedBy: 'comunidad')]
    private Collection $provincias;

    public function __construct()
    {
        $this->provincias = new ArrayCollection();
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

    /**
     * @return Collection<int, Provincia>
     */
    public function getProvincias(): Collection
    {
        return $this->provincias;
    }

    public function addProvincia(Provincia $provincia): static
    {
        if (!$this->provincias->contains($provincia)) {
            $this->provincias->add($provincia);
            $provincia->setComunidad($this);
        }

        return $this;
    }

    public function removeProvincia(Provincia $provincia): static
    {
        if ($this->provincias->removeElement($provincia)) {
            // set the owning side to null (unless already changed)
            if ($provincia->getComunidad() === $this) {
                $provincia->setComunidad(null);
            }
        }

        return $this;
    }
}
