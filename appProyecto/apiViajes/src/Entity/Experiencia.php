<?php
namespace App\Entity;

use App\Repository\ExperienciaRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ExperienciaRepository::class)]
class Experiencia
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 40)]
    private ?string $titulo = null;

    #[ORM\Column(length: 255)]
    private ?string $texto = null;

    #[ORM\Column(nullable: true)]
    private ?int $puntuacion = null;

    #[ORM\Column(type: Types::DATE_MUTABLE, nullable: true)]
    private ?\DateTimeInterface $fecha = null;

    #[ORM\ManyToOne(inversedBy: 'experiencias')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Usuario $usuario = null;

    #[ORM\ManyToOne(inversedBy: 'experiencias')]
    private ?Localizacion $localizacion = null;

    #[ORM\OneToMany(targetEntity: Imagen::class, mappedBy: 'experiencia')]
    private Collection $imagen;

    #[ORM\ManyToOne(inversedBy: 'experiencias')]
    private ?Subcategoria $subcategoria = null;

    #[ORM\OneToMany(targetEntity: Comentario::class, mappedBy: 'experiencia')]
    private Collection $comentarios;

    #[ORM\OneToMany(targetEntity: Imagen::class, mappedBy: 'experiencia')]
    private Collection $imagens;

    public function __construct()
    {
        $this->imagen = new ArrayCollection();
        $this->comentarios = new ArrayCollection();
        $this->imagens = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitulo(): ?string
    {
        return $this->titulo;
    }

    public function setTitulo(string $titulo): static
    {
        $this->titulo = $titulo;

        return $this;
    }

    public function getTexto(): ?string
    {
        return $this->texto;
    }

    public function setTexto(string $texto): static
    {
        $this->texto = $texto;

        return $this;
    }

    public function getPuntuacion(): ?int
    {
        return $this->puntuacion;
    }

    public function setPuntuacion(?int $puntuacion): static
    {
        $this->puntuacion = $puntuacion;

        return $this;
    }

    public function getFecha(): ?\DateTimeInterface
    {
        return $this->fecha;
    }

    public function setFecha(?\DateTimeInterface $fecha): static
    {
        $this->fecha = $fecha;

        return $this;
    }

    public function getUsuario(): ?Usuario
    {
        return $this->usuario;
    }

    public function setUsuario(?Usuario $usuario): static
    {
        $this->usuario = $usuario;

        return $this;
    }

    public function getLocalizacion(): ?Localizacion
    {
        return $this->localizacion;
    }

    public function setLocalizacion(?Localizacion $localizacion): static
    {
        $this->localizacion = $localizacion;

        return $this;
    }

    /**
     * @return Collection<int, Imagen>
     */
    public function getImagen(): Collection
    {
        return $this->imagen;
    }

    public function addImagen(Imagen $imagen): static
    {
        if (!$this->imagen->contains($imagen)) {
            $this->imagen->add($imagen);
            $imagen->setExperiencia($this);
        }

        return $this;
    }

    public function removeImagen(Imagen $imagen): static
    {
        if ($this->imagen->removeElement($imagen)) {
            // set the owning side to null (unless already changed)
            if ($imagen->getExperiencia() === $this) {
                $imagen->setExperiencia(null);
            }
        }

        return $this;
    }

    public function getSubcategoria(): ?Subcategoria
    {
        return $this->subcategoria;
    }

    public function setSubcategoria(?Subcategoria $subcategoria): static
    {
        $this->subcategoria = $subcategoria;

        return $this;
    }

    /**
     * @return Collection<int, Comentario>
     */
    public function getComentarios(): Collection
    {
        return $this->comentarios;
    }

    public function addComentario(Comentario $comentario): static
    {
        if (!$this->comentarios->contains($comentario)) {
            $this->comentarios->add($comentario);
            $comentario->setExperiencia($this);
        }

        return $this;
    }

    public function removeComentario(Comentario $comentario): static
    {
        if ($this->comentarios->removeElement($comentario)) {
            // set the owning side to null (unless already changed)
            if ($comentario->getExperiencia() === $this) {
                $comentario->setExperiencia(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Imagen>
     */
    public function getImagens(): Collection
    {
        return $this->imagens;
    }
}
