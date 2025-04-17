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
        return $this->render('promotion/index.html.twig', [
            'promotions' => $promotionRepository->findAllWithAbonnement(),
        ]);
    }#[Route('/new', name: 'app_promotion_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $promotion = new Promotion(); // DateCreation est déjà initialisée ici
    
        $form = $this->createForm(PromotionType::class, $promotion);
        $form->handleRequest($request);
    
        if ($form->isSubmitted()) {
            if (!$form->isValid()) {
                $this->addFlash('error', 'Le formulaire contient des erreurs.');
            } elseif ($promotion->getAbonnement() === null) {
                $this->addFlash('error', 'Veuillez sélectionner un abonnement.');
            } else {
                $entityManager->persist($promotion);
                $entityManager->flush();
    
                $this->addFlash('success', 'Promotion créée avec succès!');
                return $this->redirectToRoute('app_promotion_index');
            }
        }
    
        return $this->render('promotion/new.html.twig', [
            'promotion' => $promotion,
            'form' => $form->createView(),
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
    public function edit(Request $request, Promotion $promotion, EntityManagerInterface $entityManager): Response
    {
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