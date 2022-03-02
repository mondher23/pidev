<?php

namespace App\Entity;

use App\Repository\ReservationRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass=ReservationRepository::class)
 */
class Reservation
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     * @Assert\NotBlank(message="Le champ *numero* est obligatoire")
     * @Assert\Length(
     *      min = 1,
     *      max = 6,
     *      minMessage = "le numero de la reservation doit contenir au mois 1 caractère",
     *      maxMessage = "le numero de la reservation doit contenir au plus 6 caractères")
     */
    private $num;

    /**
     * @ORM\Column(type="integer")
     */
    private $id_u;

    /**
     * @ORM\Column(type="integer")
     */
    private $id_c;

    /**
     * @ORM\Column(type="date")
      * @Assert\GreaterThan("today")
     */
    private $date;

    /**
     * @ORM\Column(type="time")
     */
    private $heure;

    /**
     * @ORM\Column(type="integer")
     * @Assert\LessThanOrEqual(20,message="le nombre de personnes doit etre au plus 20 ")
     */
    private $nb_personnes;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNum(): ?int
    {
        return $this->num;
    }

    public function setNum(int $num): self
    {
        $this->num = $num;

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

    public function getIdC(): ?int
    {
        return $this->id_c;
    }

    public function setIdC(int $id_c): self
    {
        $this->id_c = $id_c;

        return $this;
    }

    public function getDate(): ?\DateTimeInterface
    {
        return $this->date;
    }

    public function setDate(\DateTimeInterface $date): self
    {
        $this->date = $date;

        return $this;
    }

    public function getHeure(): ?\DateTimeInterface
    {
        return $this->heure;
    }

    public function setHeure(\DateTimeInterface $heure): self
    {
        $this->heure = $heure;

        return $this;
    }

    public function getNbPersonnes(): ?int
    {
        return $this->nb_personnes;
    }

    public function setNbPersonnes(int $nb_personnes): self
    {
        $this->nb_personnes = $nb_personnes;

        return $this;
    }
}
