<?php

namespace App\Entity;

use App\Repository\PousseRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: PousseRepository::class)]
class Pousse
{
    #[ORM\Id]
    #[ORM\Column(length: 50)]
    private ?string $idPousse = null;

    #[ORM\Column(nullable: true)]
    private ?int $x = null;

    #[ORM\Column(nullable: true)]
    private ?int $y = null;

    #[ORM\Column(nullable: true)]
    private ?int $nbPlants = null;

    #[ORM\ManyToOne(inversedBy: 'pousses')]
    #[ORM\JoinColumn(name: 'idVariete', referencedColumnName: 'id_variete', nullable: false)]
    private ?Variete $idVariete = null;

    #[ORM\ManyToOne(inversedBy: 'pousses')]
    #[ORM\JoinColumn(name: 'idParcelle', referencedColumnName: 'id_parcelle', nullable: false)]
    private ?Parcelle $idParcelle = null;

    #[ORM\Column(type: Types::DATE_MUTABLE, nullable: true)]
    private ?\DateTime $datePlantation = null;

    public function getIdPousse(): ?string
    {
        return $this->idPousse;
    }

    public function setIdPousse(string $idPousse): static
    {
        $this->idPousse = $idPousse;

        return $this;
    }

    public function getX(): ?int
    {
        return $this->x;
    }

    public function setX(?int $x): static
    {
        $this->x = $x;

        return $this;
    }

    public function getY(): ?int
    {
        return $this->y;
    }

    public function setY(?int $y): static
    {
        $this->y = $y;

        return $this;
    }

    public function getNbPlants(): ?int
    {
        return $this->nbPlants;
    }

    public function setNbPlants(?int $nbPlants): static
    {
        $this->nbPlants = $nbPlants;

        return $this;
    }

    public function getIdVariete(): ?Variete
    {
        return $this->idVariete;
    }

    public function setIdVariete(?Variete $idVariete): static
    {
        $this->idVariete = $idVariete;

        return $this;
    }

    public function getIdParcelle(): ?Parcelle
    {
        return $this->idParcelle;
    }

    public function setIdParcelle(?Parcelle $idParcelle): static
    {
        $this->idParcelle = $idParcelle;

        return $this;
    }

    public function getDatePlantation(): ?\DateTime
    {
        return $this->datePlantation;
    }

    public function setDatePlantation(?\DateTime $datePlantation): static
    {
        $this->datePlantation = $datePlantation;

        return $this;
    }
}
