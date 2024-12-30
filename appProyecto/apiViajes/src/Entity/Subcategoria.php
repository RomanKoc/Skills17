<?php

namespace App\Entity;

use App\Repository\SubcategoriaRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: SubcategoriaRepository::class)]
class Subcategoria
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 30)]
    private ?string $nombre = null;

    #[ORM\ManyToOne(inversedBy: 'subcategorias')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Categoria $categoria = null;

    #[ORM\OneToMany(targetEntity: Experiencia::class, mappedBy: 'subcategoria')]
    private Collection $experiencias;

    public function __construct()
    {
        $this->experiencias = new ArrayCollection();
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
            $experiencia->setSubcategoria($this);
        }

        return $this;
    }

    public function removeExperiencia(Experiencia $experiencia): static
    {
        if ($this->experiencias->removeElement($experiencia)) {
            // set the owning side to null (unless already changed)
            if ($experiencia->getSubcategoria() === $this) {
                $experiencia->setSubcategoria(null);
            }
        }

        return $this;
    }
}
