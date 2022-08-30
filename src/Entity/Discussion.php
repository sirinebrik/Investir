<?php

namespace App\Entity;

use App\Repository\DiscussionRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=DiscussionRepository::class)
 */
class Discussion
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity=Investisseur::class, inversedBy="discussions")
     */
    private $investisseur;

    /**
     * @ORM\ManyToOne(targetEntity=Offre::class, inversedBy="discussions")
     */
    private $offre;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $send;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $dateEnvoi;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $message;

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

    public function getSend(): ?string
    {
        return $this->send;
    }

    public function setSend(string $send): self
    {
        $this->send = $send;

        return $this;
    }

    public function getDateEnvoi(): ?string
    {
        return $this->dateEnvoi;
    }

    public function setDateEnvoi(string $dateEnvoi): self
    {
        $this->dateEnvoi = $dateEnvoi;

        return $this;
    }

    public function getMessage(): ?string
    {
        return $this->message;
    }

    public function setMessage(string $message): self
    {
        $this->message = $message;

        return $this;
    }
}
