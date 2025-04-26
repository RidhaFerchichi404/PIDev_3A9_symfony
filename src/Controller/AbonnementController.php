<?php

namespace App\Controller;

use App\Entity\Abonnement;
use App\Entity\Salledesport;
use App\Form\AbonnementType;
use App\Repository\AbonnementRepository;
use App\Repository\PromotionRepository;
use App\Repository\SalleDeSportRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/abonnement')]
class AbonnementController extends AbstractController
{
    #[Route('/back', name: 'app_abonnement_index', methods: ['GET'])]
    public function index(AbonnementRepository $abonnementRepository): Response
    {
        return $this->render('abonnement/index.html.twig', [
            'abonnements' => $abonnementRepository->findAll(),
        ]);
    }

    #[Route('/salle/de/sport', name: 'app_salle_de_sport_index', methods: ['GET'])]
    public function listSalles(SalleDeSportRepository $repository): Response
    {
        return $this->render('salle_de_sport/index.html.twig', [
            'salles' => $repository->findAll()
        ]);
    }

    #[Route('/front', name: 'app_abonnement_front', methods: ['GET'])]
    public function front(AbonnementRepository $abonnementRepository): Response
    {
        return $this->render('abonnement/front.html.twig', [
            'abonnements' => $abonnementRepository->findAll(),
        ]);
    }

   /* #[Route('/new', name: 'app_abonnement_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager, SalleDeSportRepository $salleRepository): Response
    {
        $abonnement = new Abonnement();
        $form = $this->createForm(AbonnementType::class, $abonnement);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            if ($abonnement->getSalle()) {
                $abonnement->setSalleName($abonnement->getSalle()->getNom());
            }

            $entityManager->persist($abonnement);
            $entityManager->flush();

            $this->addFlash('success', 'Abonnement créé avec succès!');
            return $this->redirectToRoute('app_abonnement_index');
        }

        return $this->render('abonnement/new.html.twig', [
            'abonnement' => $abonnement,
            'form' => $form->createView(),
            'salles' => $salleRepository->findAll(),
        ]);
    }*/
    #[Route('/new', name: 'app_abonnement_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager, SalleDeSportRepository $salleRepository): Response
    {
        $abonnement = new Abonnement();
        $form = $this->createForm(AbonnementType::class, $abonnement);
        $form->handleRequest($request);
    
        if ($form->isSubmitted()) {
            $errors = [];
            $now = new \DateTime();
            if (!$form->isValid()) {
                foreach ($form->getErrors(true) as $error) {
                    $this->addFlash('error', $error->getMessage());
                }
            } else {
                $existingAbonnement = $entityManager->getRepository(Abonnement::class)->findOneBy([
                    'nom' => $abonnement->getNom(),
                    'salle' => $abonnement->getSalle()
                ]);
        
                if ($existingAbonnement) {
                    $this->addFlash('error', 'Un abonnement avec ce nom existe déjà pour cette salle');
                    return $this->redirectToRoute('app_abonnement_new');
                }
                // Validation personnalisée supplémentaire
                if ($abonnement->getDuree() <= 0 or $abonnement->getDuree() > 12)  {
                    $this->addFlash('error', 'La durée doit être supérieure à zéro');
                    return $this->redirectToRoute('app_abonnement_new');
                }
    
                if ($abonnement->getPrix() <= 0) {
                    $this->addFlash('error', 'Le prix doit être supérieur à zéro');
                    return $this->redirectToRoute('app_abonnement_new');
                }
    
                if (!$abonnement->getSalle()) {
                    $this->addFlash('error', 'Veuillez sélectionner une salle de sport');
                    return $this->redirectToRoute('app_abonnement_new');
                }
    
                // Vérification des doublons (nom + salle)
                $existingAbonnement = $entityManager->getRepository(Abonnement::class)->findOneBy([
                    'nom' => $abonnement->getNom(),
                    'salle' => $abonnement->getSalle()
                ]);
    
                if ($existingAbonnement) {
                    $this->addFlash('error', 'Un abonnement avec ce nom existe déjà pour cette salle');
                    return $this->redirectToRoute('app_abonnement_new');
                }
    
                // Tout est valide, on persiste
                $abonnement->setSalleName($abonnement->getSalle()->getNom());
                
                $entityManager->persist($abonnement);
                $entityManager->flush();
    
                $this->addFlash('success', 'Abonnement créé avec succès!');
                return $this->redirectToRoute('app_abonnement_index');
            }
        }
    
        return $this->render('abonnement/new.html.twig', [
            'abonnement' => $abonnement,
            'form' => $form->createView(),
            'salles' => $salleRepository->findAll(),
        ]);
    }
   /* #[Route('/{AbonnementID}', name: 'app_abonnement_show', methods: ['GET'])]
    public function show(Abonnement $abonnement): Response
    {
        return $this->render('abonnement/show.html.twig', [
            'abonnement' => $abonnement,
            
        ]);
    }*/
    #[Route('/{AbonnementID}', name: 'app_abonnement_show', methods: ['GET'])]
    public function show(Abonnement $abonnement, PromotionRepository $promotionRepository): Response
    {
        // Assurez-vous que la relation entre Abonnement et Promotion est correcte
        $promotions = $promotionRepository->findBy(['abonnement' => $abonnement]);
        
        return $this->render('abonnement/show.html.twig', [
            'abonnement' => $abonnement,
            'promotions' => $abonnement->getPromotions() // Ajoutez cette ligne
        ]);
    }


    #[Route('/{AbonnementID}/edit', name: 'app_abonnement_edit', methods: ['GET', 'POST'])]
    public function edit(
        Request $request, 
        Abonnement $abonnement, 
        EntityManagerInterface $entityManager,
        SalleDeSportRepository $salleRepository
    ): Response {
        $form = $this->createForm(AbonnementType::class, $abonnement);
        $form->handleRequest($request);
    
        if ($form->isSubmitted()) {
            if (!$form->isValid()) {
                foreach ($form->getErrors(true) as $error) {
                    $this->addFlash('error', $error->getMessage());
                }
            } else {
                // Validation personnalisée
                if ($abonnement->getDuree() <= 0 || $abonnement->getDuree() > 12) {
                    $this->addFlash('error', 'La durée doit être comprise entre 1 et 12 mois');
                    return $this->redirectToRoute('app_abonnement_edit', ['AbonnementID' => $abonnement->getAbonnementID()]);
                }
    
                if ($abonnement->getPrix() <= 0) {
                    $this->addFlash('error', 'Le prix doit être supérieur à zéro');
                    return $this->redirectToRoute('app_abonnement_edit', ['AbonnementID' => $abonnement->getAbonnementID()]);
                }
    
                if (!$abonnement->getSalle()) {
                    $this->addFlash('error', 'Veuillez sélectionner une salle de sport');
                    return $this->redirectToRoute('app_abonnement_edit', ['AbonnementID' => $abonnement->getAbonnementID()]);
                }
    
                // Vérification des doublons (en excluant l'abonnement actuel)
                $existingAbonnement = $entityManager->getRepository(Abonnement::class)->findOneBy([
                    'nom' => $abonnement->getNom(),
                    'salle' => $abonnement->getSalle()
                ]);
    
                if ($existingAbonnement && $existingAbonnement->getAbonnementID() !== $abonnement->getAbonnementID()) {
                    $this->addFlash('error', 'Un abonnement avec ce nom existe déjà pour cette salle');
                    return $this->redirectToRoute('app_abonnement_edit', ['AbonnementID' => $abonnement->getAbonnementID()]);
                }
    
                // Tout est valide, on met à jour
                $abonnement->setSalleName($abonnement->getSalle()->getNom());
                $entityManager->flush();
    
                $this->addFlash('success', 'Abonnement mis à jour avec succès!');
                return $this->redirectToRoute('app_abonnement_index');
            }
        }
    
        return $this->render('abonnement/edit.html.twig', [
            'abonnement' => $abonnement,
            'form' => $form->createView(),
            'salles' => $salleRepository->findAll(),
        ]);
    }
    #[Route('/{id}/promotions', name: 'app_abonnement_promotions', methods: ['GET'])]
    public function showPromotions(Abonnement $abonnement): Response
    {
        return $this->render('abonnement/promotions.html.twig', [
            'abonnement' => $abonnement,
            'promotions' => $abonnement->getPromotions(),
        ]);
    }
    #[Route('/{AbonnementID}', name: 'app_abonnement_delete', methods: ['POST'])]
    public function delete(Request $request, Abonnement $abonnement, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$abonnement->getAbonnementID(), $request->request->get('_token'))) {
            $entityManager->remove($abonnement);
            $entityManager->flush();
            $this->addFlash('success', 'Abonnement supprimé avec succès');
        }

        return $this->redirectToRoute('app_abonnement_index');
    }
}