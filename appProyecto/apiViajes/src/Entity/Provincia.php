<?php

namespace App\Entity;

use App\Repository\ProvinciaRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ProvinciaRepository::class)]
class Provincia
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column]
    private ?int $codigo = null;

    #[ORM\Column(length: 80)]
    private ?string $nombre = null;

    #[ORM\ManyToOne(inversedBy: 'provincias')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Comunidad $comunidad = null;

    #[ORM\OneToMany(targetEntity: Localizacion::class, mappedBy: 'provincia')]
    private Collection $localizacions;

    public function __construct()
    {
        $this->localizacions = new ArrayCollection();
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

    public function getComunidad(): ?Comunidad
    {
        return $this->comunidad;
    }

    public function setComunidad(?Comunidad $comunidad): static
    {
        $this->comunidad = $comunidad;

        return $this;
    }

    /**
     * @return Collection<int, Localizacion>
     */
    public function getLocalizacions(): Collection
    {
        return $this->localizacions;
    }

    public function addLocalizacion(Localizacion $localizacion): static
    {
        if (!$this->localizacions->contains($localizacion)) {
            $this->localizacions->add($localizacion);
            $localizacion->setProvincia($this);
        }

        return $this;
    }

    public function removeLocalizacion(Localizacion $localizacion): static
    {
        if ($this->localizacions->removeElement($localizacion)) {
            // set the owning side to null (unless already changed)
            if ($localizacion->getProvincia() === $this) {
                $localizacion->setProvincia(null);
            }
        }

        return $this;
    }
}
