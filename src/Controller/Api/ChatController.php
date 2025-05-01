<?php

namespace App\Controller\Api;

use App\Entity\Produit;
use App\Service\ChatGPTService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[Route('/api/chat')]
class ChatController extends AbstractController
{
    private $chatGPTService;

    public function __construct(ChatGPTService $chatGPTService)
    {
        $this->chatGPTService = $chatGPTService;
    }

    #[Route('/product/{id}/advice', name: 'api_chat_product_advice', methods: ['POST'])]
    #[IsGranted('ROLE_USER')]
    public function getProductAdvice(Request $request, Produit $produit): JsonResponse
    {
        $data = json_decode($request->getContent(), true);
        
        if (!isset($data['age']) || !isset($data['height'])) {
            return new JsonResponse(['error' => 'Age and height are required'], 400);
        }

        $productData = [
            'name' => $produit->getNom(),
            'description' => $produit->getDescription(),
            'price' => $produit->getPrix(),
        ];

        $userData = [
            'age' => $data['age'],
            'height' => $data['height']
        ];

        try {
            $response = $this->chatGPTService->generateProductAdvice($productData, $userData);
            return new JsonResponse(['message' => $response]);
        } catch (\Exception $e) {
            return new JsonResponse(['error' => 'Failed to generate advice'], 500);
        }
    }

    #[Route('/product/{id}/message', name: 'api_chat_product_message', methods: ['POST'])]
    #[IsGranted('ROLE_USER')]
    public function handleMessage(Request $request, Produit $produit): JsonResponse
    {
        $data = json_decode($request->getContent(), true);
        
        if (!isset($data['message'])) {
            return new JsonResponse(['error' => 'Message is required'], 400);
        }

        $productData = [
            'name' => $produit->getNom(),
            'description' => $produit->getDescription(),
            'price' => $produit->getPrix(),
        ];

        try {
            $response = $this->chatGPTService->generateProductAdvice($productData, null, $data['message']);
            return new JsonResponse(['message' => $response]);
        } catch (\Exception $e) {
            return new JsonResponse(['error' => 'Failed to process message'], 500);
        }
    }
} 