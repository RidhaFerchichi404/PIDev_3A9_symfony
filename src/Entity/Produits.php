<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

use Doctrine\Common\Collections\Collection;
use App\Entity\Commandes;

#[ORM\Entity]
class Produits
{

    #[ORM\Id]
    #[ORM\Column(type: "integer")]
    private int $id_produit;

    #[ORM\Column(type: "string", length: 100)]
    private string $nom;

    #[ORM\Column(type: "text")]
    private string $description;

    #[ORM\Column(type: "string", length: 50)]
    private string $categorie;

    #[ORM\Column(type: "float")]
    private float $prix;

    #[ORM\Column(type: "integer")]
    private int $quantite_stock;

    #[ORM\Column(type: "boolean")]
    private bool $disponible;

    #[ORM\Column(type: "string", length: 255)]
    private string $image_path;

    public function getId_produit()
    {
        return $this->id_produit;
    }

    public function setId_produit($value)
    {
        $this->id_produit = $value;
    }

    public function getNom()
    {
        return $this->nom;
    }

    public function setNom($value)
    {
        $this->nom = $value;
    }

    public function getDescription()
    {
        return $this->description;
    }

    public function setDescription($value)
    {
        $this->description = $value;
    }

    public function getCategorie()
    {
        return $this->categorie;
    }

    public function setCategorie($value)
    {
        $this->categorie = $value;
    }

    public function getPrix()
    {
        return $this->prix;
    }

    public function setPrix($value)
    {
        $this->prix = $value;
    }

    public function getQuantite_stock()
    {
        return $this->quantite_stock;
    }

    public function setQuantite_stock($value)
    {
        $this->quantite_stock = $value;
    }

    public function getDisponible()
    {
        return $this->disponible;
    }

    public function setDisponible($value)
    {
        $this->disponible = $value;
    }

    public function getImage_path()
    {
        return $this->image_path;
    }

    public function setImage_path($value)
    {
        $this->image_path = $value;
    }

    #[ORM\OneToMany(mappedBy: "id_produit", targetEntity: Commandes::class)]
    private Collection $commandess;

        public function getCommandess(): Collection
        {
            return $this->commandess;
        }
    
        public function addCommandes(Commandes $commandes): self
        {
            if (!$this->commandess->contains($commandes)) {
                $this->commandess[] = $commandes;
                $commandes->setId_produit($this);
            }
    
            return $this;
        }
    
        public function removeCommandes(Commandes $commandes): self
        {
            if ($this->commandess->removeElement($commandes)) {
                // set the owning side to null (unless already changed)
                if ($commandes->getId_produit() === $this) {
                    $commandes->setId_produit(null);
                }
            }
    
            return $this;
        }
}
