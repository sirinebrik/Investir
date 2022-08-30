<?php

namespace App\Entity;

use App\Repository\InvestirOffreRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=InvestirOffreRepository::class)
 */
class InvestirOffre
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity=Investisseur::class, inversedBy="investirOffres")
     */
    private $investisseur;

    /**
     * @ORM\ManyToOne(targetEntity=Offre::class, inversedBy="investirOffres")
     */
    private $offre;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $etat;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $dateInvestir;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getInvestisseur(): ?Investisseur
    {
        return $this->investisseur;
    }

    public function setInvestisseur(?Investisseur $investisseur): self
    {
        $this->investisseur = $investisseur;

        return $this;
    }

    public function getOffre(): ?Offre
    {
        return $this->offre;
    }

    public function setOffre(?Offre $offre): self
    {
        $this->offre = $offre;

        return $this;
    }

    public function getEtat(): ?string
    {
        return $this->etat;
    }

    public function setEtat(string $etat): self
    {
        $this->etat = $etat;

        return $this;
    }

    public function getDateInvestir(): ?string
    {
        return $this->dateInvestir;
    }

    public function setDateInvestir(string $dateInvestir): self
    {
        $this->dateInvestir = $dateInvestir;

        return $this;
    }
}
