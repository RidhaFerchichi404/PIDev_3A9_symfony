<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use \DateTimeInterface;

use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\ArrayCollection;
use PhpParser\Node\Expr\Cast\String_;

#[ORM\Entity]
#[ORM\Table(name: 'abonnement')]
class Abonnement
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(name: 'AbonnementID', type: 'integer')]
    private ?int $AbonnementID = null;

    #[ORM\ManyToOne(targetEntity: Salledesport::class)]
    #[ORM\JoinColumn(name: 'SalleID', referencedColumnName: 'SalleID')]
    private ?Salledesport $salle = null;

    #[ORM\Column(name: 'Nom', type: 'string', length: 255)]
    private ?string $nom = null;

    #[ORM\Column(name: 'description', type: 'text', nullable: true)]
    private ?string $description = null;

    #[ORM\Column(name: 'Duree', type: 'integer')]
    private int $duree;
    #[ORM\Column(name: 'Prix', type: 'decimal', precision: 10, scale: 2)]
    private string $prix;
    
      // ✅ Propriété "type" ajoutée pour corriger l'erreur
      #[ORM\Column(name: 'type', type: 'string', length: 255, nullable: true)]
      private ?string $type = null;

    #[ORM\Column(name: 'salle_name', type: 'string', length: 100, nullable: true)]
    private ?string $salleName = null;
    #[ORM\OneToMany(targetEntity: Promotion::class, mappedBy: 'abonnement')]
    private Collection $promotions;
    
    public function __construct()
    {
        $this->promotions = new ArrayCollection();
    }
    
    // Ajoutez les getters/setters
  
// Getters et Setters pour la classe Abonnement
public function getPromotions(): Collection
{
    return $this->promotions;
}

public function addPromotion(Promotion $promotion): self
{
    if (!$this->promotions->contains($promotion)) {
        $this->promotions[] = $promotion;
        $promotion->setAbonnement($this);
    }

    return $this;
}
public function removePromotion(Promotion $promotion): self
{
    if ($this->promotions->removeElement($promotion)) {
        // set the owning side to null (unless already changed)
        if ($promotion->getAbonnement() === $this) {
            $promotion->setAbonnement(null);
        }
    }

    return $this;
}
public function getAbonnementID(): ?int
{
    return $this->AbonnementID;
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

    // ✅ Getter/Setter pour la propriété "type"
    public function getType(): ?string
    {
        return $this->type;
    }

    public function setType(?string $type): self
    {
        $this->type = $type;
        return $this;
    }
public function getNom(): ?string
{
    return $this->nom;
}

public function setNom(?string $nom): self
{
    $this->nom = $nom;
    return $this;
}

public function getDescription(): ?string
{
    return $this->description;
}

public function setDescription(?string $description): self
{
    $this->description = $description;
    return $this;
}

public function getDuree(): int
{
    return $this->duree;
}

public function setDuree(int $duree): self
{
    $this->duree = $duree;
    return $this;
}

public function getPrix(): string
{
    return $this->prix;
}

public function setPrix(string $prix): self
{
    $this->prix = $prix;
    return $this;
}

public function getSalleName(): ?string
{
    return $this->salleName;
}

public function setSalleName(?string $salleName): self
{
    $this->salleName = $salleName;
    return $this;
}
    
}