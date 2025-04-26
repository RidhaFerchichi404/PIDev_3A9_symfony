<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class RecaptchaController extends AbstractController
{
    private $httpClient;

    public function __construct(HttpClientInterface $httpClient)
    {
        $this->httpClient = $httpClient;
    }

    /**
     * @Route("/recaptcha/verify", name="app_recaptcha_verify", methods={"POST"})
     */
    public function verify(Request $request): Response
    {
        $token = $request->request->get('g-recaptcha-response');
        
        if (!$token) {
            return new JsonResponse([
                'success' => false,
                'message' => 'Token reCAPTCHA manquant'
            ], 400);
        }
        
        try {
            $response = $this->httpClient->request('POST', 'https://www.google.com/recaptcha/api/siteverify', [
                'body' => [
                    'secret' => '6LeIxAcTAAAAAGG-vFI1TnRWxMZNFuojJ4WifJWe',
                    'response' => $token
                ]
            ]);
            
            $data = $response->toArray();
            
            return new JsonResponse([
                'success' => $data['success'] ?? false,
                'score' => $data['score'] ?? 0,
                'action' => $data['action'] ?? null
            ]);
        } catch (\Exception $e) {
            return new JsonResponse([
                'success' => false,
                'message' => 'Erreur lors de la vérification reCAPTCHA: ' . $e->getMessage()
            ], 500);
        }
    }
} 