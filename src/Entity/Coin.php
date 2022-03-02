<?php

namespace App\Entity;

use App\Repository\CoinRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ORM\Entity(repositoryClass=CoinRepository::class)
 */
class Coin
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     * @Groups("post:read")
     */
    private $id;

    /**
     * @ORM\Column(type="integer")
     * @Assert\LessThanOrEqual(50,message="le nombre de places doit etre inferieure ou egale a 50")
     * @Groups("post:read")
     */
    private $nb_places;

    /**
     * @ORM\Column(type="string", length=255)
      * @Assert\NotBlank(message="le pays est Obligatoire")
      *@Groups("post:read")
     */
    private $pays;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups("post:read")
     */
    private $img;

    /**
     * @ORM\Column(type="string", length=255)
    * @Assert\NotBlank(message="la description est Obligatoire")
     *@Groups("post:read")
     */
    private $description_c;

    /**
     * @ORM\OneToMany(targetEntity=Plat::class, mappedBy="coin", cascade={"all"}, orphanRemoval=true)
     */
    private $plats;

    public function __construct()
    {
        $this->plats = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNbPlaces(): ?int
    {
        return $this->nb_places;
    }

    public function setNbPlaces(int $nb_places): self
    {
        $this->nb_places = $nb_places;

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

    public function getImg(): ?string
    {
        return $this->img;
    }

    public function setImg(string $img): self
    {
        $this->img = $img;

        return $this;
    }

    public function getDescriptionC(): ?string
    {
        return $this->description_c;
    }

    public function setDescriptionC(string $description_c): self
    {
        $this->description_c = $description_c;

        return $this;
    }

    /**
     * @return Collection|Plat[]
     */
    public function getPlats(): Collection
    {
        return $this->plats;
    }

    public function addPlat(Plat $plat): self
    {
        if (!$this->plats->contains($plat)) {
            $this->plats[] = $plat;
            $plat->setCoin($this);
        }

        return $this;
    }

    public function removePlat(Plat $plat): self
    {
        if ($this->plats->removeElement($plat)) {
            // set the owning side to null (unless already changed)
            if ($plat->getCoin() === $this) {
                $plat->setCoin(null);
            }
        }

        return $this;
    }
}
