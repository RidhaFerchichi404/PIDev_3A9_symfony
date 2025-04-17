<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\ArrayCollection;
use App\Entity\Abonnement;

#[ORM\Entity]
#[ORM\Table(name: 'salledesport')]
class Salledesport
{

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(name: 'SalleID', type: 'integer')]
    private ?int $SalleID = null;

    #[ORM\Column(type: "string", length: 100)]
    private string $Nom;

    #[ORM\Column(type: "string", length: 255)]
    private string $Adresse;

    #[ORM\Column(type: "string", length: 100)]
    private string $Ville;

    
    #[ORM\Column(name: 'CodePostal', type: 'string', length: 10, nullable: true)]
    private ?string $codePostal = null;


    #[ORM\Column(type: "string", length: 20)]
    private string $Telephone;

    #[ORM\Column(type: "string", length: 100)]
    private string $Email;

    #[ORM\Column(type: "datetime")]
    private \DateTimeInterface $DateCreation;

   

    public function __construct()
    {
        
        $this->DateCreation = new \DateTime();
    }

    public function getSalleID(): mixed
    {
        return $this->SalleID;
    }

    public function setSalleID(mixed $SalleID): static
    {
        $this->SalleID = $SalleID;
        return $this;
    }
    public function getNom(): mixed
    {
        return $this->Nom;
    }

    public function setNom(mixed $Nom): static
    {
        $this->Nom = $Nom;
        return $this;
    }

    public function getAdresse(): mixed
    {
        return $this->Adresse;
    }

    public function setAdresse(mixed $Adresse): static
    {
        $this->Adresse = $Adresse;
        return $this;
    }

    public function getVille(): mixed
    {
        return $this->Ville;
    }

    public function setVille(mixed $Ville): static
    {
        $this->Ville = $Ville;
        return $this;
    }

    public function getCodePostal(): ?string
    {
        return $this->codePostal;
    }

    public function setCodePostal(?string $codePostal): self
    {
        $this->codePostal = $codePostal;
        return $this;
    }

    public function getTelephone(): mixed
    {
        return $this->Telephone;
    }

    public function setTelephone(mixed $Telephone): static
    {
        $this->Telephone = $Telephone;
        return $this;
    }

    public function getEmail(): mixed
    {
        return $this->Email;
    }

    public function setEmail(mixed $Email): static
    {
        $this->Email = $Email;
        return $this;
    }

    public function getDateCreation(): mixed
    {
        return $this->DateCreation;
    }

    public function setDateCreation(mixed $DateCreation): static
    {
        $this->DateCreation = $DateCreation;
        return $this;
    }

    

    

     
    }
