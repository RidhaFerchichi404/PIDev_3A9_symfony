<?php

namespace App\Repository;

use App\Entity\Comment;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\ORM\Tools\Pagination\Paginator;

class CommentRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Comment::class);
    }

    public function findMostLiked(): array
    {
        return $this->createQueryBuilder('c')
            ->orderBy('c.likes', 'DESC')
            ->getQuery()
            ->getResult();
    }

    public function findAllPaginated(int $page = 1, int $limit = 10, ?string $sort = null): Paginator
    {
        $queryBuilder = $this->createQueryBuilder('c');

        if ($sort === 'most_liked') {
            $queryBuilder->orderBy('c.likes', 'DESC');
        } else {
            $queryBuilder->orderBy('c.date', 'DESC');
        }

        $query = $queryBuilder
            ->setFirstResult(($page - 1) * $limit)
            ->setMaxResults($limit)
            ->getQuery();

        return new Paginator($query);
    }

    public function findByPostPaginated(int $postId, int $page = 1, int $limit = 10, ?string $sort = null): Paginator
    {
        $queryBuilder = $this->createQueryBuilder('c')
            ->andWhere('c.idPost = :postId')
            ->setParameter('postId', $postId);

        if ($sort === 'most_liked') {
            $queryBuilder->orderBy('c.likes', 'DESC');
        } else {
            $queryBuilder->orderBy('c.date', 'DESC');
        }

        $query = $queryBuilder
            ->setFirstResult(($page - 1) * $limit)
            ->setMaxResults($limit)
            ->getQuery();

        return new Paginator($query);
    }

    // Add custom methods as needed
}