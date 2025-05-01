<?php

namespace App\Repository;

use App\Entity\Commande;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Commande>
 */
class CommandeRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Commande::class);
    }

    //    /**
    //     * @return Commande[] Returns an array of Commande objects
    //     */
    //    public function findByExampleField($value): array
    //    {
    //        return $this->createQueryBuilder('c')
    //            ->andWhere('c.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->orderBy('c.id', 'ASC')
    //            ->setMaxResults(10)
    //            ->getQuery()
    //            ->getResult()
    //        ;
    //    }

    //    public function findOneBySomeField($value): ?Commande
    //    {
    //        return $this->createQueryBuilder('c')
    //            ->andWhere('c.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }

    public function countByStatus(string $status): int
    {
        return $this->createQueryBuilder('c')
            ->select('COUNT(c.id)')
            ->where('c.statutCommande = :status')
            ->setParameter('status', $status)
            ->getQuery()
            ->getSingleScalarResult();
    }

    public function findRecentOrders(int $limit = 5): array
    {
        return $this->createQueryBuilder('c')
            ->orderBy('c.dateCommande', 'DESC')
            ->setMaxResults($limit)
            ->getQuery()
            ->getResult();
    }

    public function findByFilters(
        string $search = '',
        string $status = '',
        string $sortBy = 'dateCommande',
        string $direction = 'DESC',
        string $dateFrom = '',
        string $dateTo = '',
        int $page = 1,
        int $limit = 10
    ): array {
        $qb = $this->createQueryBuilder('c')
            ->leftJoin('c.produit', 'p')
            ->leftJoin('c.user', 'u');

        // Apply search filter
        if ($search) {
            $qb->andWhere('
                c.nomClient LIKE :search OR 
                c.telephone LIKE :search OR 
                c.adresseLivraison LIKE :search OR
                p.nom LIKE :search OR
                u.email LIKE :search
            ')
            ->setParameter('search', '%' . $search . '%');
        }

        // Apply status filter
        if ($status) {
            $qb->andWhere('c.statutCommande = :status')
               ->setParameter('status', $status);
        }

        // Apply date range filter
        if ($dateFrom) {
            $qb->andWhere('c.dateCommande >= :dateFrom')
               ->setParameter('dateFrom', new \DateTime($dateFrom));
        }
        if ($dateTo) {
            $qb->andWhere('c.dateCommande <= :dateTo')
               ->setParameter('dateTo', new \DateTime($dateTo));
        }

        // Apply sorting
        $qb->orderBy('c.' . $sortBy, $direction);

        // Apply pagination
        $qb->setFirstResult(($page - 1) * $limit)
           ->setMaxResults($limit);

        return $qb->getQuery()->getResult();
    }

    public function countByFilters(
        string $search = '',
        string $status = '',
        string $dateFrom = '',
        string $dateTo = ''
    ): int {
        $qb = $this->createQueryBuilder('c')
            ->select('COUNT(c.id)')
            ->leftJoin('c.produit', 'p')
            ->leftJoin('c.user', 'u');

        // Apply search filter
        if ($search) {
            $qb->andWhere('
                c.nomClient LIKE :search OR 
                c.telephone LIKE :search OR 
                c.adresseLivraison LIKE :search OR
                p.nom LIKE :search OR
                u.email LIKE :search
            ')
            ->setParameter('search', '%' . $search . '%');
        }

        // Apply status filter
        if ($status) {
            $qb->andWhere('c.statutCommande = :status')
               ->setParameter('status', $status);
        }

        // Apply date range filter
        if ($dateFrom) {
            $qb->andWhere('c.dateCommande >= :dateFrom')
               ->setParameter('dateFrom', new \DateTime($dateFrom));
        }
        if ($dateTo) {
            $qb->andWhere('c.dateCommande <= :dateTo')
               ->setParameter('dateTo', new \DateTime($dateTo));
        }

        return (int) $qb->getQuery()->getSingleScalarResult();
    }
}
