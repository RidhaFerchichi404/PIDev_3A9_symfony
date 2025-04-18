<?php

namespace App\Controller;

use App\Entity\SalleDeSport;
use App\Entity\Historique;
use App\Form\SalleDeSportType;
use App\Repository\SalleDeSportRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Dompdf\Dompdf;
use Dompdf\Options;

#[Route('/salle/de/sport')]
final class SalleDeSportController extends AbstractController
{
    #[Route(name: 'app_salle_de_sport_index', methods: ['GET'])]
    public function index(SalleDeSportRepository $salleDeSportRepository, EntityManagerInterface $entityManager): Response
    {
        $salleDeSports = $salleDeSportRepository->findAll();

        // Récupérer les équipements et les classer
        $to_do = [];
        $in_progress = [];
        $done = [];
        $data = [];
        $data_values = [];
        $equipements_data = []; // Ajouter cette variable

        $aujourdhui = new \DateTime();

        foreach ($salleDeSports as $salle) {
            $equipements = $salle->getEquipements();
            $data[] = $salle->getNom(); // Nom de la salle
            $data_values[] = count($equipements); // Nombre d'équipements

            foreach ($equipements as $equipement) {
                $prochaineVerification = $equipement->getProchaineVerification();
                $derniereVerification = $equipement->getDerniereVerification();

                // Ajouter les données des équipements pour le calendrier
                $equipements_data[] = [
                    'nom' => $equipement->getNom(),
                    'salle' => $salle->getNom(),
                    'derniere_verification' => $derniereVerification,
                    'prochaine_verification' => $prochaineVerification,
                ];

                if ($prochaineVerification > $aujourdhui) {
                    $to_do[] = [
                        'nom' => $equipement->getNom(),
                        'salle' => $salle->getNom(),
                        'prochaine_verification' => $prochaineVerification,
                    ];
                } elseif ($prochaineVerification == $aujourdhui) {
                    $in_progress[] = [
                        'nom' => $equipement->getNom(),
                        'salle' => $salle->getNom(),
                        'prochaine_verification' => $prochaineVerification,
                    ];
                } else {
                    $done[] = [
                        'nom' => $equipement->getNom(),
                        'salle' => $salle->getNom(),
                        'prochaine_verification' => $prochaineVerification,
                    ];
                }
            }
        }

        return $this->render('salle_de_sport/index.html.twig', [
            'salle_de_sports' => $salleDeSports,
            'to_do' => $to_do,
            'in_progress' => $in_progress,
            'done' => $done,
            'data' => $data, // Labels des salles
            'data_values' => $data_values, // Valeurs des équipements
            'equipements_data' => $equipements_data, // Données des équipements pour le calendrier
        ]);
    }

    #[Route('/fronts',name: 'app_salle_de_sport_index1', methods: ['GET'])]
    public function index1(SalleDeSportRepository $salleDeSportRepository): Response
    {
        return $this->render('salle_de_sport/front.html.twig', [
            'salle_de_sports' => $salleDeSportRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_salle_de_sport_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $salleDeSport = new SalleDeSport();
        $form = $this->createForm(SalleDeSportType::class, $salleDeSport);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($salleDeSport);
            $entityManager->flush();

            // Ajouter une entrée dans l'historique
            $historique = new Historique();
            $historique->setAction('Ajout');
            $historique->setSalle($salleDeSport->getNom());
            $historique->setDate(new \DateTime());
            $entityManager->persist($historique);
            $entityManager->flush();

            return $this->redirectToRoute('app_salle_de_sport_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('salle_de_sport/new.html.twig', [
            'salle_de_sport' => $salleDeSport,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_salle_de_sport_show', methods: ['GET'])]
    public function show(SalleDeSport $salleDeSport): Response
    {
        return $this->render('salle_de_sport/show.html.twig', [
            'salle_de_sport' => $salleDeSport,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_salle_de_sport_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, SalleDeSport $salleDeSport, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(SalleDeSportType::class, $salleDeSport);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_salle_de_sport_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('salle_de_sport/edit.html.twig', [
            'salle_de_sport' => $salleDeSport,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_salle_de_sport_delete', methods: ['POST'])]
    public function delete(Request $request, SalleDeSport $salleDeSport, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$salleDeSport->getId(), $request->getPayload()->getString('_token'))) {
            $entityManager->remove($salleDeSport);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_salle_de_sport_index', [], Response::HTTP_SEE_OTHER);
    }

    #[Route('/{id}/equipements', name: 'app_salle_de_sport_equipements', methods: ['GET'])]
    public function equipements(SalleDeSport $salleDeSport): Response
    {
        $equipements = $salleDeSport->getEquipements();

        return $this->render('equipement/index.html.twig', [
            'salle_de_sport' => $salleDeSport, // Transmet la salle de sport à la vue
            'equipements' => $equipements,
        ]);
    }

    #[Route('/{id}/equipementsf', name: 'app_salle_de_sport_equipementsf', methods: ['GET'])]
    public function equipementsf(SalleDeSport $salleDeSport): Response
    {
        $equipements = $salleDeSport->getEquipements();
    
        return $this->render('equipement/front.html.twig', [
            'salle_de_sport' => $salleDeSport,
            'equipements' => $equipements,
        ]);
    }

    #[Route('/statistics', name: 'app_salle_de_sport_statistics', methods: ['GET'])]
    public function stat(SalleDeSportRepository $salleDeSportRepository): Response
    {
        // Fetch the salle de sport data
        $salleDeSports = $salleDeSportRepository->findAll();

        // Create data arrays for the chart (labels and values)
        $data = [];
        $data_values = [];

        foreach ($salleDeSports as $salleDeSport) {
            $data[] = $salleDeSport->getNom();  // The name of the gym
            $data_values[] = $salleDeSport->getEquipements()->count();  // The number of equipment in the gym
        }

        return $this->render('salle_de_sport/statistics.html.twig', [
            'data' => $data,             // Pass the labels
            'data_values' => $data_values // Pass the values
        ]);
    }

    #[Route('/{id}/pdf', name: 'app_salle_de_sport_pdf', methods: ['GET'])]
    public function generatePdf(SalleDeSport $salleDeSport): Response
    {
        // Inclure TCPDF
        require_once $this->getParameter('kernel.project_dir') . '/vendor/tcpdf/tcpdf.php';

        // Créer une instance de TCPDF
        $pdf = new \TCPDF();

        // Configurer le document PDF
        $pdf->SetCreator('Symfony');
        $pdf->SetAuthor('Gym Dashboard');
        $pdf->SetTitle($salleDeSport->getNom() . ' - Details');
        $pdf->SetSubject('Gym Details');
        $pdf->SetMargins(10, 10, 10);
        $pdf->SetAutoPageBreak(true, 10);

        // Ajouter une page
        $pdf->AddPage();

        // Contenu HTML pour le PDF
        $html = $this->renderView('salle_de_sport/pdf.html.twig', [
            'salle_de_sport' => $salleDeSport,
            'equipements' => $salleDeSport->getEquipements(),
        ]);

        // Charger le contenu HTML dans TCPDF
        $pdf->writeHTML($html, true, false, true, false, '');

        // Générer le PDF
        $pdfContent = $pdf->Output($salleDeSport->getNom() . '_details.pdf', 'S');

        // Retourner le PDF en réponse
        return new Response($pdfContent, 200, [
            'Content-Type' => 'application/pdf',
            'Content-Disposition' => 'inline; filename="' . $salleDeSport->getNom() . '_details.pdf"',
        ]);
    }
    #[Route('/ask-chatgpt', name: 'ask_chatgpt')]
    public function askChatGPT(Request $request): Response
    {
        $userMessage = $request->query->get('message', 'What is the weather today?'); // Exemple de message par défaut

        $response = $this->chatGPTService->askChatGPT($userMessage);

        return $this->render('chatgpt/response.html.twig', [
            'response' => $response,
        ]);
    }
}
