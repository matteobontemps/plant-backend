<?php

namespace App\Entity;

use App\Repository\VarieteRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: VarieteRepository::class)]
class Variete
{
    #[ORM\Id]
    #[ORM\Column(length: 50)]
    private ?string $idVariete = null;

    #[ORM\Column(length: 255)]
    private ?string $libelle = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $description = null;

    #[ORM\Column(nullable: true)]
    private ?int $nbGraines = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $ensoleillement = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $frequenceArrosage = null;

    #[ORM\Column(type: Types::DATE_MUTABLE, nullable: true)]
    private ?\DateTime $dateDebutPeriodePlantation = null;

    #[ORM\Column(type: Types::DATE_MUTABLE, nullable: true)]
    private ?\DateTime $dateFinPeriodePlantation = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $resistanceFroid = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $tempsAvantRecolte = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $ph = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $image = null;

    #[ORM\ManyToOne(inversedBy: 'varietes')]
    #[ORM\JoinColumn(name: 'idPlante', referencedColumnName: 'id_plante', nullable: false)]
    private ?Plante $idPlante = null;

    /**
     * @var Collection<int, Pousse>
     */
    #[ORM\OneToMany(targetEntity: Pousse::class, mappedBy: 'idVariete')]
    private Collection $pousses;

    public function __construct()
    {
        $this->pousses = new ArrayCollection();
    }

    public function getIdVariete(): ?string
    {
        return $this->idVariete;
    }

    public function setIdVariete(string $idVariete): static
    {
        $this->idVariete = $idVariete;

        return $this;
    }

    public function getLibelle(): ?string
    {
        return $this->libelle;
    }

    public function setLibelle(string $libelle): static
    {
        $this->libelle = $libelle;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): static
    {
        $this->description = $description;

        return $this;
    }

    public function getNbGraines(): ?int
    {
        return $this->nbGraines;
    }

    public function setNbGraines(?int $nbGraines): static
    {
        $this->nbGraines = $nbGraines;

        return $this;
    }

    public function getEnsoleillement(): ?string
    {
        return $this->ensoleillement;
    }

    public function setEnsoleillement(?string $ensoleillement): static
    {
        $this->ensoleillement = $ensoleillement;

        return $this;
    }

    public function getFrequenceArrosage(): ?string
    {
        return $this->frequenceArrosage;
    }

    public function setFrequenceArrosage(?string $frequenceArrosage): static
    {
        $this->frequenceArrosage = $frequenceArrosage;

        return $this;
    }

    public function getDateDebutPeriodePlantation(): ?\DateTime
    {
        return $this->dateDebutPeriodePlantation;
    }

    public function setDateDebutPeriodePlantation(?\DateTime $dateDebutPeriodePlantation): static
    {
        $this->dateDebutPeriodePlantation = $dateDebutPeriodePlantation;

        return $this;
    }

    public function getDateFinPeriodePlantation(): ?\DateTime
    {
        return $this->dateFinPeriodePlantation;
    }

    public function setDateFinPeriodePlantation(?\DateTime $dateFinPeriodePlantation): static
    {
        $this->dateFinPeriodePlantation = $dateFinPeriodePlantation;

        return $this;
    }

    public function getResistanceFroid(): ?string
    {
        return $this->resistanceFroid;
    }

    public function setResistanceFroid(?string $resistanceFroid): static
    {
        $this->resistanceFroid = $resistanceFroid;

        return $this;
    }

    public function getTempsAvantRecolte(): ?string
    {
        return $this->tempsAvantRecolte;
    }

    public function setTempsAvantRecolte(?string $tempsAvantRecolte): static
    {
        $this->tempsAvantRecolte = $tempsAvantRecolte;

        return $this;
    }

    public function getPh(): ?string
    {
        return $this->ph;
    }

    public function setPh(?string $ph): static
    {
        $this->ph = $ph;

        return $this;
    }

    public function getImage(): ?string
    {
        return $this->image;
    }

    public function setImage(?string $image): static
    {
        $this->image = $image;

        return $this;
    }

    public function getIdPlante(): ?Plante
    {
        return $this->idPlante;
    }

    public function setIdPlante(?Plante $idPlante): static
    {
        $this->idPlante = $idPlante;

        return $this;
    }

    /**
     * @return Collection<int, Pousse>
     */
    public function getPousses(): Collection
    {
        return $this->pousses;
    }

    public function addPouss(Pousse $pouss): static
    {
        if (!$this->pousses->contains($pouss)) {
            $this->pousses->add($pouss);
            $pouss->setIdVariete($this);
        }

        return $this;
    }

    public function removePouss(Pousse $pouss): static
    {
        if ($this->pousses->removeElement($pouss)) {
            // set the owning side to null (unless already changed)
            if ($pouss->getIdVariete() === $this) {
                $pouss->setIdVariete(null);
            }
        }

        return $this;
    }
}
