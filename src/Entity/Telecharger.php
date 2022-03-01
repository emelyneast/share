<?php

namespace App\Entity;

use App\Repository\TelechargerRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=TelechargerRepository::class)
 */
class Telecharger
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="integer")
     */
    private $nb;

    /**
     * @ORM\ManyToOne(targetEntity=Inscrire::class, inversedBy="telechargers")
     */
    private $inscrire;

    /**
     * @ORM\ManyToOne(targetEntity=Fichier::class, inversedBy="telechargers")
     */
    private $fichier;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNb(): ?int
    {
        return $this->nb;
    }

    public function setNb(int $nb): self
    {
        $this->nb = $nb;

        return $this;
    }

    public function getInscrire(): ?Inscrire
    {
        return $this->inscrire;
    }

    public function setInscrire(?Inscrire $inscrire): self
    {
        $this->inscrire = $inscrire;

        return $this;
    }

    public function getFichier(): ?Fichier
    {
        return $this->fichier;
    }

    public function setFichier(?Fichier $fichier): self
    {
        $this->fichier = $fichier;

        return $this;
    }
}
