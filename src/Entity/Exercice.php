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
    #[Assert\Length(
        min: 10, 
        max: 255, 
        minMessage: "La description doit contenir au moins {{ limit }} caractères.",
        maxMessage: "La description ne peut pas dépasser {{ limit }} caractères."
    )]
    #[Assert\Regex(
        pattern: "/^[a-zA-Z0-9àáâäãåąčćęèéêëėįìíîïłńòóôöõøùúûüųūÿýżźñçšžÀÁÂÄÃÅĄĆČĖĘÈÉÊËÌÍÎÏĮŁŃÒÓÔÖÕØÙÚÛÜŲŪŸÝŻŹÑßÇŒÆŠŽ ,.'-]+$/",
        message: "La description ne doit contenir que des lettres, chiffres et certains caractères spéciaux."
    )]
    private ?string $description = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank(message: "L'image est requise.")]
    #[Assert\Regex(
        pattern: "/.+\.(gif|jpe?g|png|webp)$/i",
        message: "Le fichier doit être au format GIF, JPEG, PNG ou WEBP."
    )]
    private ?string $image = null;

    #[ORM\Column]
    #[Assert\NotNull(message: "L'utilisateur est requis.")]
    #[Assert\Positive(message: "L'identifiant utilisateur doit être un entier positif.")]
    private ?int $id_user = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank(message: "Le nom de l'exercice est requis.")]
    #[Assert\Length(
        min: 3, 
        max: 50, 
        minMessage: "Le nom de l'exercice doit contenir au moins {{ limit }} caractères.",
        maxMessage: "Le nom de l'exercice ne peut pas dépasser {{ limit }} caractères."
    )]
    #[Assert\Regex(
        pattern: "/^[a-zA-Z0-9àáâäãåąčćęèéêëėįìíîïłńòóôöõøùúûüųūÿýżźñçšžÀÁÂÄÃÅĄĆČĖĘÈÉÊËÌÍÎÏĮŁŃÒÓÔÖÕØÙÚÛÜŲŪŸÝŻŹÑßÇŒÆŠŽ ,.'-]+$/",
        message: "Le nom de l'exercice ne doit contenir que des lettres, chiffres et certains caractères spéciaux."
    )]
    private ?string $nom_exercice = null;

    #[ORM\ManyToOne(targetEntity: Equipement::class, inversedBy: 'exercices')]
    #[ORM\JoinColumn(name: 'id_equipement', referencedColumnName: 'id', nullable: false)]
    #[Assert\NotNull(message: "L'équipement est obligatoire.")]
    private ?Equipement $equipement = null;

    #[ORM\Column(length: 255, nullable: true)]
    #[Assert\Choice(
        choices: ["biceps", "triceps", "épaules", "dos", "pectoraux", "abdominaux", "jambes", "fessiers", "mollets", "avant-bras", "cardio", "full-body"],
        message: "Le groupe musculaire choisi n'est pas valide.",
        multiple: false
    )]
    private ?string $muscle = null;

    #[ORM\Column(length: 1000, nullable: true)]
    #[Assert\Length(
        max: 1000,
        maxMessage: "Les conseils ne peuvent pas dépasser {{ limit }} caractères."
    )]
    private ?string $conseils = null;
    
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

    public function getMuscle(): ?string
    {
        return $this->muscle;
    }

    public function setMuscle(?string $muscle): static
    {
        $this->muscle = $muscle;
        return $this;
    }

    public function getConseils(): ?string
    {
        return $this->conseils;
    }

    public function setConseils(?string $conseils): static
    {
        $this->conseils = $conseils;
        return $this;
    }
}
