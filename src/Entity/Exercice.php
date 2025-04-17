<?php

namespace App\Entity;

use App\Repository\ExerciceRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use App\Entity\Equipement;

#[ORM\Entity(repositoryClass: ExerciceRepository::class)]
class Exercice
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank(message: "La description est requise.")]
    #[Assert\Length(min: 5, max: 255, minMessage: "La description est trop courte.")]
    private ?string $description = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank(message: "L'image est requise.")]
    // Si c’est une URL d’image :
    #[Assert\Url(message: "Le format de l'image doit être une URL valide.")]
    private ?string $image = null;

    #[ORM\Column]
    #[Assert\NotNull(message: "L'utilisateur est requis.")]
    #[Assert\Positive(message: "L'identifiant utilisateur doit être un entier positif.")]
    private ?int $id_user = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank(message: "Le nom de l'exercice est requis.")]
    #[Assert\Length(min: 2, max: 255, minMessage: "Le nom de l'exercice est trop court.")]
    private ?string $nom_exercice = null;

    #[ORM\ManyToOne(targetEntity: Equipement::class, inversedBy: 'exercices')]
    #[ORM\JoinColumn(name: 'id_equipement', referencedColumnName: 'id', nullable: false)]
    #[Assert\NotNull(message: "L'équipement est obligatoire.")]
    private ?Equipement $equipement = null;
    
    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): static
    {
        $this->description = $description;
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

    public function getNomExercice(): ?string
    {
        return $this->nom_exercice;
    }

    public function setNomExercice(string $nom_exercice): static
    {
        $this->nom_exercice = $nom_exercice;
        return $this;
    }

    public function getEquipement(): ?Equipement
    {
        return $this->equipement;
    }
    
    public function setEquipement(?Equipement $equipement): static
    {
        $this->equipement = $equipement;
        return $this;
    }
}
