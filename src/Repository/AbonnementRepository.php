<?php

namespace App\Repository;

use App\Entity\Abonnement;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Abonnement>
 *
 * @method Abonnement|null find($id, $lockMode = null, $lockVersion = null)
 * @method Abonnement|null findOneBy(array $criteria, array $orderBy = null)
 * @method Abonnement[]    findAll()
 * @method Abonnement[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class AbonnementRepository extends ServiceEntityRepository
{


    /**
     * Trouve les abonnements par salle de sport
     */
    public function findBySalle(int $salleId): array
    {
        return $this->createQueryBuilder('a')
            ->andWhere('a.salle = :salleId')
            ->setParameter('salleId', $salleId)
            ->orderBy('a.Prix', 'ASC')
            ->getQuery()
            ->getResult();
    }

    /**
     * Trouve les abonnements par prix maximum
     */
    public function findByMaxPrice(float $maxPrice): array
    {
        return $this->createQueryBuilder('a')
            ->andWhere('a.Prix <= :maxPrice')
            ->setParameter('maxPrice', $maxPrice)
            ->orderBy('a.Prix', 'ASC')
            ->getQuery()
            ->getResult();
    }
// src/Repository/AbonnementRepository.php

public function findAllWithPromotions()
{
    return $this->createQueryBuilder('a')
        ->leftJoin('a.promotions', 'p')
        ->addSelect('p')
        ->getQuery()
        ->getResult();
}

public function __construct(ManagerRegistry $registry)
{
    parent::__construct($registry, Abonnement::class);
    
    // Solution correcte pour accéder à l'EntityManager
    $entityManager = $registry->getManagerForClass(Abonnement::class);
    $classMetadata = $entityManager->getClassMetadata(Abonnement::class);
    
    // Filtrez les fieldMappings si nécessaire
    $classMetadata->fieldMappings = array_filter(
        $classMetadata->fieldMappings,
        fn($field) => $field['fieldName'] !== 'dateCreation'
    );
}
    
}
