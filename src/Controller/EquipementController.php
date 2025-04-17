<?php

namespace App\Controller;

use App\Entity\Equipement;
use App\Form\EquipementType;
use App\Repository\EquipementRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/equipement')]
final class EquipementController extends AbstractController
{
    #[Route(name: 'app_equipement_index', methods: ['GET'])]
    public function index(EquipementRepository $equipementRepository): Response
    {
        return $this->render('equipement/index.html.twig', [
            'equipements' => $equipementRepository->findAll(),
        ]);
    }
    #[Route('/front', name: 'app_equipement', methods: ['GET', 'POST'])]
    public function index1(EquipementRepository $equipementRepository): Response
    {
        return $this->render('equipement/front.html.twig', [
            'equipements' => $equipementRepository->findAll(),
        ]);
    }


    #[Route('/new', name: 'app_equipement_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $equipement = new Equipement();
        $form = $this->createForm(EquipementType::class, $equipement);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($equipement);
            $entityManager->flush();

            return $this->redirectToRoute('app_equipement_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('equipement/new.html.twig', [
            'equipement' => $equipement,
            'form' => $form,
        ]);
    }

    #[Route('/new/salle/{id}', name: 'app_equipement_new_for_salle', methods: ['GET', 'POST'])]
    public function newForSalle(Request $request, EntityManagerInterface $entityManager, $id): Response
    {
        // Créer un nouvel équipement
        $equipement = new Equipement();
        
        // Récupérer la salle de sport
        $salle = $entityManager->getRepository('App\Entity\SalleDeSport')->find($id);
        
        if (!$salle) {
            throw $this->createNotFoundException('La salle n\'existe pas');
        }
        
        // Préremplir la salle
        $equipement->setSalle($salle);
        
        // Préremplir l'ID utilisateur avec celui de la salle
        $equipement->setIdUser($salle->getIdUser());
        
        // Créer le formulaire avec les options pour masquer les champs déjà définis
        $form = $this->createForm(EquipementType::class, $equipement, [
            'hide_salle' => true,
            'hide_user' => true,
        ]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($equipement);
            $entityManager->flush();

            // Rediriger vers la liste des équipements de cette salle
            return $this->redirectToRoute('app_salle_de_sport_equipements', ['id' => $id], Response::HTTP_SEE_OTHER);
        }

        return $this->render('equipement/new.html.twig', [
            'equipement' => $equipement,
            'form' => $form,
            'salle_id' => $id,
            'salle_nom' => $salle->getNom(),
        ]);
    }

    #[Route('/{id}', name: 'app_equipement_show', methods: ['GET'])]
    public function show(Equipement $equipement): Response
    {
        return $this->render('equipement/show.html.twig', [
            'equipement' => $equipement,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_equipement_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Equipement $equipement, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(EquipementType::class, $equipement);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            // Rediriger vers les équipements de la salle correspondante
            return $this->redirectToRoute('app_salle_de_sport_equipements', [
                'id' => $equipement->getSalle()->getId(),
            ], Response::HTTP_SEE_OTHER);
        }

        return $this->render('equipement/edit.html.twig', [
            'equipement' => $equipement,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_equipement_delete', methods: ['POST'])]
    public function delete(Request $request, Equipement $equipement, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$equipement->getId(), $request->getPayload()->getString('_token'))) {
            $entityManager->remove($equipement);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_equipement_index', [], Response::HTTP_SEE_OTHER);
    }

    #[Route('/{id}/exercices', name: 'app_equipement_exercices', methods: ['GET'])]
    public function exercices(Equipement $equipement): Response
    {
        return $this->render('exercice/index.html.twig', [
            'exercices' => $equipement->getExercices(),
            'equipement' => $equipement,
        ]);
    }

    #[Route('/{id}/exercicesf', name: 'app_equipement_exercicesf', methods: ['GET'])]
    public function exercices1(Equipement $equipement): Response
    {
        $exercices = $equipement->getExercices();
    
        return $this->render('exercice/front.html.twig', [
            'equipement' => $equipement,
            'exercices' => $exercices,
        ]);
    }
}
