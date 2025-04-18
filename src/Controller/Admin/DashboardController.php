<?php

namespace App\Controller\Admin;

use App\Repository\CommandeRepository;
use App\Repository\ProduitRepository;
use App\Service\StaticSession;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/admin')]
class DashboardController extends AbstractController
{
    public function __construct(
        private StaticSession $staticSession
    ) {
    }

    #[Route('/', name: 'app_admin_dashboard')]
    public function index(
        ProduitRepository $produitRepository,
        CommandeRepository $commandeRepository
    ): Response {
        if (!$this->staticSession->isAdmin()) {
            $this->addFlash('error', 'Access denied. Admin privileges required.');
            return $this->redirectToRoute('app_home');
        }

        $totalProducts = $produitRepository->count([]);
        $totalOrders = $commandeRepository->count([]);
        $pendingOrders = $commandeRepository->countByStatus('En attente');
        $completedOrders = $commandeRepository->countByStatus('Livrée');
        $recentOrders = $commandeRepository->findRecentOrders(5);

        return $this->render('admin/dashboard.html.twig', [
            'totalProducts' => $totalProducts,
            'totalOrders' => $totalOrders,
            'pendingOrders' => $pendingOrders,
            'completedOrders' => $completedOrders,
            'recentOrders' => $recentOrders,
        ]);
    }
} 