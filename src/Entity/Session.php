<?php

namespace App\Entity;

use App\Repository\SessionRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=SessionRepository::class)
 */
class Session
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=150)
     */
    private $lieu;

    /**
     * @ORM\Column(type="string", length=25)
     */
    private $statut;

    /**
     * @ORM\Column(type="date")
     */
    private $dateDebut;

    /**
     * @ORM\Column(type="date")
     */
    private $dateFin;

    /**
     * @ORM\Column(type="integer")
     */
    private $placeTotal;

    /**
     * @ORM\ManyToMany(targetEntity=Stagiaire::class, inversedBy="sessions")
     */
    private $stagiaires;

    /**
     * @ORM\ManyToOne(targetEntity=Formation::class, inversedBy="sessions")
     * @ORM\JoinColumn(nullable=false)
     */
    private $formation;

    /**
     * @ORM\OneToMany(targetEntity=Programme::class, mappedBy="session", orphanRemoval=true)
     */
    private $programmesSession;

    public function __construct()
    {
        $this->stagiaires = new ArrayCollection();
        $this->programmes = new ArrayCollection();
        $this->programmesSession = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getLieu(): ?string
    {
        return $this->lieu;
    }

    public function setLieu(string $lieu): self
    {
        $this->lieu = $lieu;

        return $this;
    }

    public function getStatut(): ?string
    {
        return $this->statut;
    }

    public function setStatut(string $statut): self
    {
        $this->statut = $statut;

        return $this;
    }

    public function getDateDebut(): ?\DateTimeInterface
    {
        return $this->dateDebut;
    }

    public function setDateDebut(\DateTimeInterface $dateDebut): self
    {
        $this->dateDebut = $dateDebut;

        return $this;
    }

    public function getDateFin(): ?\DateTimeInterface
    {
        return $this->dateFin;
    }

    public function setDateFin(\DateTimeInterface $dateFin): self
    {
        $this->dateFin = $dateFin;

        return $this;
    }

    public function getPlaceTotal(): ?int
    {
        return $this->placeTotal;
    }

    public function setPlaceTotal(int $placeTotal): self
    {
        $this->placeTotal = $placeTotal;

        return $this;
    }

    /**
     * @return Collection<int, Stagiaire>
     */
    public function getStagiaires(): Collection
    {
        return $this->stagiaires;
    }

    public function addStagiaire(Stagiaire $stagiaire): self
    {
        if (!$this->stagiaires->contains($stagiaire)) {
            $this->stagiaires[] = $stagiaire;
        }

        return $this;
    }

    public function removeStagiaire(Stagiaire $stagiaire): self
    {
        $this->stagiaires->removeElement($stagiaire);

        return $this;
    }

    public function getFormation(): ?Formation
    {
        return $this->formation;
    }

    public function setFormation(?Formation $formation): self
    {
        $this->formation = $formation;

        return $this;
    }

    /**
     * @return Collection<int, Programme>
     */
    public function getProgrammesSession(): Collection
    {
        return $this->programmesSession;
    }

    public function addProgrammesSession(Programme $programmesSession): self
    {
        if (!$this->programmesSession->contains($programmesSession)) {
            $this->programmesSession[] = $programmesSession;
            $programmesSession->setSession($this);
        }

        return $this;
    }

    public function removeProgrammesSession(Programme $programmesSession): self
    {
        if ($this->programmesSession->removeElement($programmesSession)) {
            // set the owning side to null (unless already changed)
            if ($programmesSession->getSession() === $this) {
                $programmesSession->setSession(null);
            }
        }

        return $this;
    }

    // Function permettant de calculer le nombre de place restant dans une session de formation
    public function nbRestant()
    {
        $nbInscrit = count($this->stagiaires);    //// On compte le nombre d'inscrit avec la function count()
        $nbPlaceTotal = $this->placeTotal;        //// On déclare le nombre de place total à un formation
        $nbRestant = $nbPlaceTotal - $nbInscrit;    //// On soustrait le nombre d'inscrit au place total
        return $nbRestant;                          //// On return le résultat
    }

    // Function permettant de calculer le nombre d'inscrit à une session de formation
    public function nbInscrit()
    {
        $nbInscrit = count($this->stagiaires);    //// On compte le nombre d'inscrit avec la function count()
        return $nbInscrit;                          //// On return le résultat
    }
}
