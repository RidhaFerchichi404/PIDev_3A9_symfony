<?php

namespace App\Controller;

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

#[Route('/commande')]
class CommandeController extends AbstractController
{
    private StaticSession $staticSession;

    public function __construct(StaticSession $staticSession)
    {
        $this->staticSession = $staticSession;
    }

    /**
     * READ Operation - List all orders
     * This method displays all orders for admins, but only shows orders for the current user if they're a client
     */
    #[Route('/', name: 'app_commande_index', methods: ['GET'])]
    public function index(CommandeRepository $commandeRepository): Response
    {
        $role = $this->staticSession->getRole();
        // If user is admin, show all orders. If client, only show their orders (using example user ID 1)
        $commandes = $role === 'admin' ? $commandeRepository->findAll() : $commandeRepository->findBy(['userId' => 1]);

        return $this->render('commande/index.html.twig', [
            'commandes' => $commandes,
            'role' => $role,
        ]);
    }

    /**
     * CREATE Operation - Create a new order
     * This method handles both GET (display form) and POST (process form submission) requests
     * It includes stock validation and automatic status setting
     */
    #[Route('/new/{produit}', name: 'app_commande_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager, ?Produit $produit = null): Response
    {
        // Security check: Only clients and admins can create orders
        if (!$this->staticSession->isAdmin() && !$this->staticSession->isClient()) {
            throw $this->createAccessDeniedException('Access denied.');
        }

        $commande = new Commande();
        
        // If a product is provided in the URL, pre-select it in the form
        if ($produit) {
            $commande->setProduit($produit);
        }
        
        $form = $this->createForm(CommandeType::class, $commande);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $produit = $commande->getProduit();
            $requestedQuantity = $commande->getQuantite();
            $currentStock = $produit->getQuantiteStock();

            // Stock validation: Check if there's enough stock available
            if ($currentStock < $requestedQuantity) {
                $this->addFlash('error', 'Stock insuffisant. Il ne reste que ' . $currentStock . ' unités en stock.');
                return $this->render('commande/new.html.twig', [
                    'commande' => $commande,
                    'form' => $form,
                ]);
            }

            // Set order details
            $commande->setDateCommande(new \DateTime());
            $commande->setStatutCommande(Commande::STATUS_PENDING);
            $commande->setUserId(1); // Example user ID

            // Update product stock
            $produit->setQuantiteStock($currentStock - $requestedQuantity);

            // Save the new order
            $entityManager->persist($commande);
            $entityManager->flush();

            return $this->redirectToRoute('app_commande_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('commande/new.html.twig', [
            'commande' => $commande,
            'form' => $form,
        ]);
    }

    /**
     * READ Operation - Show order details
     * This method displays the details of a specific order
     */
    #[Route('/{id}', name: 'app_commande_show', methods: ['GET'])]
    public function show(Commande $commande): Response
    {
        return $this->render('commande/show.html.twig', [
            'commande' => $commande,
        ]);
    }

    /**
     * UPDATE Operation - Edit an existing order
     * This method handles both GET (display edit form) and POST (process form submission) requests
     * It includes stock validation and handles quantity changes
     */
    #[Route('/{id}/edit', name: 'app_commande_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Commande $commande, EntityManagerInterface $entityManager): Response
    {
        // Security check: Only admins and the order owner (client) can edit orders
        if (!$this->staticSession->isAdmin() && 
            (!$this->staticSession->isClient() || $commande->getUserId() !== 1)) {
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

            // Stock validation: Check if there's enough stock for increased quantity
            if ($quantityDifference > 0 && $currentStock < $quantityDifference) {
                $this->addFlash('error', 'Stock insuffisant. Il ne reste que ' . $currentStock . ' unités en stock.');
                return $this->render('commande/edit.html.twig', [
                    'commande' => $commande,
                    'form' => $form,
                    'available_statuses' => Commande::getAvailableStatuses(),
                ]);
            }

            // Update product stock based on quantity change
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

    /**
     * DELETE Operation - Remove an order
     * This method handles the deletion of an order and restores product stock
     * It includes security checks and CSRF protection
     */
    #[Route('/{id}', name: 'app_commande_delete', methods: ['POST'])]
    public function delete(Request $request, Commande $commande, EntityManagerInterface $entityManager): Response
    {
        // Security check: Only admins and the order owner (client) can delete orders
        if (!$this->staticSession->isAdmin() && 
            (!$this->staticSession->isClient() || $commande->getUserId() !== 1)) {
            throw $this->createAccessDeniedException('Access denied.');
        }

        // CSRF protection check
        if ($this->isCsrfTokenValid('delete'.$commande->getId(), $request->request->get('_token'))) {
            $produit = $commande->getProduit();
            $quantity = $commande->getQuantite();
            
            // Restore product stock if the order wasn't already cancelled
            if ($commande->getStatutCommande() !== Commande::STATUS_CANCELLED) {
                $produit->setQuantiteStock($produit->getQuantiteStock() + $quantity);
            }

            // Remove the order from database
            $entityManager->remove($commande);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_commande_index', [], Response::HTTP_SEE_OTHER);
    }
    
    /**
     * Additional Operation - Change order status
     * This method allows admins to change the status of an order
     * It includes status validation and security checks
     */
    #[Route('/{id}/change-status/{status}', name: 'app_commande_change_status', methods: ['GET', 'POST'])]
    public function changeStatus(Request $request, Commande $commande, string $status, EntityManagerInterface $entityManager): Response
    {
        // Security check: Only admins can change order status
        if ($this->staticSession->getRole() !== 'admin') {
            throw $this->createAccessDeniedException('Access denied.');
        }

        $availableStatuses = Commande::getAvailableStatuses();
        
        // Validate the new status
        if (in_array($status, $availableStatuses)) {
            $commande->setStatutCommande($status);
            $entityManager->flush();
            
            $this->addFlash('success', 'Le statut de la commande a été modifié avec succès.');
        } else {
            $this->addFlash('danger', 'Statut de commande invalide.');
        }
        
        return $this->redirectToRoute('app_commande_show', ['id' => $commande->getId()], Response::HTTP_SEE_OTHER);
    }
} 