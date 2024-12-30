<?php

namespace App\Entity;

use App\Repository\ImagenRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ImagenRepository::class)]
class Imagen
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(type: Types::BLOB)]
    private $nombre = null;

    #[ORM\ManyToOne(inversedBy: 'imagens')]
    private ?Experiencia $experiencia = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNombre()
    {
        return $this->nombre;
    }

    public function setNombre($nombre): static
    {
        $this->nombre = $nombre;

        return $this;
    }

    public function getExperiencia(): ?Experiencia
    {
        return $this->experiencia;
    }

    public function setExperiencia(?Experiencia $experiencia): static
    {
        $this->experiencia = $experiencia;

        return $this;
    }
}
