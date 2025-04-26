<?php

namespace App\Controller;

use App\Entity\SalleDeSport;
use App\Repository\SalleDeSportRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

class GymDashboardController extends AbstractController
{
    #[Route('/gym/dashboard', name: 'app_gym_dashboard')]
    #[IsGranted('ROLE_ADMIN')]
    public function index(SalleDeSportRepository $salleRepository): Response
    {
        // Récupérer toutes les salles de sport
        $gyms = $salleRepository->findAll();
        
        return $this->render('gym_dashboard/index.html.twig', [
            'gyms' => $gyms,
        ]);
    }
    
    #[Route('/gym/{id}/equipments', name: 'app_gym_equipments')]
    public function equipments(SalleDeSport $gym): Response
    {
        return $this->render('gym_dashboard/equipments.html.twig', [
            'gym' => $gym,
        ]);
    }
    
    #[Route('/gym/{id}/download', name: 'app_gym_download_pdf')]
    public function downloadPdf(SalleDeSport $gym): Response
    {
        // Ici vous pouvez ajouter la logique pour générer un PDF
        // Pour l'instant, nous allons simplement rediriger vers la page des équipements
        
        $this->addFlash('info', 'La fonctionnalité de téléchargement PDF sera disponible prochainement.');
        
        return $this->redirectToRoute('app_gym_equipments', ['id' => $gym->getId()]);
    }
} 