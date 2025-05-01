<?php

namespace App\Service;

use Symfony\Contracts\HttpClient\HttpClientInterface;

class ChatGPTService
{
    private $httpClient;
    private $apiKey;
    private $basePrompt;

    public function __construct(HttpClientInterface $httpClient, string $apiKey)
    {
        $this->httpClient = $httpClient;
        $this->apiKey = $apiKey;
        $this->basePrompt = "You are a knowledgeable fitness equipment and sports product advisor. Your role is to provide personalized advice about the product based on user's age, height, and specific needs. Focus ONLY on the product being discussed and fitness-related advice. If asked about topics unrelated to fitness or the product, politely redirect the conversation back to fitness and product advice. Keep responses concise, friendly, and focused on helping users make informed decisions about their fitness journey with this product.";
    }

    public function generateProductAdvice(array $productData, ?array $userData = null, ?string $userMessage = null): string
    {
        $prompt = $this->basePrompt . "\n\n";
        $prompt .= "Product Information:\n";
        $prompt .= "- Name: {$productData['name']}\n";
        $prompt .= "- Description: {$productData['description']}\n";
        $prompt .= "- Price: \${$productData['price']}\n";
        
        if ($userData) {
            $prompt .= "\nUser Information:\n";
            $prompt .= "- Age: {$userData['age']}\n";
            $prompt .= "- Height: {$userData['height']}\n";
        }

        if ($userMessage) {
            $prompt .= "\nUser Question: {$userMessage}\n";
        }

        $prompt .= "\nProvide personalized advice about this product's suitability and usage recommendations. Include safety considerations if applicable.";

        $response = $this->httpClient->request('POST', 'https://api.openai.com/v1/chat/completions', [
            'headers' => [
                'Authorization' => 'Bearer ' . $this->apiKey,
                'Content-Type' => 'application/json',
            ],
            'json' => [
                'model' => 'gpt-3.5-turbo',
                'messages' => [
                    ['role' => 'system', 'content' => $this->basePrompt],
                    ['role' => 'user', 'content' => $prompt]
                ],
                'temperature' => 0.7,
                'max_tokens' => 500
            ]
        ]);

        $data = $response->toArray();
        return $data['choices'][0]['message']['content'];
    }
} 