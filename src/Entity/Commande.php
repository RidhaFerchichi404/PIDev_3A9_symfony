<?php

namespace App\Entity;

use App\Repository\CommandeRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Commande Entity
 * Represents an order in the system with validation constraints
 */
#[ORM\Entity(repositoryClass: CommandeRepository::class)]
#[ORM\Table(name: 'commandes')]
class Commande
{
    // Order status constants
    const STATUS_PENDING = 'En attente';    // Order is waiting to be processed
    const STATUS_PROCESSING = 'En cours';   // Order is being processed
    const STATUS_DELIVERED = 'Livrée';      // Order has been delivered
    const STATUS_CANCELLED = 'Annulée';     // Order has been cancelled

    /**
     * Primary key of the order
     * @ORM\Column name is customized to match database schema
     */
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(name: 'id_commande')]
    private ?int $id = null;

    /**
     * Product associated with the order
     * ManyToOne relationship with Produit entity
     * Cannot be null (nullable: false)
     */
    #[ORM\ManyToOne(inversedBy: 'commandes')]
    #[ORM\JoinColumn(name: 'id_produit', referencedColumnName: 'id_produit', nullable: false)]
    private ?Produit $produit = null;

    /**
     * User ID who placed the order
     * @ORM\Column name is customized to match database schema
     */
    #[ORM\Column(name: 'id_user')]
    private ?int $userId = null;

    /**
     * Client's name
     * Validation constraints:
     * - Cannot be empty
     * - Must be between 2 and 100 characters
     */
    #[ORM\Column(name: 'nom_client', length: 100)]
    #[Assert\NotBlank(message: "Le nom du client ne peut pas être vide")]
    #[Assert\Length(min: 2, max: 100, minMessage: "Le nom doit contenir au moins {{ limit }} caractères", maxMessage: "Le nom ne peut pas dépasser {{ limit }} caractères")]
    private ?string $nomClient = null;

    /**
     * Delivery address
     * Validation constraints:
     * - Cannot be empty
     * - Stored as TEXT type to allow longer addresses
     */
    #[ORM\Column(name: 'adresse_livraison', type: Types::TEXT)]
    #[Assert\NotBlank(message: "L'adresse de livraison ne peut pas être vide")]
    private ?string $adresseLivraison = null;

    /**
     * Client's phone number
     * Validation constraints:
     * - Cannot be empty
     * - Must match phone number format (8-20 characters, can include numbers, -, +, spaces, and parentheses)
     */
    #[ORM\Column(name: 'telephone', length: 20)]
    #[Assert\NotBlank(message: "Le numéro de téléphone ne peut pas être vide")]
    #[Assert\Regex(pattern: "/^[0-9\-\+\s\(\)]{8,20}$/", message: "Format de téléphone invalide")]
    private ?string $telephone = null;

    /**
     * Order quantity
     * Validation constraints:
     * - Cannot be empty
     * - Must be a positive number
     */
    #[ORM\Column(name: 'quantite')]
    #[Assert\NotBlank(message: "La quantité ne peut pas être vide")]
    #[Assert\Positive(message: "La quantité doit être positive")]
    private ?int $quantite = null;

    /**
     * Order date
     * Automatically set to current date/time when order is created
     */
    #[ORM\Column(name: 'date_commande', type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $dateCommande = null;

    /**
     * Order status
     * Default value is STATUS_PENDING
     * Must be one of the defined status constants
     */
    #[ORM\Column(name: 'statut_commande', length: 20)]
    private ?string $statutCommande = self::STATUS_PENDING;

    /**
     * Constructor
     * Initializes the order date to current date/time
     */
    public function __construct()
    {
        $this->dateCommande = new \DateTime();
    }

    // Getters and setters with validation

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getProduit(): ?Produit
    {
        return $this->produit;
    }

    public function setProduit(?Produit $produit): static
    {
        $this->produit = $produit;
        return $this;
    }

    public function getUserId(): ?int
    {
        return $this->userId;
    }

    public function setUserId(int $userId): static
    {
        $this->userId = $userId;
        return $this;
    }

    public function getNomClient(): ?string
    {
        return $this->nomClient;
    }

    public function setNomClient(string $nomClient): static
    {
        $this->nomClient = $nomClient;
        return $this;
    }

    public function getAdresseLivraison(): ?string
    {
        return $this->adresseLivraison;
    }

    public function setAdresseLivraison(string $adresseLivraison): static
    {
        $this->adresseLivraison = $adresseLivraison;
        return $this;
    }

    public function getTelephone(): ?string
    {
        return $this->telephone;
    }

    public function setTelephone(string $telephone): static
    {
        $this->telephone = $telephone;
        return $this;
    }

    public function getQuantite(): ?int
    {
        return $this->quantite;
    }

    public function setQuantite(int $quantite): static
    {
        $this->quantite = $quantite;
        return $this;
    }

    public function getDateCommande(): ?\DateTimeInterface
    {
        return $this->dateCommande;
    }

    public function setDateCommande(\DateTimeInterface $dateCommande): static
    {
        $this->dateCommande = $dateCommande;
        return $this;
    }

    public function getStatutCommande(): ?string
    {
        return $this->statutCommande;
    }

    /**
     * Sets the order status with validation
     * @throws \InvalidArgumentException if status is not valid
     */
    public function setStatutCommande(string $statutCommande): static
    {
        if (!in_array($statutCommande, [self::STATUS_PENDING, self::STATUS_PROCESSING, self::STATUS_DELIVERED, self::STATUS_CANCELLED])) {
            throw new \InvalidArgumentException("Statut de commande invalide");
        }
        
        $this->statutCommande = $statutCommande;
        return $this;
    }

    /**
     * Returns all available order statuses
     * @return array List of valid order statuses
     */
    public static function getAvailableStatuses(): array
    {
        return [
            self::STATUS_PENDING,
            self::STATUS_PROCESSING,
            self::STATUS_DELIVERED,
            self::STATUS_CANCELLED
        ];
    }
}
