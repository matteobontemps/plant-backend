<?php

namespace App\Entity;

use App\Repository\CategorieRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: CategorieRepository::class)]
class Categorie
{
    #[ORM\Id]
    #[ORM\Column(type: 'string', length: 50)]
    private ?string $idCat = null;

    #[ORM\Column(length: 255)]
    private ?string $libelle = null;

    #[ORM\ManyToOne(targetEntity: self::class, inversedBy: 'categories')]
    #[ORM\JoinColumn(name: 'idCatParent', referencedColumnName: 'id_cat', onDelete: 'SET NULL')]
    private ?self $idCatParent = null;

    /**
     * @var Collection<int, self>
     */
    #[ORM\OneToMany(targetEntity: self::class, mappedBy: 'idCatParent')]
    private Collection $categories;

    /**
     * @var Collection<int, Plante>
     */
    #[ORM\OneToMany(targetEntity: Plante::class, mappedBy: 'idCat')]
    private Collection $plantes;

    public function __construct()
    {
        $this->categories = new ArrayCollection();
        $this->plantes = new ArrayCollection();
    }

    public function getIdCat(): ?string
    {
        return $this->idCat;
    }

    public function setIdCat(string $idCat): static
    {
        $this->idCat = $idCat;

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

    public function getIdCatParent(): ?self
    {
        return $this->idCatParent;
    }

    public function setIdCatParent(?self $idCatParent): static
    {
        $this->idCatParent = $idCatParent;

        return $this;
    }

    /**
     * @return Collection<int, self>
     */
    public function getCategories(): Collection
    {
        return $this->categories;
    }

    public function addCategory(self $category): static
    {
        if (!$this->categories->contains($category)) {
            $this->categories->add($category);
            $category->setIdCatParent($this);
        }

        return $this;
    }

    public function removeCategory(self $category): static
    {
        if ($this->categories->removeElement($category)) {
            // set the owning side to null (unless already changed)
            if ($category->getIdCatParent() === $this) {
                $category->setIdCatParent(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Plante>
     */
    public function getPlantes(): Collection
    {
        return $this->plantes;
    }

    public function addPlante(Plante $plante): static
    {
        if (!$this->plantes->contains($plante)) {
            $this->plantes->add($plante);
            $plante->setIdCat($this);
        }

        return $this;
    }

    public function removePlante(Plante $plante): static
    {
        if ($this->plantes->removeElement($plante)) {
            // set the owning side to null (unless already changed)
            if ($plante->getIdCat() === $this) {
                $plante->setIdCat(null);
            }
        }

        return $this;
    }
}
