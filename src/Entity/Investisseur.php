<?php

namespace App\Entity;

use App\Repository\InvestisseurRepository;
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
}
