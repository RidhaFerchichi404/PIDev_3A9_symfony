<?php

namespace App\Entity;

use App\Repository\CommandeRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: CommandeRepository::class)]
#[ORM\Table(name: 'commandes')]
class Commande
{
    const STATUS_PENDING = 'En attente';
    const STATUS_PROCESSING = 'En cours';
    const STATUS_DELIVERED = 'Livrée';
    const STATUS_CANCELLED = 'Annulée';

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(name: 'id_commande')]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'commandes')]
    #[ORM\JoinColumn(name: 'id_produit', referencedColumnName: 'id_produit', nullable: false)]
    private ?Produit $produit = null;

    #[ORM\Column(name: 'id_user')]
    private ?int $userId = null;

    #[ORM\Column(name: 'nom_client', length: 100)]
    #[Assert\NotBlank(message: "Le nom du client ne peut pas être vide")]
    #[Assert\Length(min: 2, max: 100, minMessage: "Le nom doit contenir au moins {{ limit }} caractères", maxMessage: "Le nom ne peut pas dépasser {{ limit }} caractères")]
    private ?string $nomClient = null;

    #[ORM\Column(name: 'adresse_livraison', type: Types::TEXT)]
    #[Assert\NotBlank(message: "L'adresse de livraison ne peut pas être vide")]
    private ?string $adresseLivraison = null;

    #[ORM\Column(name: 'telephone', length: 20)]
    #[Assert\NotBlank(message: "Le numéro de téléphone ne peut pas être vide")]
    #[Assert\Regex(pattern: "/^[0-9\-\+\s\(\)]{8,20}$/", message: "Format de téléphone invalide")]
    private ?string $telephone = null;

    #[ORM\Column(name: 'quantite')]
    #[Assert\NotBlank(message: "La quantité ne peut pas être vide")]
    #[Assert\Positive(message: "La quantité doit être positive")]
    private ?int $quantite = null;

    #[ORM\Column(name: 'date_commande', type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $dateCommande = null;

    #[ORM\Column(name: 'statut_commande', length: 20)]
    private ?string $statutCommande = self::STATUS_PENDING;

    public function __construct()
    {
        $this->dateCommande = new \DateTime();
    }

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

    public function setStatutCommande(string $statutCommande): static
    {
        if (!in_array($statutCommande, [self::STATUS_PENDING, self::STATUS_PROCESSING, self::STATUS_DELIVERED, self::STATUS_CANCELLED])) {
            throw new \InvalidArgumentException("Statut de commande invalide");
        }
        
        $this->statutCommande = $statutCommande;

        return $this;
    }

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
