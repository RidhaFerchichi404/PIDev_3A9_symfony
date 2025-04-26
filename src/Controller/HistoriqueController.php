<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Doctrine\ORM\EntityManagerInterface;
use App\Entity\Historique;
use Symfony\Component\Security\Http\Attribute\IsGranted;

class HistoriqueController extends AbstractController
{
    #[Route('/historique', name: 'app_salle_de_sport_historique', methods: ['GET'])]
    #[IsGranted('ROLE_USER')]
    public function historique(EntityManagerInterface $entityManager): Response
    {
        $historiqueRepository = $entityManager->getRepository(Historique::class);
        
        // Si l'utilisateur est admin, afficher tout l'historique
        if ($this->isGranted('ROLE_ADMIN')) {
            $historique = $historiqueRepository->findAll();
        } else {
            // Sinon, afficher uniquement l'historique de l'utilisateur connecté
            $user = $this->getUser();
            $historique = $historiqueRepository->findBy(['user' => $user->getId()]);
        }

        return $this->render('salle_de_sport/historique.html.twig', [
            'historique' => $historique,
        ]);
    }
}
