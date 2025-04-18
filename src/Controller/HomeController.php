<?php

namespace App\Controller;

use App\Repository\ProduitRepository;
use App\Repository\CommandeRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class HomeController extends AbstractController
{
    #[Route('/', name: 'app_home')]
    public function index(ProduitRepository $produitRepository, CommandeRepository $commandeRepository): Response
    {
        $productCount = count($produitRepository->findAll());
        $orderCount = count($commandeRepository->findAll());
        
        return $this->render('home/index.html.twig', [
            'productCount' => $productCount,
            'orderCount' => $orderCount,
        ]);
    }
} 