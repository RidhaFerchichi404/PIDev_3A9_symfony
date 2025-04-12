<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;


#[ORM\Entity]
class Exercice
{

    #[ORM\Id]
    #[ORM\Column(type: "bigint")]
    private string $id;

    #[ORM\Column(type: "text")]
    private string $description;

    #[ORM\Column(type: "string", length: 255)]
    private string $image;

    #[ORM\Column(type: "bigint")]
    private string $id_user;

    #[ORM\Column(type: "bigint")]
    private string $id_equipement;

    #[ORM\Column(type: "string", length: 100)]
    private string $nom_exercice;

    public function getId()
    {
        return $this->id;
    }

    public function setId($value)
    {
        $this->id = $value;
    }

    public function getDescription()
    {
        return $this->description;
    }

    public function setDescription($value)
    {
        $this->description = $value;
    }

    public function getImage()
    {
        return $this->image;
    }

    public function setImage($value)
    {
        $this->image = $value;
    }

    public function getId_user()
    {
        return $this->id_user;
    }

    public function setId_user($value)
    {
        $this->id_user = $value;
    }

    public function getId_equipement()
    {
        return $this->id_equipement;
    }

    public function setId_equipement($value)
    {
        $this->id_equipement = $value;
    }

    public function getNom_exercice()
    {
        return $this->nom_exercice;
    }

    public function setNom_exercice($value)
    {
        $this->nom_exercice = $value;
    }
}
