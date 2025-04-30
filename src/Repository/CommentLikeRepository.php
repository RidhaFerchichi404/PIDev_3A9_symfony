<?php

namespace App\Repository;

use App\Entity\CommentLike;
use App\Entity\User;
use App\Entity\Comment;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

class CommentLikeRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, CommentLike::class);
    }

    public function findOneByUserAndComment(User $user, Comment $comment): ?CommentLike
    {
        return $this->createQueryBuilder('cl')
            ->andWhere('cl.user = :user')
            ->andWhere('cl.comment = :comment')
            ->setParameter('user', $user)
            ->setParameter('comment', $comment)
            ->getQuery()
            ->getOneOrNullResult();
    }

    public function hasUserLikedComment(User $user, Comment $comment): bool
    {
        $result = $this->createQueryBuilder('cl')
            ->select('COUNT(cl.id)')
            ->andWhere('cl.user = :user')
            ->andWhere('cl.comment = :comment')
            ->setParameter('user', $user)
            ->setParameter('comment', $comment)
            ->getQuery()
            ->getSingleScalarResult();
        
        return (int) $result > 0;
    }

    public function countLikesByComment(Comment $comment): int
    {
        return (int) $this->createQueryBuilder('cl')
            ->select('COUNT(cl.id)')
            ->andWhere('cl.comment = :comment')
            ->setParameter('comment', $comment)
            ->getQuery()
            ->getSingleScalarResult();
    }
} 