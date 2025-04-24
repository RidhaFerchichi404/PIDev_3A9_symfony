<?php

namespace App\Entity;

use App\Repository\ProduitRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Produit Entity
 * Represents a product in the system with validation constraints
 */
#[ORM\Entity(repositoryClass: ProduitRepository::class)]
#[ORM\Table(name: 'produits')]
class Produit
{
    /**
     * Primary key of the product
     * @ORM\Column name is customized to match database schema
     */
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(name: 'id_produit')]
    #[Groups(['produit:read'])]
    private ?int $id = null;

    /**
     * Product name
     * Validation constraints:
     * - Cannot be empty
     * - Must be between 3 and 100 characters
     */
    #[ORM\Column(length: 100)]
    #[Assert\NotBlank(message: "Le nom du produit ne peut pas être vide")]
    #[Assert\Length(min: 3, max: 100, minMessage: "Le nom doit contenir au moins {{ limit }} caractères", maxMessage: "Le nom ne peut pas dépasser {{ limit }} caractères")]
    #[Groups(['produit:read'])]
    private ?string $nom = null;

    /**
     * Product description
     * - Optional field (nullable: true)
     * - Stored as TEXT type to allow longer descriptions
     */
    #[ORM\Column(type: Types::TEXT, nullable: true)]
    #[Groups(['produit:read'])]
    private ?string $description = null;

    /**
     * Product category
     * - Optional field (nullable: true)
     * - Maximum length of 50 characters
     */
    #[ORM\Column(length: 50, nullable: true)]
    #[Groups(['produit:read'])]
    private ?string $categorie = null;

    /**
     * Product price
     * Validation constraints:
     * - Cannot be empty
     * - Must be positive
     * - Stored as DECIMAL with 10 total digits and 2 decimal places
     */
    #[ORM\Column(type: Types::DECIMAL, precision: 10, scale: 2)]
    #[Assert\NotBlank(message: "Le prix ne peut pas être vide")]
    #[Assert\Positive(message: "Le prix doit être positif")]
    #[Groups(['produit:read'])]
    private ?string $prix = null;

    /**
     * Stock quantity
     * Validation constraints:
     * - Cannot be empty
     * - Must be zero or positive
     */
    #[ORM\Column]
    #[Assert\NotBlank(message: "La quantité en stock ne peut pas être vide")]
    #[Assert\PositiveOrZero(message: "La quantité en stock ne peut pas être négative")]
    #[Groups(['produit:read'])]
    private ?int $quantiteStock = null;

    /**
     * Product availability
     * - Boolean field
     * - Default value is true
     */
    #[ORM\Column]
    #[Groups(['produit:read'])]
    private ?bool $disponible = true;

    /**
     * Product image path
     * - Optional field (nullable: true)
     * - Maximum length of 255 characters
     */
    #[ORM\Column(length: 255, nullable: true)]
    #[Groups(['produit:read'])]
    private ?string $imagePath = null;

    /**
     * Orders associated with this product
     * OneToMany relationship with Commande entity
     * orphanRemoval: true - removes orders if product is deleted
     */
    #[ORM\OneToMany(mappedBy: 'produit', targetEntity: Commande::class, orphanRemoval: true)]
    private Collection $commandes;

    /**
     * Constructor
     * Initializes the orders collection
     */
    public function __construct()
    {
        $this->commandes = new ArrayCollection();
    }

    // Getters and setters

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

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): static
    {
        $this->description = $description;
        return $this;
    }

    public function getCategorie(): ?string
    {
        return $this->categorie;
    }

    public function setCategorie(?string $categorie): static
    {
        $this->categorie = $categorie;
        return $this;
    }

    public function getPrix(): ?string
    {
        return $this->prix;
    }

    public function setPrix(string $prix): static
    {
        $this->prix = $prix;
        return $this;
    }

    public function getQuantiteStock(): ?int
    {
        return $this->quantiteStock;
    }

    public function setQuantiteStock(int $quantiteStock): static
    {
        $this->quantiteStock = $quantiteStock;
        return $this;
    }

    public function isDisponible(): ?bool
    {
        return $this->disponible;
    }

    public function setDisponible(bool $disponible): static
    {
        $this->disponible = $disponible;
        return $this;
    }

    public function getImagePath(): ?string
    {
        return $this->imagePath;
    }

    public function setImagePath(?string $imagePath): static
    {
        $this->imagePath = $imagePath;
        return $this;
    }

    /**
     * Get all orders associated with this product
     * @return Collection<int, Commande>
     */
    public function getCommandes(): Collection
    {
        return $this->commandes;
    }

    /**
     * Add an order to this product
     * Maintains the bidirectional relationship
     */
    public function addCommande(Commande $commande): static
    {
        if (!$this->commandes->contains($commande)) {
            $this->commandes->add($commande);
            $commande->setProduit($this);
        }
        return $this;
    }

    /**
     * Remove an order from this product
     * Maintains the bidirectional relationship
     */
    public function removeCommande(Commande $commande): static
    {
        if ($this->commandes->removeElement($commande)) {
            if ($commande->getProduit() === $this) {
                $commande->setProduit(null);
            }
        }
        return $this;
    }

    /**
     * String representation of the product
     * Used for display purposes
     */
    public function __toString(): string
    {
        return $this->nom;
    }
}
