<?php

namespace App\Entity;

use App\Repository\InvestisseurRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=InvestisseurRepository::class)
 */
class Investisseur
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\OneToOne(targetEntity=User::class, cascade={"persist", "remove"})
     * @ORM\JoinColumn(nullable=false)
     */
    private $utilisateur;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $pays;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $nom_entreprise;

    /**
     * @ORM\OneToMany(targetEntity=Discussion::class, mappedBy="investisseur")
     */
    private $discussions;

    /**
     * @ORM\OneToMany(targetEntity=InvestirOffre::class, mappedBy="investisseur")
     */
    private $investirOffres;

    public function __construct()
    {
        $this->discussions = new ArrayCollection();
        $this->investirOffres = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUtilisateur(): ?User
    {
        return $this->utilisateur;
    }

    public function setUtilisateur(User $utilisateur): self
    {
        $this->utilisateur = $utilisateur;

        return $this;
    }

    public function getPays(): ?string
    {
        return $this->pays;
    }

    public function setPays(string $pays): self
    {
        $this->pays = $pays;

        return $this;
    }

    public function getNomEntreprise(): ?string
    {
        return $this->nom_entreprise;
    }

    public function setNomEntreprise(string $nom_entreprise): self
    {
        $this->nom_entreprise = $nom_entreprise;

        return $this;
    }

    /**
     * @return Collection<int, Discussion>
     */
    public function getDiscussions(): Collection
    {
        return $this->discussions;
    }

    public function addDiscussion(Discussion $discussion): self
    {
        if (!$this->discussions->contains($discussion)) {
            $this->discussions[] = $discussion;
            $discussion->setInvestisseur($this);
        }

        return $this;
    }

    public function removeDiscussion(Discussion $discussion): self
    {
        if ($this->discussions->removeElement($discussion)) {
            // set the owning side to null (unless already changed)
            if ($discussion->getInvestisseur() === $this) {
                $discussion->setInvestisseur(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, InvestirOffre>
     */
    public function getInvestirOffres(): Collection
    {
        return $this->investirOffres;
    }

    public function addInvestirOffre(InvestirOffre $investirOffre): self
    {
        if (!$this->investirOffres->contains($investirOffre)) {
            $this->investirOffres[] = $investirOffre;
            $investirOffre->setInvestisseur($this);
        }

        return $this;
    }

    public function removeInvestirOffre(InvestirOffre $investirOffre): self
    {
        if ($this->investirOffres->removeElement($investirOffre)) {
            // set the owning side to null (unless already changed)
            if ($investirOffre->getInvestisseur() === $this) {
                $investirOffre->setInvestisseur(null);
            }
        }

        return $this;
    }
}
