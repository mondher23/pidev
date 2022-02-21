<?php

namespace App\Entity;

use App\Repository\VoyageRepository;
use Doctrine\ORM\Mapping as ORM;

use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass=VoyageRepository::class)
 */
class Voyage
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id_v;

    /**
     * @ORM\Column(type="integer")
     * @Assert\NotBlank(message="Le champ *ID User* est obligatoire")
     */
    private $id_u;

    /**
     * @ORM\Column(type="integer")
     * @Assert\NotBlank(message="Le champ *ID Offre* est obligatoire")
     */
    private $id_o;

    /**
     * @ORM\Column(type="date")
     */
    private $date_dep;

    /**
     * @ORM\Column(type="time")
     */
    private $heure_dep;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank(message="Le champ *destination* est obligatoire")
     */
    private $destination;

    /**
     * @ORM\Column(type="boolean")
     */
    private $done;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getIdV(): ?int
    {
        return $this->id_v;
    }

    public function setIdV(int $id_v): self
    {
        $this->id_v = $id_v;

        return $this;
    }

    public function getIdU(): ?int
    {
        return $this->id_u;
    }

    public function setIdU(int $id_u): self
    {
        $this->id_u = $id_u;

        return $this;
    }

    public function getIdO(): ?int
    {
        return $this->id_o;
    }

    public function setIdO(int $id_o): self
    {
        $this->id_o = $id_o;

        return $this;
    }

    public function getDateDep(): ?\DateTimeInterface
    {
        return $this->date_dep;
    }

    public function setDateDep(\DateTimeInterface $date_dep): self
    {
        $this->date_dep = $date_dep;

        return $this;
    }

    public function getHeureDep(): ?\DateTimeInterface
    {
        return $this->heure_dep;
    }

    public function setHeureDep(\DateTimeInterface $heure_dep): self
    {
        $this->heure_dep = $heure_dep;

        return $this;
    }

    public function getDestination(): ?string
    {
        return $this->destination;
    }

    public function setDestination(string $destination): self
    {
        $this->destination = $destination;

        return $this;
    }

    public function getDone(): ?bool
    {
        return $this->done;
    }

    public function setDone(bool $done): self
    {
        $this->done = $done;

        return $this;
    }


}