<?php

namespace App\Controller;

use App\Entity\Equipement;
use App\Entity\SalleDeSport;
use App\Form\EquipementImageType;
use App\Form\EquipementType;
use App\Service\OpenAIService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/equipement-ai')]
class EquipementAIController extends AbstractController
{
    #[Route('/new', name: 'app_equipement_ai_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        // Récupérer les salles de sport pour le formulaire
        $salles = $entityManager->getRepository(SalleDeSport::class)->findAll();
        
        return $this->render('equipement_ai/new.html.twig', [
            'salles' => $salles,
        ]);
    }
    
    #[Route('/analyze', name: 'app_equipement_ai_analyze', methods: ['POST'])]
    public function analyze(Request $request, OpenAIService $openAIService): JsonResponse
    {
        $data = json_decode($request->getContent(), true);
        $imageBase64 = $data['image'] ?? null;
        
        if (!$imageBase64) {
            return new JsonResponse(['error' => 'No image provided'], Response::HTTP_BAD_REQUEST);
        }
        
        try {
            // Analyse de l'image avec GPT-4 Vision pour une meilleure précision
            $analysisResult = $openAIService->analyzeEquipmentWithGPT4Vision($imageBase64);
            
            // Log du résultat pour déboguer
            error_log("Résultat d'analyse GPT-4 Vision: " . json_encode($analysisResult));
            
            // Sauvegarder l'image
            $filename = $openAIService->saveImageFromBase64($imageBase64);
            
            return new JsonResponse([
                'success' => true,
                'analysis' => $analysisResult,
                'filename' => $filename
            ]);
        } catch (\Exception $e) {
            error_log("Erreur lors de l'analyse API: " . $e->getMessage());
            return new JsonResponse([
                'success' => false,
                'error' => $e->getMessage()
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
    
    #[Route('/raw-analysis', name: 'app_equipement_ai_raw_analysis', methods: ['POST'])]
    public function rawAnalysis(Request $request, OpenAIService $openAIService): JsonResponse
    {
        // Récupérer le fichier image
        $imageFile = $request->files->get('image');
        
        if (!$imageFile) {
            return new JsonResponse(['error' => 'Aucune image fournie'], Response::HTTP_BAD_REQUEST);
        }
        
        try {
            // Lire le contenu de l'image
            $imageContent = file_get_contents($imageFile->getPathname());
            if ($imageContent === false) {
                throw new \Exception("Impossible de lire le fichier téléchargé");
            }
            
            // Sauvegarder l'image
            $originalFilename = pathinfo($imageFile->getClientOriginalName(), PATHINFO_FILENAME);
            $safeFilename = preg_replace('/[^A-Za-z0-9_]/', '', $originalFilename);
            $safeFilename = strtolower($safeFilename);
            $newFilename = $safeFilename.'-'.uniqid().'.'.$imageFile->guessExtension();
            
            // Créer le répertoire si nécessaire
            $uploadDir = $this->getParameter('kernel.project_dir') . '/public/images';
            if (!is_dir($uploadDir) && !mkdir($uploadDir, 0777, true)) {
                throw new \Exception("Impossible de créer le répertoire d'upload");
            }
            
            // Écrire directement le contenu dans le nouveau fichier
            $newFilePath = $uploadDir . '/' . $newFilename;
            if (file_put_contents($newFilePath, $imageContent) === false) {
                throw new \Exception("Impossible d'écrire le fichier d'image");
            }
            
            // Convertir l'image en base64
            $mimeType = $imageFile->getMimeType() ?: 'image/jpeg';
            $imageBase64 = 'data:' . $mimeType . ';base64,' . base64_encode($imageContent);
            
            try {
                // Analyse avec GPT-4 Vision - cette méthode est modifiée pour ne plus utiliser l'analyse locale
                $analysisResult = $openAIService->analyzeEquipmentWithGPT4Vision($imageBase64);
                error_log("Analyse GPT-4 Vision réussie pour {$newFilename}: " . json_encode($analysisResult));
                
                // Retourner le résultat de l'analyse
                return new JsonResponse([
                    'success' => true,
                    'raw_response' => $analysisResult['nom'],
                    'detailed_analysis' => $analysisResult,
                    'filename' => $newFilename,
                    'analysis_source' => 'gpt4_vision',
                    'image_path' => '/images/' . $newFilename
                ]);
            } catch (\Exception $e) {
                // Erreur lors de l'analyse avec GPT-4 Vision
                error_log("Erreur d'analyse avec GPT-4 Vision: " . $e->getMessage());
                return new JsonResponse([
                    'success' => false,
                    'error' => "Erreur lors de l'analyse avec l'IA: " . $e->getMessage(),
                    'filename' => $newFilename,
                    'image_path' => '/images/' . $newFilename
                ], Response::HTTP_OK); // On renvoie 200 mais avec success=false pour que le front puisse gérer l'erreur
            }
            
        } catch (\Exception $e) {
            error_log("Erreur globale: " . $e->getMessage());
            return new JsonResponse([
                'success' => false,
                'error' => $e->getMessage()
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
    
    #[Route('/save', name: 'app_equipement_ai_save', methods: ['POST'])]
    public function save(Request $request, EntityManagerInterface $entityManager): Response
    {
        try {
            // Récupérer les données du formulaire
            $nom = $request->request->get('nom');
            $fonctionnement = $request->request->getBoolean('fonctionnement');
            $derniere_verification = $request->request->get('derniere_verification');
            $prochaine_verification = $request->request->get('prochaine_verification');
            $salleId = $request->request->get('salle');
            $imageFilename = $request->request->get('image_filename');
            
            // Valider les données requises
            if (empty($nom) || empty($salleId)) {
                $this->addFlash('error', 'Le nom et la salle sont obligatoires');
                return $this->redirectToRoute('app_equipement_ai_new');
            }
            
            // Récupérer la salle
            $salle = $entityManager->getRepository(SalleDeSport::class)->find($salleId);
            if (!$salle) {
                $this->addFlash('error', 'Salle de sport non trouvée');
                return $this->redirectToRoute('app_equipement_ai_new');
            }
            
            // Créer un nouvel équipement
            $equipement = new Equipement();
            $equipement->setNom($nom);
            $equipement->setFonctionnement($fonctionnement);
            $equipement->setDerniereVerification(new \DateTime($derniere_verification));
            $equipement->setProchaineVerification(new \DateTime($prochaine_verification));
            $equipement->setSalle($salle);
            $equipement->setImage($imageFilename ?: null);
            
            // Récupérer l'utilisateur connecté et définir son ID
            $user = $this->getUser();
            if ($user) {
                $equipement->setIdUser($user->getId());
            } else {
                // Si pas d'utilisateur connecté, utiliser un ID par défaut
                $equipement->setIdUser(1);
            }
            
            // Sauvegarder l'équipement
            $entityManager->persist($equipement);
            $entityManager->flush();
            
            // Message de succès
            $this->addFlash('success', 'Équipement ajouté avec succès!');
            
            // Redirection vers la liste des équipements
            return $this->redirectToRoute('app_equipement_index');
            
        } catch (\Exception $e) {
            $this->addFlash('error', 'Erreur lors de l\'enregistrement: ' . $e->getMessage());
            return $this->redirectToRoute('app_equipement_ai_new');
        }
    }

    /**
     * Améliore les résultats d'analyse de l'IA pour une meilleure présentation
     */
    private function enhanceAnalysisResults(array $results): array
    {
        // Vérifier si le nom indique un tapis de course
        $isTreadmill = false;
        
        // Vérifier si le nom indique déjà un tapis de course
        if (isset($results['nom']) && preg_match('/(tapis|course|treadmill|running)/i', $results['nom'])) {
            $isTreadmill = true;
        }
        
        // Si le nom ne contient pas ces mots mais la description oui, c'est probablement un tapis de course
        if (!$isTreadmill && isset($results['description']) && 
            preg_match('/(tapis.*course|course.*pied|treadmill|running.*machine|courir|marcher|cardio|inclinaison|vitesse)/i', $results['description'])) {
            $results['nom'] = 'Tapis de course';
            $isTreadmill = true;
        }
        
        // Si c'est un tapis de course, s'assurer que la description est complète
        if ($isTreadmill) {
            if (!isset($results['description']) || strlen($results['description']) < 100) {
                $results['description'] = "Tapis de course électrique pour l'entraînement cardio. Équipé d'un moteur permettant d'ajuster la vitesse et généralement d'un système d'inclinaison. Le plateau de course est conçu pour amortir l'impact sur les articulations. Le panneau de contrôle permet de régler la vitesse, l'inclinaison et d'accéder à différents programmes d'entraînement.";
            }
            if (!isset($results['fonction']) || strlen($results['fonction']) < 100) {
                $results['fonction'] = "Permet de courir ou marcher en intérieur, quel que soit le temps extérieur. Idéal pour l'entraînement cardiovasculaire, la perte de poids et l'amélioration de l'endurance. Permet de suivre et d'ajuster l'intensité de l'effort de façon précise.";
            }
        }
        
        // Capitaliser le nom de l'équipement
        if (isset($results['nom'])) {
            $results['nom'] = ucfirst($results['nom']);
        }
        
        // Assurer que la description contient bien des sauts de ligne pour une meilleure lisibilité
        if (isset($results['description']) && !str_contains($results['description'], "\n")) {
            // Si pas de sauts de ligne, découper en paragraphes
            $words = explode(' ', $results['description']);
            $paragraphs = [];
            $currentParagraph = [];
            
            // Créer des paragraphes d'environ 15-20 mots
            foreach ($words as $word) {
                $currentParagraph[] = $word;
                if (count($currentParagraph) >= mt_rand(15, 20)) {
                    $paragraphs[] = implode(' ', $currentParagraph);
                    $currentParagraph = [];
                }
            }
            
            if (!empty($currentParagraph)) {
                $paragraphs[] = implode(' ', $currentParagraph);
            }
            
            $results['description'] = implode("\n\n", $paragraphs);
        }
        
        // Assurer que la fonction contient bien des sauts de ligne
        if (isset($results['fonction']) && !str_contains($results['fonction'], "\n")) {
            // Ajouter un saut de ligne après la première phrase
            $results['fonction'] = preg_replace('/(\.)(\s+)([A-Z])/', ".$2\n\n$3", $results['fonction']);
        }
        
        return $results;
    }
    
    /**
     * Détermine si l'image montre un tapis de course en utilisant des caractéristiques visuelles
     */
    private function isTreadmillImage(string $filename): bool
    {
        $imagePath = $this->getParameter('kernel.project_dir') . '/public/images/' . $filename;
        
        if (!file_exists($imagePath)) {
            return false;
        }
        
        // Analyser l'image pour détecter des caractéristiques d'un tapis de course
        try {
            // Charger l'image
            $image = imagecreatefromjpeg($imagePath);
            if (!$image) {
                $image = imagecreatefrompng($imagePath);
                if (!$image) {
                    return false;
                }
            }
            
            // Obtenir les dimensions
            $width = imagesx($image);
            $height = imagesy($image);
            
            // Caractéristiques d'un tapis: forme rectangulaire allongée, généralement noir/gris
            $blackPixels = 0;
            $totalPixels = 0;
            
            // Échantillonner l'image pour les couleurs (pour éviter d'analyser chaque pixel)
            $sampleSize = 10; // Analyser 1 pixel sur 10
            for ($x = 0; $x < $width; $x += $sampleSize) {
                for ($y = 0; $y < $height; $y += $sampleSize) {
                    $rgb = imagecolorat($image, $x, $y);
                    $r = ($rgb >> 16) & 0xFF;
                    $g = ($rgb >> 8) & 0xFF;
                    $b = $rgb & 0xFF;
                    
                    // Si le pixel est dans les tons noirs/gris
                    if ($r < 100 && $g < 100 && $b < 100) {
                        $blackPixels++;
                    }
                    
                    $totalPixels++;
                }
            }
            
            // Libérer la mémoire
            imagedestroy($image);
            
            // Si l'image a une forme allongée et beaucoup de pixels noirs, c'est probablement un tapis
            $isRectangular = ($width / $height > 1.5) || ($height / $width > 1.5);
            $hasDarkColors = ($blackPixels / $totalPixels) > 0.3; // Plus de 30% de pixels noirs
            
            return $isRectangular && $hasDarkColors;
            
        } catch (\Exception $e) {
            error_log("Erreur lors de l'analyse de l'image pour détecter un tapis: " . $e->getMessage());
            return false;
        }
    }
} 