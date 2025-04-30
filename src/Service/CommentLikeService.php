<?php

namespace App\Service;

use App\Entity\Comment;
use App\Entity\User;
use App\Repository\CommentLikeRepository;

class CommentLikeService
{
    private CommentLikeRepository $commentLikeRepository;

    public function __construct(CommentLikeRepository $commentLikeRepository)
    {
        $this->commentLikeRepository = $commentLikeRepository;
    }

    public function hasUserLikedComment(User $user, Comment $comment): bool
    {
        return $this->commentLikeRepository->hasUserLikedComment($user, $comment);
    }

    public function getLikeCount(Comment $comment): int
    {
        return $this->commentLikeRepository->countLikesByComment($comment);
    }
} 