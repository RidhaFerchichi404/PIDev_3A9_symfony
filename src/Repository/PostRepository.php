<?php

namespace App\Repository;

use App\Entity\Post;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\ORM\Tools\Pagination\Paginator;

class PostRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Post::class);
    }

    public function findByType(string $type, int $page = 1, int $limit = 10): Paginator
    {
        $query = $this->createQueryBuilder('p')
            ->andWhere('p.type = :type')
            ->setParameter('type', $type)
            ->orderBy('p.dateU', 'DESC')
            ->setFirstResult(($page - 1) * $limit)
            ->setMaxResults($limit)
            ->getQuery();

        return new Paginator($query);
    }

    public function findMostCommented(int $page = 1, int $limit = 10): Paginator
    {
        $subQuery = $this->createQueryBuilder('p2')
            ->select('COUNT(c2.id)')
            ->leftJoin('p2.comments', 'c2')
            ->where('p2.idp = p.idp')
            ->getDQL();

        $query = $this->createQueryBuilder('p')
            ->addSelect("($subQuery) as HIDDEN commentCount")
            ->orderBy('commentCount', 'DESC')
            ->setFirstResult(($page - 1) * $limit)
            ->setMaxResults($limit)
            ->getQuery();

        return new Paginator($query);
    }

    public function findAllPaginated(int $page = 1, int $limit = 10): Paginator
    {
        $query = $this->createQueryBuilder('p')
            ->orderBy('p.dateU', 'DESC')
            ->setFirstResult(($page - 1) * $limit)
            ->setMaxResults($limit)
            ->getQuery();

        return new Paginator($query);
    }

    public function countByType(): array
    {
        return $this->createQueryBuilder('p')
            ->select('p.type, COUNT(p.idp) as count')
            ->groupBy('p.type')
            ->getQuery()
            ->getResult();
    }

    // Add custom methods as needed
}