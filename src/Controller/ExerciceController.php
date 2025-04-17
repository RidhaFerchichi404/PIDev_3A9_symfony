<?php

namespace App\Controller;

use App\Entity\Exercice;
use App\Form\ExerciceType;
use App\Repository\ExerciceRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/exercice')]
final class ExerciceController extends AbstractController
{
    #[Route(name: 'app_exercice_index', methods: ['GET'])]
    public function index(ExerciceRepository $exerciceRepository): Response
    {
        return $this->render('exercice/index.html.twig', [
            'exercices' => $exerciceRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_exercice_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $exercice = new Exercice();
        $form = $this->createForm(ExerciceType::class, $exercice);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($exercice);
            $entityManager->flush();

            return $this->redirectToRoute('app_exercice_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('exercice/new.html.twig', [
            'exercice' => $exercice,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_exercice_show', methods: ['GET'])]
    public function show(Exercice $exercice): Response
    {
        return $this->render('exercice/show.html.twig', [
            'exercice' => $exercice,
        ]);
    }

    #[Route('/exercice/{id}', name: 'app_exercice_showf', methods: ['GET'])]
    public function showf(Exercice $exercice): Response
    {
        return $this->render('exercice/show.html.twig', [
            'exercice' => $exercice,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_exercice_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Exercice $exercice, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(ExerciceType::class, $exercice);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            // Rediriger vers les exercices de l'équipement associé
            return $this->redirectToRoute('app_equipement_exercices', [
                'id' => $exercice->getEquipement()->getId(),
            ], Response::HTTP_SEE_OTHER);
        }

        return $this->render('exercice/edit.html.twig', [
            'exercice' => $exercice,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_exercice_delete', methods: ['POST'])]
    public function delete(Request $request, Exercice $exercice, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$exercice->getId(), $request->getPayload()->getString('_token'))) {
            $entityManager->remove($exercice);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_exercice_index', [], Response::HTTP_SEE_OTHER);
    }

    #[Route('/new/equipement/{id}', name: 'app_exercice_new_for_equipement', methods: ['GET', 'POST'])]
    public function newForEquipement(Request $request, EntityManagerInterface $entityManager, $id): Response
    {
        // Créer un nouvel exercice
        $exercice = new Exercice();
        
        // Récupérer l'équipement
        $equipement = $entityManager->getRepository('App\Entity\Equipement')->find($id);
        
        if (!$equipement) {
            throw $this->createNotFoundException('L\'équipement n\'existe pas');
        }
        
        // Préremplir l'équipement
        $exercice->setEquipement($equipement);
        
        // Préremplir l'ID utilisateur avec celui de l'équipement
        $exercice->setIdUser($equipement->getIdUser());
        
        // Créer le formulaire avec les options pour masquer les champs déjà définis
        $form = $this->createForm(ExerciceType::class, $exercice, [
            'hide_equipement' => true,
            'hide_user' => true,
        ]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($exercice);
            $entityManager->flush();

            // Rediriger vers la liste des exercices de cet équipement
            return $this->redirectToRoute('app_equipement_exercices', ['id' => $id], Response::HTTP_SEE_OTHER);
        }

        return $this->render('exercice/new.html.twig', [
            'exercice' => $exercice,
            'form' => $form,
            'equipement_id' => $id,
            'equipement_nom' => $equipement->getNom(),
        ]);
    }
}
