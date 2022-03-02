<?php

namespace App\Entity;

use App\Repository\OffreRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\Constraints\MoreThanOrEqual;

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
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank(message="le Titre est Obligatoire")
     */
    private $titre;

    /**
     * @ORM\Column(type="string", length=2000)
     */
    private $description;

    /**
     * @ORM\Column(type="float")
     * @Assert\NotBlank(message="Le champ *remise* est obligatoire")
     * @Assert\LessThanOrEqual(100,message="la remise doit etre inferieure ou egale a 100")
     */
    private $remise;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $image;

    /**
     * @ORM\Column(type="date")
     * @Assert\NotBlank(message="Le champ *date du debut* est obligatoire")
     */
    private $deb_date;

    /**
     * @ORM\Column(type="date")
     * @Assert\NotBlank(message="Le champ *date d'expiration* est obligatoire")
     * @Assert\GreaterThan("today")
     */
    private $exp_date;

    /**
     * @ORM\Column(type="boolean")
     */
    private $expire;

    /**
     * @ORM\Column(type="string", length=10)
     */
    private $background_color;

    /**
     * @ORM\Column(type="string", length=10)
     */
    private $border_color;

    /**
     * @ORM\Column(type="string", length=10)
     */
    private $text_color;

    /**
     * @ORM\OneToMany(targetEntity=Voyage::class, mappedBy="offre")
     */
    private $voyages;

    public function __construct()
    {
        $this->voyages = new ArrayCollection();
    }


    public function getId(): ?int
    {
        return $this->id;
    }

    public function setId(int $id): self
    {
        $this->id = $id;

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

    public function getDebDate(): ?\DateTimeInterface
    {
        return $this->deb_date;
    }

    public function setDebDate(\DateTimeInterface $deb_date): self
    {
        $this->deb_date = $deb_date;

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

    public function getBackgroundColor(): ?string
    {
        return $this->background_color;
    }

    public function setBackgroundColor(string $background_color): self
    {
        $this->background_color = $background_color;

        return $this;
    }

    public function getBorderColor(): ?string
    {
        return $this->border_color;
    }

    public function setBorderColor(string $border_color): self
    {
        $this->border_color = $border_color;

        return $this;
    }

    public function getTextColor(): ?string
    {
        return $this->text_color;
    }

    public function setTextColor(string $text_color): self
    {
        $this->text_color = $text_color;

        return $this;
    }

    /**
     * @return Collection|Voyage[]
     */
    public function getVoyages(): Collection
    {
        return $this->voyages;
    }

    public function addVoyage(Voyage $voyage): self
    {
        if (!$this->voyages->contains($voyage)) {
            $this->voyages[] = $voyage;
            $voyage->setOffre($this);
        }

        return $this;
    }

    public function removeVoyage(Voyage $voyage): self
    {
        if ($this->voyages->removeElement($voyage)) {
            // set the owning side to null (unless already changed)
            if ($voyage->getOffre() === $this) {
                $voyage->setOffre(null);
            }
        }

        return $this;
    }

}