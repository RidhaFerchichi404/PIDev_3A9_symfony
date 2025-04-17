<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;


#[ORM\Entity]
#[ORM\Table(name: 'promotion')]
class Promotion
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(name: 'PromotionID', type: 'integer')]
    private ?int $id = null;

    #[ORM\Column(name: 'CodePromo', type: 'string', length: 50)]
    private string $codePromo;

    #[ORM\Column(type: 'text')]
    private string $description;

    #[ORM\Column(name: 'TypeReduction', type: 'string', length: 50)]
    private string $typeReduction;

    #[ORM\Column(name: 'ValeurReduction', type: 'decimal', precision: 10, scale: 2)]
    private string $valeurReduction;
    #[ORM\Column(name: "DateDebut", type: "date")]
    private \DateTimeInterface $dateDebut;

    #[ORM\Column(name: "DateFin", type: "date")]
    private \DateTimeInterface $dateFin;
    #[ORM\Column(name: 'DateCreation', type: 'datetime')]
    private \DateTimeInterface $dateCreation;

    #[ORM\ManyToOne(targetEntity: Salledesport::class)]
    #[ORM\JoinColumn(name: 'SalleId', referencedColumnName: 'SalleID')]
    private ?Salledesport $salle = null;

    
    #[ORM\ManyToOne(targetEntity: Abonnement::class, inversedBy: 'promotions')]
    #[ORM\JoinColumn(name: 'AbonnementID', referencedColumnName: 'AbonnementID', nullable: false, onDelete: 'CASCADE')]
    #[Assert\NotNull(message: 'L\'abonnement est obligatoire.')]
    private ?Abonnement $abonnement = null;
    

    public function __construct()
    {
        $this->dateCreation = new \DateTime();
    }
// ... (dans la classe Promotion)

public function getId(): ?int
{
    return $this->id;
}

public function getCodePromo(): string
{
    return $this->codePromo;
}

public function setCodePromo(string $codePromo): self
{
    $this->codePromo = $codePromo;
    return $this;
}

public function getDescription(): string
{
    return $this->description;
}

public function setDescription(string $description): self
{
    $this->description = $description;
    return $this;
}

public function getTypeReduction(): string
{
    return $this->typeReduction;
}

public function setTypeReduction(string $typeReduction): self
{
    $this->typeReduction = $typeReduction;
    return $this;
}

public function getValeurReduction(): string
{
    return $this->valeurReduction;
}

public function setValeurReduction(string $valeurReduction): self
{
    $this->valeurReduction = $valeurReduction;
    return $this;
}

public function getDateDebut(): \DateTimeInterface
{
    return $this->dateDebut;
}

public function setDateDebut(\DateTimeInterface $dateDebut): self
{
    $this->dateDebut = $dateDebut;
    return $this;
}

public function getDateFin(): \DateTimeInterface
{
    return $this->dateFin;
}

public function setDateFin(\DateTimeInterface $dateFin): self
{
    $this->dateFin = $dateFin;
    return $this;
}

public function getDateCreation(): \DateTimeInterface
{
    return $this->dateCreation;
}

public function setDateCreation(\DateTimeInterface $dateCreation): self
{
    $this->dateCreation = $dateCreation;
    return $this;
}

public function getSalle(): ?Salledesport
{
    return $this->salle;
}

public function setSalle(?Salledesport $salle): self
{
    $this->salle = $salle;
    return $this;
}

public function getAbonnement(): ?Abonnement
{
    return $this->abonnement;
}

public function setAbonnement(?Abonnement $abonnement): self
{
    $this->abonnement = $abonnement;
    return $this;
}
    
}