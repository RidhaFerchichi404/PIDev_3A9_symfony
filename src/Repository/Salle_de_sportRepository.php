<?php

namespace App\Repository;

use App\Entity\Salle_de_sport;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

class Salle_de_sportRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Salle_de_sport::class);
    }

    // Add custom methods as needed
}