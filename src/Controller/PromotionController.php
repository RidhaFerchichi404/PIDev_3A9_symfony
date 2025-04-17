<?php

namespace App\Controller;

use App\Entity\Promotion;
use App\Entity\Abonnement;
use App\Form\PromotionType;
use App\Repository\PromotionRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/promotion')]
class PromotionController extends AbstractController
{
    #[Route('/', name: 'app_promotion_index', methods: ['GET'])]
    public function index(PromotionRepository $promotionRepository): Response
    {
        return $this->render('promotion/new.html.twig', [
            $promotions = $promotionRepository->findBy([], ['id' => 'ASC'])
        ]);
    }


    #[Route('/new', name: 'app_promotion_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $promotion = new Promotion();
        $form = $this->createForm(PromotionType::class, $promotion);
        $form->handleRequest($request);
    
        if ($form->isSubmitted()) {
            $errors = [];
            $now = new \DateTime();
    
            // Contrôle de l'abonnement
            if (!$promotion->getAbonnement()) {
                $errors[] = 'Veuillez sélectionner un abonnement.';
            }
    
            // Contrôle du code promo
            if (empty($promotion->getCodePromo())) {
                $errors[] = 'Le code promo est obligatoire.';
            } elseif (strlen($promotion->getCodePromo()) < 5) {
                $errors[] = 'Le code promo doit contenir au moins 5 caractères.';
            }
    
            // Contrôle de la valeur de réduction
            $valeurReduction = $promotion->getValeurReduction();
            if ($valeurReduction === null) {
                $errors[] = 'La valeur de réduction est obligatoire.';
            } elseif ($valeurReduction <= 0) {
                $errors[] = 'La valeur de réduction doit être positive.';
            } elseif ($valeurReduction > 100) {
                $errors[] = 'La valeur de réduction ne peut pas dépasser 100%.';
            }
    
            // Contrôle des dates
            $dateDebut = $promotion->getDateDebut();
            $dateFin = $promotion->getDateFin();
    
            if (!$dateDebut) {
                $errors[] = 'La date de début est obligatoire.';
            } elseif ($dateDebut < $now) {
                $errors[] = 'La date de début ne peut pas être dans le passé.';
            }
    
            if (!$dateFin) {
                $errors[] = 'La date de fin est obligatoire.';
            } elseif ($dateFin < $now) {
                $errors[] = 'La date de fin ne peut pas être dans le passé.';
            } elseif ($dateDebut && $dateFin < $dateDebut) {
                $errors[] = 'La date de fin doit être après la date de début.';
            }
    
            if (empty($errors)) { // Parenthèse fermante ajoutée ici
                try {
                    $entityManager->persist($promotion);
                    $entityManager->flush();
    
                    $this->addFlash('success', 'Promotion créée avec succès!');
                    return $this->redirectToRoute('app_promotion_index');
                } catch (\Exception $e) {
                    $this->addFlash('error', 'Une erreur est survenue lors de la création de la promotion.');
                    // Log l'erreur si nécessaire
                    // $this->logger->error($e->getMessage());
                }
            } else {
                foreach ($errors as $error) {
                    $this->addFlash('error', $error);
                }
            }
        }
    
        return $this->render('promotion/new.html.twig', [
            'form' => $form->createView(),
            'promotion' => $promotion
            
            
        ]);
    }
    #[Route('/{id}', name: 'app_promotion_show', methods: ['GET'])]
    public function show(Promotion $promotion): Response
    {
        return $this->render('promotion/show.html.twig', [
            'promotion' => $promotion,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_promotion_edit', methods: ['GET', 'POST'])]
    public function edit(
        Request $request, 
        $id, 
        EntityManagerInterface $entityManager,
        PromotionRepository $promotionRepository
    ): Response {
        $promotion = $promotionRepository->find($id);
        
        if (!$promotion) {
            throw $this->createNotFoundException('Promotion non trouvée pour l\'id '.$id);
        }
    
        $form = $this->createForm(PromotionType::class, $promotion);
        $form->handleRequest($request);
    
        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();
    
            $this->addFlash('success', 'Promotion mise à jour avec succès!');
            return $this->redirectToRoute('app_promotion_index');
        }
    
        return $this->render('promotion/edit.html.twig', [
            'promotion' => $promotion,
            'form' => $form->createView(),
        ]);
    }
    #[Route('/{id}', name: 'app_promotion_delete', methods: ['POST'])]
    public function delete(Request $request, Promotion $promotion, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$promotion->getId(), $request->request->get('_token'))) {
            $entityManager->remove($promotion);
            $entityManager->flush();
            $this->addFlash('success', 'Promotion supprimée avec succès!');
        }

        return $this->redirectToRoute('app_promotion_index');
    }
}