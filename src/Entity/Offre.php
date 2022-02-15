<?php

namespace App\Entity;

use App\Repository\OffreRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=OffreRepository::class)
 */
class Offre
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id_o;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $titre;

    /**
     * @ORM\Column(type="string", length=2000)
     */
    private $description;

    /**
     * @ORM\Column(type="float")
     */
    private $remise;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $image;

    /**
     * @ORM\Column(type="date")
     */
    private $exp_date;

    /**
     * @ORM\Column(type="boolean")
     */
    private $expire;


    public function getIdO(): ?int
    {
        return $this->id_o;
    }

    public function setIdO(int $id_o): self
    {
        $this->id_o = $id_o;

        return $this;
    }

    public function getTitre(): ?string
    {
        return $this->titre;
    }

    public function setTitre(string $titre): self
    {
        $this->titre = $titre;

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

    public function getImage(): ?string
    {
        return $this->image;
    }

    public function setImage(string $image): self
    {
        $this->image = $image;

        return $this;
    }

    public function getRemise(): ?float
    {
        return $this->remise;
    }

    public function setRemise(float $remise): self
    {
        $this->remise = $remise;

        return $this;
    }

    public function getExpDate(): ?\DateTimeInterface
    {
        return $this->exp_date;
    }

    public function setExpDate(\DateTimeInterface $exp_date): self
    {
        $this->exp_date = $exp_date;

        return $this;
    }

    public function getExpire(): ?bool
    {
        return $this->expire;
    }

    public function setExpire(bool $expire): self
    {
        $this->expire = $expire;

        return $this;
    }
}
