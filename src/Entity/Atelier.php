<?php

namespace App\Entity;


use App\Repository\AtelierRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=AtelierRepository::class)
 */
class Atelier
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=50)
     */
    private $nom;

    /**
     * @ORM\ManyToOne(targetEntity=Categorie::class, inversedBy="ateliers")
     * @ORM\JoinColumn(nullable=false)
     */
    private $categorie;

    /**
     * @ORM\OneToMany(targetEntity=Programme::class, mappedBy="atelier", orphanRemoval=true)
     */
    private $pro;

    /**
     * @ORM\OneToMany(targetEntity=Programme::class, mappedBy="atelier", orphanRemoval=true)
     */
    private $programmesAtelier;

    public function __construct()
    {
        $this->programmes = new ArrayCollection();
        $this->pro = new ArrayCollection();
        $this->programmesAtelier = new ArrayCollection();
    }


    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNom(): ?string
    {
        return $this->nom;
    }

    public function setNom(string $nom): self
    {
        $this->nom = $nom;

        return $this;
    }

    public function getCategorie(): ?Categorie
    {
        return $this->categorie;
    }

    public function setCategorie(?Categorie $categorie): self
    {
        $this->categorie = $categorie;

        return $this;
    }

    /**
     * @return Collection<int, Programme>
     */
    public function getProgrammesAtelier(): Collection
    {
        return $this->programmesAtelier;
    }

    public function addProgrammesAtelier(Programme $programmesAtelier): self
    {
        if (!$this->programmesAtelier->contains($programmesAtelier)) {
            $this->programmesAtelier[] = $programmesAtelier;
            $programmesAtelier->setAtelier($this);
        }

        return $this;
    }

    public function removeProgrammesAtelier(Programme $programmesAtelier): self
    {
        if ($this->programmesAtelier->removeElement($programmesAtelier)) {
            // set the owning side to null (unless already changed)
            if ($programmesAtelier->getAtelier() === $this) {
                $programmesAtelier->setAtelier(null);
            }
        }

        return $this;
    }
}
