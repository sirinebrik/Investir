<?php

namespace App\Entity;

use App\Repository\MinistereRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=MinistereRepository::class)
 */
class Ministere
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
     * @ORM\OneToOne(targetEntity=TypeMinistere::class, cascade={"persist", "remove"})
     * @ORM\JoinColumn(nullable=false)
     */
    private $type;

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

    public function getType(): ?TypeMinistere
    {
        return $this->type;
    }

    public function setType(TypeMinistere $type): self
    {
        $this->type = $type;

        return $this;
    }
}
