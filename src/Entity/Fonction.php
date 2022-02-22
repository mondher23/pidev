<?php

namespace App\Entity;

use App\Repository\FonctionRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=FonctionRepository::class)
 */
class Fonction
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank(message="Le champ nom_f est obligatoire")
     */
    private $nom_f;

    /**
     * @ORM\Column(type="integer")
     * @Assert\GreaterThanOrEqual(450,message="le salaire doit être supérieur ou egale a 450")
     * @Assert\NotBlank(message="Le champ salaire est obligatoire")
     */
    private $salaire;

    /**
     * @ORM\Column(type="integer")
     * @Assert\NotBlank(message="Le champ nb_heure est obligatoire")
     *    @Assert\Range(
     *      min = 40,
     *      max = 65,
     *      notInRangeMessage = "le nombre d'heure doit être entre 40 et 60", )
     */
    private $nb_heure;

    /**
     * @ORM\OneToMany(targetEntity=Personnel::class, mappedBy="fonction")
     */
    private $personnels;

    /**
     * @ORM\ManyToMany(targetEntity=Depense::class, mappedBy="fonction")
     */
    private $depenses;

    public function __construct()
    {
        $this->personnels = new ArrayCollection();
        $this->depenses = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNomF(): ?string
    {
        return $this->nom_f;
    }

    public function setNomF(string $nom_f): self
    {
        $this->nom_f = $nom_f;

        return $this;
    }

    public function getSalaire(): ?int
    {
        return $this->salaire;
    }

    public function setSalaire(int $salaire): self
    {
        $this->salaire = $salaire;

        return $this;
    }

    public function getNbHeure(): ?int
    {
        return $this->nb_heure;
    }

    public function setNbHeure(int $nb_heure): self
    {
        $this->nb_heure = $nb_heure;

        return $this;
    }

    /**
     * @return Collection|Personnel[]
     */
    public function getPersonnels(): Collection
    {
        return $this->personnels;
    }

    public function addPersonnel(Personnel $personnel): self
    {
        if (!$this->personnels->contains($personnel)) {
            $this->personnels[] = $personnel;
            $personnel->setFonction($this);
        }

        return $this;
    }

    public function removePersonnel(Personnel $personnel): self
    {
        if ($this->personnels->removeElement($personnel)) {
            // set the owning side to null (unless already changed)
            if ($personnel->getFonction() === $this) {
                $personnel->setFonction(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Depense[]
     */
    public function getDepenses(): Collection
    {
        return $this->depenses;
    }

    public function addDepense(Depense $depense): self
    {
        if (!$this->depenses->contains($depense)) {
            $this->depenses[] = $depense;
            $depense->addFonction($this);
        }

        return $this;
    }

    public function removeDepense(Depense $depense): self
    {
        if ($this->depenses->removeElement($depense)) {
            $depense->removeFonction($this);
        }

        return $this;
    }
}
