<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;


#[ORM\Entity]
class Equipement
{

    #[ORM\Id]
    #[ORM\Column(type: "bigint")]
    private string $id;

    #[ORM\Column(type: "bigint")]
    private string $id_salle;

    #[ORM\Column(type: "string", length: 100)]
    private string $nom;

    #[ORM\Column(type: "boolean")]
    private bool $fonctionnement;

    #[ORM\Column(type: "date")]
    private \DateTimeInterface $prochaine_verification;

    #[ORM\Column(type: "date")]
    private \DateTimeInterface $derniere_verification;

    #[ORM\Column(type: "bigint")]
    private string $id_user;

    #[ORM\Column(type: "datetime")]
    private \DateTimeInterface $date_ajout;

    public function getId()
    {
        return $this->id;
    }

    public function setId($value)
    {
        $this->id = $value;
    }

    public function getId_salle()
    {
        return $this->id_salle;
    }

    public function setId_salle($value)
    {
        $this->id_salle = $value;
    }

    public function getNom()
    {
        return $this->nom;
    }

    public function setNom($value)
    {
        $this->nom = $value;
    }

    public function getFonctionnement()
    {
        return $this->fonctionnement;
    }

    public function setFonctionnement($value)
    {
        $this->fonctionnement = $value;
    }

    public function getProchaine_verification()
    {
        return $this->prochaine_verification;
    }

    public function setProchaine_verification($value)
    {
        $this->prochaine_verification = $value;
    }

    public function getDerniere_verification()
    {
        return $this->derniere_verification;
    }

    public function setDerniere_verification($value)
    {
        $this->derniere_verification = $value;
    }

    public function getId_user()
    {
        return $this->id_user;
    }

    public function setId_user($value)
    {
        $this->id_user = $value;
    }

    public function getDate_ajout()
    {
        return $this->date_ajout;
    }

    public function setDate_ajout($value)
    {
        $this->date_ajout = $value;
    }
}
