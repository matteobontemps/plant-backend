<?php

namespace App\Entity;

use App\Repository\ParcelleRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ParcelleRepository::class)]
class Parcelle
{
    #[ORM\Id]
    #[ORM\Column(length: 50)]
    private ?string $idParcelle = null;

    #[ORM\Column(length: 255)]
    private ?string $libelle = null;

    #[ORM\Column]
    private ?int $longueur = null;

    #[ORM\Column]
    private ?int $largeur = null;

    #[ORM\Column]
    private ?float $tailleCarres = null;

    #[ORM\ManyToOne(inversedBy: 'parcelles')]
    #[ORM\JoinColumn(name: 'idUser', referencedColumnName: 'id_user', nullable: false)]
    private ?User $idUser = null;

    /**
     * @var Collection<int, Pousse>
     */
    #[ORM\OneToMany(targetEntity: Pousse::class, mappedBy: 'idParcelle')]
    private Collection $pousses;

    public function __construct()
    {
        $this->pousses = new ArrayCollection();
    }

    public function getIdParcelle(): ?string
    {
        return $this->idParcelle;
    }

    public function setIdParcelle(string $idParcelle): static
    {
        $this->idParcelle = $idParcelle;

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

    public function getLongueur(): ?int
    {
        return $this->longueur;
    }

    public function setLongueur(int $longueur): static
    {
        $this->longueur = $longueur;

        return $this;
    }

    public function getLargeur(): ?int
    {
        return $this->largeur;
    }

    public function setLargeur(int $largeur): static
    {
        $this->largeur = $largeur;

        return $this;
    }

    public function getTailleCarres(): ?float
    {
        return $this->tailleCarres;
    }

    public function setTailleCarres(float $tailleCarres): static
    {
        $this->tailleCarres = $tailleCarres;

        return $this;
    }

    public function getIdUser(): ?User
    {
        return $this->idUser;
    }

    public function setIdUser(?User $idUser): static
    {
        $this->idUser = $idUser;

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
            $pouss->setIdParcelle($this);
        }

        return $this;
    }

    public function removePouss(Pousse $pouss): static
    {
        if ($this->pousses->removeElement($pouss)) {
            // set the owning side to null (unless already changed)
            if ($pouss->getIdParcelle() === $this) {
                $pouss->setIdParcelle(null);
            }
        }

        return $this;
    }
}
