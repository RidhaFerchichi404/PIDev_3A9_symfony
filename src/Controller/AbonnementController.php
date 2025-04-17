<?php

namespace App\Controller;

use App\Entity\Abonnement;
use App\Entity\Salledesport;
use App\Form\AbonnementType;
use App\Repository\AbonnementRepository;
use App\Repository\SalleDeSportRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
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
#[Route('/salle/de/sport', name: 'app_salle_de_sport_index')]
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
            'abonnements' => $abonnementRepository->findAllWithPromotions(),
        ]);
    }
    #[Route('/new', name: 'app_abonnement_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager, SalleDeSportRepository $salleRepository): Response
    {
        $abonnement = new Abonnement();
        $form = $this->createForm(AbonnementType::class, $abonnement);
        
        $form->handleRequest($request);
    
        if ($form->isSubmitted() && $form->isValid()) {
            // Si vous avez une relation avec SalleDeSport
            if ($abonnement->getSalle()) {
                $abonnement->setSalleName($abonnement->getSalle()->getNom());
            }
    
            $entityManager->persist($abonnement);
            $entityManager->flush();
    
            $this->addFlash('success', 'Abonnement créé avec succès!');
            return $this->redirectToRoute('app_abonnement_index');
        }
    
        return $this->render('abonnement/new.html.twig', [
            'form' => $form->createView(),
            'salles' => $salleRepository->findAll(),
        ]);
    }
    

    #[Route('/{AbonnementID}', name: 'app_abonnement_show', methods: ['GET'])]
    public function show(Abonnement $abonnement): Response
    {
        return $this->render('abonnement/show.html.twig', [
            'abonnement' => $abonnement,
        ]);
    }

    
    
#[Route('/{AbonnementID}/edit', name: 'app_test_edit', methods: ['GET', 'POST'])]
public function edit(Request $request, Abonnement $abonnement, EntityManagerInterface $entityManager): Response
{
    $form = $this->createForm(AbonnementType::class, $abonnement);
    $form->handleRequest($request);

    if ($form->isSubmitted() && $form->isValid()) {
        $entityManager->flush();

        return $this->redirectToRoute('app_test_index', [], Response::HTTP_SEE_OTHER);
    }

    return $this->render('abonnement/edit.html.twig', [
        'abonnement' => $abonnement,
        'form' => $form,
    ]);
}
    #[Route('/{AbonnementID}', name: 'app_abonnement_delete', methods: ['POST'])]
    public function delete(Request $request, Abonnement $abonnement, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$abonnement->getAbonnementID(), $request->request->get('_token'))) {
            $entityManager->remove($abonnement);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_abonnement_index');
    }

   
}
