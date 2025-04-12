<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

use App\Entity\User;

#[ORM\Entity]
class Commandes
{

    #[ORM\Id]
    #[ORM\Column(type: "integer")]
    private int $id_commande;

        #[ORM\ManyToOne(targetEntity: Produits::class, inversedBy: "commandess")]
    #[ORM\JoinColumn(name: 'id_produit', referencedColumnName: 'id_produit', onDelete: 'CASCADE')]
    private Produits $id_produit;

        #[ORM\ManyToOne(targetEntity: User::class, inversedBy: "commandess")]
    #[ORM\JoinColumn(name: 'id_user', referencedColumnName: 'id', onDelete: 'CASCADE')]
    private User $id_user;

    #[ORM\Column(type: "string", length: 100)]
    private string $nom_client;

    #[ORM\Column(type: "text")]
    private string $adresse_livraison;

    #[ORM\Column(type: "string", length: 20)]
    private string $telephone;

    #[ORM\Column(type: "integer")]
    private int $quantite;

    #[ORM\Column(type: "datetime")]
    private \DateTimeInterface $date_commande;

    #[ORM\Column(type: "string", length: 20)]
    private string $statut_commande;

    public function getId_commande()
    {
        return $this->id_commande;
    }

    public function setId_commande($value)
    {
        $this->id_commande = $value;
    }

    public function getId_produit()
    {
        return $this->id_produit;
    }

    public function setId_produit($value)
    {
        $this->id_produit = $value;
    }

    public function getId_user()
    {
        return $this->id_user;
    }

    public function setId_user($value)
    {
        $this->id_user = $value;
    }

    public function getNom_client()
    {
        return $this->nom_client;
    }

    public function setNom_client($value)
    {
        $this->nom_client = $value;
    }

    public function getAdresse_livraison()
    {
        return $this->adresse_livraison;
    }

    public function setAdresse_livraison($value)
    {
        $this->adresse_livraison = $value;
    }

    public function getTelephone()
    {
        return $this->telephone;
    }

    public function setTelephone($value)
    {
        $this->telephone = $value;
    }

    public function getQuantite()
    {
        return $this->quantite;
    }

    public function setQuantite($value)
    {
        $this->quantite = $value;
    }

    public function getDate_commande()
    {
        return $this->date_commande;
    }

    public function setDate_commande($value)
    {
        $this->date_commande = $value;
    }

    public function getStatut_commande()
    {
        return $this->statut_commande;
    }

    public function setStatut_commande($value)
    {
        $this->statut_commande = $value;
    }
}
