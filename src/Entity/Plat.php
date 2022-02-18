<?php

namespace App\Entity;

use App\Repository\PlatRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=PlatRepository::class)
 */
class Plat
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
    private $id_p;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $nom_p;

    /**
     * @ORM\Column(type="float")
     */
    private $prix;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $img_p;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $description;

    /**
     * @ORM\Column(type="boolean")
     */
    private $dispo;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getIdP(): ?int
    {
        return $this->id_p;
    }

    public function setIdP(int $id_p): self
    {
        $this->id_p = $id_p;

        return $this;
    }

    public function getNomP(): ?string
    {
        return $this->nom_p;
    }

    public function setNomP(string $nom_p): self
    {
        $this->nom_p = $nom_p;

        return $this;
    }

    public function getPrix(): ?float
    {
        return $this->prix;
    }

    public function setPrix(float $prix): self
    {
        $this->prix = $prix;

        return $this;
    }

    public function getImgP(): ?string
    {
        return $this->img_p;
    }

    public function setImgP(string $img_p): self
    {
        $this->img_p = $img_p;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getDispo(): ?bool
    {
        return $this->dispo;
    }

    public function setDispo(bool $dispo): self
    {
        $this->dispo = $dispo;

        return $this;
    }
}
