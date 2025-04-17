<?php

namespace App\Entity;

use App\Repository\SalleDeSportRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: SalleDeSportRepository::class)]
class SalleDeSport
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;
    #[ORM\OneToMany(mappedBy: 'salle', targetEntity: Equipement::class)]
    private Collection $equipements;
    
    #[ORM\Column(length: 255)]
    #[Assert\NotBlank(message: "Le nom ne doit pas être vide.")]
    #[Assert\Length(min: 2, max: 255, minMessage: "Le nom est trop court.")]
    private ?string $nom = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank(message: "La zone ne doit pas être vide.")]
    private ?string $zone = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank(message: "L'image est requise.")]
    private ?string $image = null;

    #[ORM\Column]
    #[Assert\NotNull(message: "L'identifiant utilisateur est requis.")]
    #[Assert\Positive(message: "L'identifiant utilisateur doit être un entier positif.")]
    private ?int $id_user = null;

    public function __construct()
    {
        $this->equipements = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNom(): ?string
    {
        return $this->nom;
    }

    public function setNom(string $nom): static
    {
        $this->nom = $nom;

        return $this;
    }

    public function getZone(): ?string
    {
        return $this->zone;
    }

    public function setZone(string $zone): static
    {
        $this->zone = $zone;

        return $this;
    }

    public function getImage(): ?string
    {
        return $this->image;
    }

    public function setImage(string $image): static
    {
        $this->image = $image;

        return $this;
    }

    public function getIdUser(): ?int
    {
        return $this->id_user;
    }

    public function setIdUser(int $id_user): static
    {
        $this->id_user = $id_user;

        return $this;
    }
    public function getEquipements(): Collection
{
    return $this->equipements;
}
}
