<?php

namespace App\Entity;

use App\Repository\PlanteRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: PlanteRepository::class)]
class Plante
{
    #[ORM\Id]
    #[ORM\Column(length: 50)]
    private ?string $idPlante = null;

    #[ORM\Column(length: 255)]
    private ?string $nom = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $description = null;

    #[ORM\ManyToOne(inversedBy: 'plantes')]
    #[ORM\JoinColumn(name: 'idCat', referencedColumnName: 'id_cat')]
    private ?Categorie $idCat = null;

    #[ORM\Column(length: 255)]
    private ?string $image = null;

    /**
     * @var Collection<int, Variete>
     */
    #[ORM\OneToMany(targetEntity: Variete::class, mappedBy: 'idPlante')]
    private Collection $varietes;

    public function __construct()
    {
        $this->varietes = new ArrayCollection();
    }

    public function getIdPlante(): ?string
    {
        return $this->idPlante;
    }

    public function setIdPlante(string $idPlante): static
    {
        $this->idPlante = $idPlante;

        return $this;
    }

    public function getNom(): ?string
    {
        return $this->nom;
    }

    public function setNom(string $nom): static
    {
        $this->nom = $nom;

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

    public function getIdCat(): ?Categorie
    {
        return $this->idCat;
    }

    public function setIdCat(?Categorie $idCat): static
    {
        $this->idCat = $idCat;

        return $this;
    }

    public function getImage(): ?string
    {
        return $this->image;
    }

    public function setImage(string $image): static
    {
        $this->image = $image;

        return $this;
    }

    /**
     * @return Collection<int, Variete>
     */
    public function getVarietes(): Collection
    {
        return $this->varietes;
    }

    public function addVariete(Variete $variete): static
    {
        if (!$this->varietes->contains($variete)) {
            $this->varietes->add($variete);
            $variete->setIdPlante($this);
        }

        return $this;
    }

    public function removeVariete(Variete $variete): static
    {
        if ($this->varietes->removeElement($variete)) {
            // set the owning side to null (unless already changed)
            if ($variete->getIdPlante() === $this) {
                $variete->setIdPlante(null);
            }
        }

        return $this;
    }
}
