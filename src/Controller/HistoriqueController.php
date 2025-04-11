<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Doctrine\ORM\EntityManagerInterface;
use App\Entity\Historique;

class HistoriqueController extends AbstractController
{
    #[Route('/historique', name: 'app_salle_de_sport_historique', methods: ['GET'])]
    public function historique(EntityManagerInterface $entityManager): Response
    {
        $historiqueRepository = $entityManager->getRepository(Historique::class);
        $historique = $historiqueRepository->findAll();

        return $this->render('salle_de_sport/historique.html.twig', [
            'historique' => $historique,
        ]);
    }
}
