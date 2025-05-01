<?php

namespace App\Controller\Shop;

use App\Entity\Commande;
use App\Form\CommandeType;
use App\Repository\CommandeRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use App\Service\StaticSession;
use App\Entity\Produit;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Dompdf\Dompdf;
use Dompdf\Options;

#[Route('/shop/commande')]
class CommandeController extends AbstractController
{
    private StaticSession $staticSession;

    public function __construct(StaticSession $staticSession)
    {
        $this->staticSession = $staticSession;
    }

    #[Route('/', name: 'app_commande_index', methods: ['GET'])]
    #[IsGranted('ROLE_USER')]
    public function index(CommandeRepository $commandeRepository): Response
    {
        $user = $this->getUser();
        $commandes = $this->isGranted('ROLE_ADMIN') 
            ? $commandeRepository->findAll() 
            : $commandeRepository->findBy(['user' => $user]);

        return $this->render('commande/index.html.twig', [
            'commandes' => $commandes,
            'role' => $this->staticSession->getRole(),
        ]);
    }

    #[Route('/new/{produit}', name: 'app_commande_new', methods: ['GET', 'POST'])]
    #[IsGranted('ROLE_USER')]
    public function new(Request $request, EntityManagerInterface $entityManager, ?Produit $produit = null): Response
    {
        $commande = new Commande();
        $commande->setUser($this->getUser());
        
        // If a product is provided, pre-select it
        if ($produit) {
            $commande->setProduit($produit);
        }
        
        $form = $this->createForm(CommandeType::class, $commande);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $produit = $commande->getProduit();
            $requestedQuantity = $commande->getQuantite();
            $currentStock = $produit->getQuantiteStock();

            // Check if there's enough stock
            if ($currentStock < $requestedQuantity) {
                $this->addFlash('error', 'Stock insuffisant. Il ne reste que ' . $currentStock . ' unités en stock.');
                return $this->render('commande/new.html.twig', [
                    'commande' => $commande,
                    'form' => $form,
                ]);
            }

            $commande->setDateCommande(new \DateTime());
            $commande->setStatutCommande(Commande::STATUS_PENDING);

            // Update stock
            $produit->setQuantiteStock($currentStock - $requestedQuantity);

            $entityManager->persist($commande);
            $entityManager->flush();

            return $this->redirectToRoute('app_commande_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('commande/new.html.twig', [
            'commande' => $commande,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_commande_show', methods: ['GET'])]
    #[IsGranted('ROLE_USER')]
    public function show(Commande $commande): Response
    {
        // Check if user has access to this order
        if (!$this->isGranted('ROLE_ADMIN') && $commande->getUser() !== $this->getUser()) {
            throw $this->createAccessDeniedException('Access denied.');
        }

        return $this->render('commande/show.html.twig', [
            'commande' => $commande,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_commande_edit', methods: ['GET', 'POST'])]
    #[IsGranted('ROLE_USER')]
    public function edit(Request $request, Commande $commande, EntityManagerInterface $entityManager): Response
    {
        // Check if user has access to this order
        if (!$this->isGranted('ROLE_ADMIN') && $commande->getUser() !== $this->getUser()) {
            throw $this->createAccessDeniedException('Access denied.');
        }

        $originalQuantity = $commande->getQuantite();
        $produit = $commande->getProduit();
        $currentStock = $produit->getQuantiteStock();

        $form = $this->createForm(CommandeType::class, $commande);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $newQuantity = $commande->getQuantite();
            $quantityDifference = $newQuantity - $originalQuantity;

            // Check if there's enough stock for the increased quantity
            if ($quantityDifference > 0 && $currentStock < $quantityDifference) {
                $this->addFlash('error', 'Stock insuffisant. Il ne reste que ' . $currentStock . ' unités en stock.');
                return $this->render('commande/edit.html.twig', [
                    'commande' => $commande,
                    'form' => $form,
                    'available_statuses' => Commande::getAvailableStatuses(),
                ]);
            }

            // Update stock
            $produit->setQuantiteStock($currentStock - $quantityDifference);

            $entityManager->flush();

            return $this->redirectToRoute('app_commande_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('commande/edit.html.twig', [
            'commande' => $commande,
            'form' => $form,
            'available_statuses' => Commande::getAvailableStatuses(),
        ]);
    }

    #[Route('/{id}', name: 'app_commande_delete', methods: ['POST'])]
    #[IsGranted('ROLE_USER')]
    public function delete(Request $request, Commande $commande, EntityManagerInterface $entityManager): Response
    {
        // Check if user has access to this order
        if (!$this->isGranted('ROLE_ADMIN') && $commande->getUser() !== $this->getUser()) {
            throw $this->createAccessDeniedException('Access denied.');
        }

        if ($this->isCsrfTokenValid('delete'.$commande->getId(), $request->request->get('_token'))) {
            $produit = $commande->getProduit();
            $quantity = $commande->getQuantite();
            
            // Only restore stock if the order was not cancelled
            if ($commande->getStatutCommande() !== Commande::STATUS_CANCELLED) {
                $produit->setQuantiteStock($produit->getQuantiteStock() + $quantity);
            }

            $entityManager->remove($commande);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_commande_index', [], Response::HTTP_SEE_OTHER);
    }
    
    #[Route('/{id}/change-status/{status}', name: 'app_commande_change_status', methods: ['GET', 'POST'])]
    #[IsGranted('ROLE_ADMIN')]
    public function changeStatus(Request $request, Commande $commande, string $status, EntityManagerInterface $entityManager): Response
    {
        $availableStatuses = Commande::getAvailableStatuses();
        
        if (in_array($status, $availableStatuses)) {
            $commande->setStatutCommande($status);
            $entityManager->flush();
            
            $this->addFlash('success', 'Le statut de la commande a été modifié avec succès.');
        } else {
            $this->addFlash('danger', 'Statut de commande invalide.');
        }
        
        return $this->redirectToRoute('app_commande_show', ['id' => $commande->getId()], Response::HTTP_SEE_OTHER);
    }

    #[Route('/{id}/pdf', name: 'app_commande_pdf', methods: ['GET'])]
    #[IsGranted('ROLE_USER')]
    public function generatePdf(Commande $commande): Response
    {
        // Check if user has access to this order
        if (!$this->isGranted('ROLE_ADMIN') && $commande->getUser() !== $this->getUser()) {
            throw $this->createAccessDeniedException('Access denied.');
        }

        // Configure Dompdf
        $options = new Options();
        $options->set('isHtml5ParserEnabled', true);
        $options->set('isPhpEnabled', true);

        // Initialize Dompdf
        $dompdf = new Dompdf($options);

        // Generate HTML content
        $html = $this->renderView('commande/order_pdf.html.twig', [
            'commande' => $commande
        ]);

        // Load HTML to Dompdf
        $dompdf->loadHtml($html);

        // Set paper size and orientation
        $dompdf->setPaper('A4', 'portrait');

        // Render the PDF
        $dompdf->render();

        // Generate PDF filename
        $filename = sprintf('order-%s-%s.pdf', 
            $commande->getId(),
            $commande->getDateCommande()->format('Y-m-d')
        );

        // Return the PDF as response
        return new Response(
            $dompdf->output(),
            Response::HTTP_OK,
            [
                'Content-Type' => 'application/pdf',
                'Content-Disposition' => sprintf('attachment; filename="%s"', $filename)
            ]
        );
    }
} 