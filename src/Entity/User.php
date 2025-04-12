<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

use Doctrine\Common\Collections\Collection;
use App\Entity\Comment;

#[ORM\Entity]
class User
{

    #[ORM\Id]
    #[ORM\Column(type: "bigint")]
    private string $id;

    #[ORM\Column(type: "string", length: 100)]
    private string $first_name;

    #[ORM\Column(type: "string", length: 100)]
    private string $last_name;

    #[ORM\Column(type: "string", length: 255)]
    private string $email;

    #[ORM\Column(type: "string", length: 255)]
    private string $password_hash;

    #[ORM\Column(type: "string", length: 20)]
    private string $phone_number;

    #[ORM\Column(type: "string", length: 50)]
    private string $role;

    #[ORM\Column(type: "date")]
    private \DateTimeInterface $subscription_end_date;

    #[ORM\Column(type: "boolean")]
    private bool $is_active;

    #[ORM\Column(type: "datetime")]
    private \DateTimeInterface $created_at;

    #[ORM\Column(type: "datetime")]
    private \DateTimeInterface $updated_at;

    #[ORM\Column(type: "integer")]
    private int $violation_count;

    #[ORM\Column(type: "string", length: 255)]
    private string $location;

    #[ORM\Column(type: "string", length: 20)]
    private string $cin;

    #[ORM\Column(type: "integer")]
    private int $age;

    public function getId()
    {
        return $this->id;
    }

    public function setId($value)
    {
        $this->id = $value;
    }

    public function getFirst_name()
    {
        return $this->first_name;
    }

    public function setFirst_name($value)
    {
        $this->first_name = $value;
    }

    public function getLast_name()
    {
        return $this->last_name;
    }

    public function setLast_name($value)
    {
        $this->last_name = $value;
    }

    public function getEmail()
    {
        return $this->email;
    }

    public function setEmail($value)
    {
        $this->email = $value;
    }

    public function getPassword_hash()
    {
        return $this->password_hash;
    }

    public function setPassword_hash($value)
    {
        $this->password_hash = $value;
    }

    public function getPhone_number()
    {
        return $this->phone_number;
    }

    public function setPhone_number($value)
    {
        $this->phone_number = $value;
    }

    public function getRole()
    {
        return $this->role;
    }

    public function setRole($value)
    {
        $this->role = $value;
    }

    public function getSubscription_end_date()
    {
        return $this->subscription_end_date;
    }

    public function setSubscription_end_date($value)
    {
        $this->subscription_end_date = $value;
    }

    public function getIs_active()
    {
        return $this->is_active;
    }

    public function setIs_active($value)
    {
        $this->is_active = $value;
    }

    public function getCreated_at()
    {
        return $this->created_at;
    }

    public function setCreated_at($value)
    {
        $this->created_at = $value;
    }

    public function getUpdated_at()
    {
        return $this->updated_at;
    }

    public function setUpdated_at($value)
    {
        $this->updated_at = $value;
    }

    public function getViolation_count()
    {
        return $this->violation_count;
    }

    public function setViolation_count($value)
    {
        $this->violation_count = $value;
    }

    public function getLocation()
    {
        return $this->location;
    }

    public function setLocation($value)
    {
        $this->location = $value;
    }

    public function getCin()
    {
        return $this->cin;
    }

    public function setCin($value)
    {
        $this->cin = $value;
    }

    public function getAge()
    {
        return $this->age;
    }

    public function setAge($value)
    {
        $this->age = $value;
    }

    #[ORM\OneToMany(mappedBy: "idUser", targetEntity: Post::class)]
    private Collection $posts;

        public function getPosts(): Collection
        {
            return $this->posts;
        }
    
        public function addPost(Post $post): self
        {
            if (!$this->posts->contains($post)) {
                $this->posts[] = $post;
                $post->setIdUser($this);
            }
    
            return $this;
        }
    
        public function removePost(Post $post): self
        {
            if ($this->posts->removeElement($post)) {
                // set the owning side to null (unless already changed)
                if ($post->getIdUser() === $this) {
                    $post->setIdUser(null);
                }
            }
    
            return $this;
        }

    #[ORM\OneToMany(mappedBy: "id_user", targetEntity: Commandes::class)]
    private Collection $commandess;

        public function getCommandess(): Collection
        {
            return $this->commandess;
        }
    
        public function addCommandes(Commandes $commandes): self
        {
            if (!$this->commandess->contains($commandes)) {
                $this->commandess[] = $commandes;
                $commandes->setId_user($this);
            }
    
            return $this;
        }
    
        public function removeCommandes(Commandes $commandes): self
        {
            if ($this->commandess->removeElement($commandes)) {
                // set the owning side to null (unless already changed)
                if ($commandes->getId_user() === $this) {
                    $commandes->setId_user(null);
                }
            }
    
            return $this;
        }

    #[ORM\OneToMany(mappedBy: "idUser", targetEntity: Comment::class)]
    private Collection $comments;
}
