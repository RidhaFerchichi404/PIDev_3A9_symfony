<?php

namespace App\Twig;

use App\Entity\Comment;
use App\Entity\User;
use App\Service\CommentLikeService;
use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

class CommentLikeExtension extends AbstractExtension
{
    private CommentLikeService $commentLikeService;

    public function __construct(CommentLikeService $commentLikeService)
    {
        $this->commentLikeService = $commentLikeService;
    }

    public function getFunctions(): array
    {
        return [
            new TwigFunction('has_liked_comment', [$this, 'hasLikedComment']),
            new TwigFunction('comment_like_count', [$this, 'getLikeCount']),
        ];
    }

    public function hasLikedComment(User $user = null, Comment $comment = null): bool
    {
        if (!$user || !$comment) {
            return false;
        }

        return $this->commentLikeService->hasUserLikedComment($user, $comment);
    }

    public function getLikeCount(Comment $comment): int
    {
        return $this->commentLikeService->getLikeCount($comment);
    }
} 