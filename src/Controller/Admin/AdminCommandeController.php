<?php

namespace App\Controller\Admin;

use App\Entity\Commande;
use App\Form\CommandeType;
use App\Repository\CommandeRepository;
use App\Service\StaticSession;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/admin/commande')]
class AdminCommandeController extends AbstractController
{
    public function __construct(
        private StaticSession $staticSession
    ) {
    }

    #[Route('/', name: 'app_admin_commande_index', methods: ['GET'])]
    public function index(CommandeRepository $commandeRepository): Response
    {
        if (!$this->staticSession->isAdmin()) {
            $this->addFlash('error', 'Access denied. Admin privileges required.');
            return $this->redirectToRoute('app_home');
        }

        return $this->render('admin/commande/index.html.twig', [
            'commandes' => $commandeRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_admin_commande_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        if (!$this->staticSession->isAdmin()) {
            $this->addFlash('error', 'Access denied. Admin privileges required.');
            return $this->redirectToRoute('app_home');
        }

        $commande = new Commande();
        $form = $this->createForm(CommandeType::class, $commande);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $commande->setDateCommande(new \DateTime());
            if (!$commande->getStatutCommande()) {
                $commande->setStatutCommande(Commande::STATUS_PENDING);
            }
            $commande->setUserId(1);
            
            $entityManager->persist($commande);
            $entityManager->flush();

            $this->addFlash('success', 'Order created successfully');
            return $this->redirectToRoute('app_admin_commande_index');
        }

        return $this->render('admin/commande/new.html.twig', [
            'commande' => $commande,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_admin_commande_show', methods: ['GET'])]
    public function show(Commande $commande): Response
    {
        if (!$this->staticSession->isAdmin()) {
            $this->addFlash('error', 'Access denied. Admin privileges required.');
            return $this->redirectToRoute('app_home');
        }

        return $this->render('admin/commande/show.html.twig', [
            'commande' => $commande,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_admin_commande_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Commande $commande, EntityManagerInterface $entityManager): Response
    {
        if (!$this->staticSession->isAdmin()) {
            $this->addFlash('error', 'Access denied. Admin privileges required.');
            return $this->redirectToRoute('app_home');
        }

        $form = $this->createForm(CommandeType::class, $commande, [
            'is_edit' => true
        ]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            $this->addFlash('success', 'Order updated successfully');
            return $this->redirectToRoute('app_admin_commande_index');
        }

        return $this->render('admin/commande/edit.html.twig', [
            'commande' => $commande,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_admin_commande_delete', methods: ['POST'])]
    public function delete(Request $request, Commande $commande, EntityManagerInterface $entityManager): Response
    {
        if (!$this->staticSession->isAdmin()) {
            $this->addFlash('error', 'Access denied. Admin privileges required.');
            return $this->redirectToRoute('app_home');
        }

        if ($this->isCsrfTokenValid('delete'.$commande->getId(), $request->request->get('_token'))) {
            $entityManager->remove($commande);
            $entityManager->flush();
            $this->addFlash('success', 'Order deleted successfully');
        }

        return $this->redirectToRoute('app_admin_commande_index');
    }

    #[Route('/{id}/change-status/{status}', name: 'app_admin_commande_change_status', methods: ['GET', 'POST'])]
    public function changeStatus(Request $request, Commande $commande, string $status, EntityManagerInterface $entityManager): Response
    {
        if (!$this->staticSession->isAdmin()) {
            $this->addFlash('error', 'Access denied. Admin privileges required.');
            return $this->redirectToRoute('app_home');
        }

        $availableStatuses = Commande::getAvailableStatuses();
        
        if (in_array($status, $availableStatuses)) {
            $commande->setStatutCommande($status);
            $entityManager->flush();
            
            $this->addFlash('success', 'Order status updated successfully');
        } else {
            $this->addFlash('error', 'Invalid order status');
        }
        
        return $this->redirectToRoute('app_admin_commande_show', ['id' => $commande->getId()]);
    }
} 