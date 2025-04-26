<?php

namespace App\Entity;

use App\Repository\UserRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;


#[ORM\Entity(repositoryClass: UserRepository::class)]
#[ORM\HasLifecycleCallbacks]
class User implements UserInterface, PasswordAuthenticatedUserInterface
{

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: "integer")]
    private ?int $id = null;

    #[ORM\Column(type: "string", length: 255)]
    private string $first_name;

    #[ORM\Column(type: "string", length: 255)]
    private string $last_name;

    #[ORM\Column(type: "string", length: 255, unique: true)]
    private string $email;

    #[ORM\Column(type: "string", length: 255)]
    private string $password_hash = '';

    #[ORM\Column(type: "string", length: 20, nullable: true)]
    private ?string $phone_number = null;

    #[ORM\Column(type: "string", length: 50)]
    private string $role = 'ROLE_USER';

    #[ORM\Column(type: "date")]
    private \DateTimeInterface $subscription_end_date;

    #[ORM\Column(type: "boolean")]
    private bool $is_active = true;

    #[ORM\Column(type: "datetime")]
    private \DateTimeInterface $created_at;

    #[ORM\Column(type: "datetime")]
    private \DateTimeInterface $updated_at;

    #[ORM\Column(type: "integer")]
    private int $violation_count = 0;

    #[ORM\Column(type: "string", length: 255, nullable: true)]
    private ?string $location = null;

    #[ORM\Column(type: "string", length: 20, nullable: true)]
    private ?string $cin = null;

    #[ORM\Column(type: "integer", nullable: true)]
    private ?int $age = null;

    #[ORM\Column(type: "string", length: 255, nullable: true)]
    private ?string $reset_token = null;

    #[ORM\Column(type: "datetime_immutable", nullable: true)]
    private ?\DateTimeImmutable $reset_token_expires_at = null;

    #[ORM\Column(type: "string", length: 10, nullable: true)]
    private ?string $verification_code = null;

    #[ORM\Column(type: "datetime_immutable", nullable: true)]
    private ?\DateTimeImmutable $verification_code_expires_at = null;

    public function __construct()
    {
        // Initialisation des dates
        $this->created_at = new \DateTime();
        $this->updated_at = new \DateTime();
        
        // Date d'abonnement par défaut à un mois
        $this->subscription_end_date = new \DateTime('+1 month');
    }

    #[ORM\PrePersist]
    public function setCreatedAtValue(): void
    {
        $this->created_at = new \DateTime();
        $this->updated_at = new \DateTime();
    }

    #[ORM\PreUpdate]
    public function setUpdatedAtValue(): void
    {
        $this->updated_at = new \DateTime();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getFirstname(): string
    {
        return $this->first_name;
    }

    public function setFirstname(string $value): self
    {
        $this->first_name = $value;
        return $this;
    }

    public function getLastname(): string
    {
        return $this->last_name;
    }

    public function setLastname(string $value): self
    {
        $this->last_name = $value;
        return $this;
    }

    public function getEmail(): string
    {
        return $this->email;
    }

    public function setEmail(string $value): self
    {
        $this->email = $value;
        return $this;
    }

    /**
     * @return string
     */
    public function getUserIdentifier(): string
    {
        return $this->email;
    }

    /**
     * @return array
     */
    public function getRoles(): array
    {
        return [$this->role];
    }

    /**
     * @return string
     */
    public function getPassword(): string
    {
        return $this->password_hash;
    }

    /**
     * Supprime les données sensibles de l'utilisateur.
     */
    public function eraseCredentials(): void
    {
        // Ne rien faire car nous n'avons pas de mot de passe temporaire
    }

    /**
     * @return string|null
     */
    public function getBadge(): ?string
    {
        return null;
    }

    public function getPasswordhash(): string
    {
        return $this->password_hash;
    }

    public function setPasswordHash(string $value): self
    {
        // Assurez-vous que le mot de passe n'est jamais null
        $this->password_hash = $value ?: '';
        return $this;
    }

    public function getPhonenumber(): ?string
    {
        return $this->phone_number;
    }

    public function setPhonenumber(?string $value): self
    {
        $this->phone_number = $value;
        return $this;
    }

    public function getRole(): string
    {
        return $this->role;
    }

    public function setRole(string $value): self
    {
        $this->role = $value;
        return $this;
    }

    public function getSubscriptionenddate(): \DateTimeInterface
    {
        return $this->subscription_end_date;
    }

    public function setSubscriptionenddate(\DateTimeInterface $value): self
    {
        $this->subscription_end_date = $value;
        return $this;
    }

    public function getIsactive(): bool
    {
        return $this->is_active;
    }

    public function setIsactive(bool $value): self
    {
        $this->is_active = $value;
        return $this;
    }

    public function getCreatedat(): \DateTimeInterface
    {
        return $this->created_at;
    }

    public function setCreatedat(\DateTimeInterface $value): self
    {
        $this->created_at = $value;
        return $this;
    }

    public function getUpdatedat(): \DateTimeInterface
    {
        return $this->updated_at;
    }

    public function setUpdatedat(\DateTimeInterface $value): self
    {
        $this->updated_at = $value;
        return $this;
    }

    public function getViolationcount(): int
    {
        return $this->violation_count;
    }

    public function setViolationcount(int $value): self
    {
        $this->violation_count = $value;
        return $this;
    }

    public function getLocation(): ?string
    {
        return $this->location;
    }

    public function setLocation(?string $value): self
    {
        $this->location = $value;
        return $this;
    }

    public function getCin(): ?string
    {
        return $this->cin;
    }

    public function setCin(?string $value): self
    {
        $this->cin = $value;
        return $this;
    }

    public function getAge(): ?int
    {
        return $this->age;
    }

    public function setAge(?int $value): self
    {
        $this->age = $value;
        return $this;
    }
    
    public function getResetToken(): ?string
    {
        return $this->reset_token;
    }
    
    public function setResetToken(?string $resetToken): self
    {
        $this->reset_token = $resetToken;
        return $this;
    }
    
    public function getResetTokenExpiresAt(): ?\DateTimeImmutable
    {
        return $this->reset_token_expires_at;
    }
    
    public function setResetTokenExpiresAt(?\DateTimeImmutable $resetTokenExpiresAt): self
    {
        $this->reset_token_expires_at = $resetTokenExpiresAt;
        return $this;
    }
    
    public function getVerificationCode(): ?string
    {
        return $this->verification_code;
    }
    
    public function setVerificationCode(?string $verificationCode): self
    {
        $this->verification_code = $verificationCode;
        return $this;
    }
    
    public function getVerificationCodeExpiresAt(): ?\DateTimeImmutable
    {
        return $this->verification_code_expires_at;
    }
    
    public function setVerificationCodeExpiresAt(?\DateTimeImmutable $verificationCodeExpiresAt): self
    {
        $this->verification_code_expires_at = $verificationCodeExpiresAt;
        return $this;
    }
    
    public function getFullName(): string
    {
        return $this->first_name . ' ' . $this->last_name;
    }
}
