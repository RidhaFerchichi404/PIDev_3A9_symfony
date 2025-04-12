<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use App\Validator\Constraints\NoBadWords;
use Symfony\Component\Validator\Constraints as Assert;

use App\Entity\User;

#[ORM\Entity]
class Comment
{

    #[ORM\Id]
    #[ORM\Column(type: "integer")]
    private int $id;

    #[ORM\Column(type: "string", length: 255)]
    #[Assert\NotBlank(message: "Comment cannot be empty")]
    #[NoBadWords]
    private string $comment;

    #[ORM\Column(type: "date")]
    private \DateTimeInterface $date;

    #[ORM\Column(type: "integer")]
    private int $likes;

        #[ORM\ManyToOne(targetEntity: Post::class, inversedBy: "comments")]
    #[ORM\JoinColumn(name: 'idPost', referencedColumnName: 'idp', onDelete: 'CASCADE')]
    private Post $idPost;

        #[ORM\ManyToOne(targetEntity: User::class, inversedBy: "comments")]
    #[ORM\JoinColumn(name: 'idUser', referencedColumnName: 'id', onDelete: 'CASCADE')]
    private User $idUser;

    public function getId()
    {
        return $this->id;
    }

    public function setId($value)
    {
        $this->id = $value;
    }

    public function getComment()
    {
        return $this->comment;
    }

    public function setComment($value)
    {
        $this->comment = $value;
    }

    public function getDate()
    {
        return $this->date;
    }

    public function setDate($value)
    {
        $this->date = $value;
    }

    public function getLikes()
    {
        return $this->likes;
    }

    public function setLikes($value)
    {
        $this->likes = $value;
    }

    public function getIdPost()
    {
        return $this->idPost;
    }

    public function setIdPost($value)
    {
        $this->idPost = $value;
    }

    public function getIdUser()
    {
        return $this->idUser;
    }

    public function setIdUser($value)
    {
        $this->idUser = $value;
    }
}
