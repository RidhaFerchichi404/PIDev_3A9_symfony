<?php

namespace App\Repository;

use App\Entity\Promotion;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Promotion>
 *
 * @method Promotion|null find($id, $lockMode = null, $lockVersion = null)
 * @method Promotion|null findOneBy(array $criteria, array $orderBy = null)
 * @method Promotion[]    findAll()
 * @method Promotion[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PromotionRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Promotion::class);
    }

    /**
     * Trouve les promotions par abonnement
     */
    public function findByAbonnement($abonnementId): array
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.abonnement = :abonnementId')
            ->setParameter('abonnementId', $abonnementId)
            ->orderBy('p.dateDebut', 'DESC')
            ->getQuery()
            ->getResult();
    }

    /**
     * Trouve les promotions actives
     */
    public function findActivePromotions(): array
    {
        $now = new \DateTime();
        
        return $this->createQueryBuilder('p')
            ->andWhere('p.dateDebut <= :now')
            ->andWhere('p.dateFin >= :now')
            ->setParameter('now', $now)
            ->orderBy('p.valeurReduction', 'DESC')
            ->getQuery()
            ->getResult();
    }

    /**
     * Trouve les promotions avec leurs relations
     */
    public function findAllWithRelations(): array
    {
        return $this->createQueryBuilder('p')
            ->leftJoin('p.abonnement', 'a')
            ->addSelect('a')
            ->leftJoin('p.salle', 's')
            ->addSelect('s')
            ->orderBy('p.dateCreation', 'DESC')
            ->getQuery()
            ->getResult();
    }

    /**
     * Trouve les promotions par salle
     */
    public function findBySalle($salleId): array
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.salle = :salleId')
            ->setParameter('salleId', $salleId)
            ->orderBy('p.dateFin', 'ASC')
            ->getQuery()
            ->getResult();
    }

    /**
     * Sauvegarde une promotion
     */
    public function save(Promotion $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    /**
     * Supprime une promotion
     */
    public function remove(Promotion $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    /**
     * Trouve les promotions qui expirent bientôt (dans les 7 jours)
     */
    public function findExpiringSoon(): array
    {
        $now = new \DateTime();
        $inOneWeek = (new \DateTime())->modify('+7 days');
        
        return $this->createQueryBuilder('p')
            ->andWhere('p.dateFin BETWEEN :now AND :inOneWeek')
            ->setParameter('now', $now)
            ->setParameter('inOneWeek', $inOneWeek)
            ->orderBy('p.dateFin', 'ASC')
            ->getQuery()
            ->getResult();
    }
}