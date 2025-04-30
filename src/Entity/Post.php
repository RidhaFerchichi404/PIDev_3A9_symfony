<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use App\Validator\Constraints\NoBadWords;

use App\Entity\User;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\ArrayCollection;
use App\Entity\Comment;

#[ORM\Entity]
class Post
{

    #[ORM\Id]
    #[ORM\Column(type: "integer", name:"idp")]
    #[ORM\GeneratedValue]
    private int $idp;

    #[ORM\Column(type: "string", length: 255)]
    #[Assert\NotBlank(message: "Description cannot be empty")]
    #[NoBadWords]
    private string $description;

    #[ORM\Column(type: "date", name: "date_u")]
    private \DateTimeInterface $dateU;

    #[ORM\Column(type: "string", length: 255)]
    private string $image;

    #[ORM\Column(type: "string", length: 255)]
    private string $type;

    #[ORM\ManyToOne(targetEntity: User::class, inversedBy: "posts")]
    #[ORM\JoinColumn(name: 'idUser', referencedColumnName: 'id', onDelete: 'CASCADE')]
    private User $idUser;

    public function __construct()
    {
        $this->comments = new ArrayCollection();
    }

    public function getId(): int
    {
        return $this->idp;
    }

    public function setId(int $id): self
    {
        $this->idp = $id;
        return $this;
    }

    public function getDescription()
    {
        return $this->description;
    }

    public function setDescription($value)
    {
        $this->description = $value;
    }

    public function getDateU()
    {
        return $this->dateU;
    }

    public function setDateU($value)
    {
        $this->dateU = $value;
    }

    public function getImage()
    {
        return $this->image;
    }

    public function setImage($value)
    {
        $this->image = $value;
    }

    public function getType()
    {
        return $this->type;
    }

    public function setType($value)
    {
        $this->type = $value;
    }

    public function getIdUser()
    {
        return $this->idUser;
    }

    public function setIdUser($value)
    {
        $this->idUser = $value;
    }

    #[ORM\OneToMany(mappedBy: "idPost", targetEntity: Comment::class)]
    private Collection $comments;

    public function getComments(): Collection
    {
        return $this->comments;
    }

    public function addComment(Comment $comment): self
    {
        if (!$this->comments->contains($comment)) {
            $this->comments[] = $comment;
            $comment->setIdPost($this);
        }

        return $this;
    }

    public function removeComment(Comment $comment): self
    {
        if ($this->comments->removeElement($comment)) {
            // set the owning side to null (unless already changed)
            if ($comment->getIdPost() === $this) {
                $comment->setIdPost(null);
            }
        }

        return $this;
    }

    public function deletePost($post, $request, $entityManager): void
    {
        if ($this->isCsrfTokenValid('delete'.$post->getId(), $request->get('_token'))) {
            $entityManager->remove($post);
            $entityManager->flush();
        }
    }
}
