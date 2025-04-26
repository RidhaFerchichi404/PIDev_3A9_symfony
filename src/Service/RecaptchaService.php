<?php

namespace App\Service;

use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class RecaptchaService
{
    private $params;
    private $httpClient;

    public function __construct(
        ParameterBagInterface $params,
        HttpClientInterface $httpClient
    ) {
        $this->params = $params;
        $this->httpClient = $httpClient;
    }

    public function verify(string $token): bool
    {
        try {
            $response = $this->httpClient->request('POST', 'https://www.google.com/recaptcha/api/siteverify', [
                'body' => [
                    'secret' => '6LfE4jApAAAAAPdcUrE7KFM2ndXHUsZMpBnxP-Jg',
                    'response' => $token,
                ]
            ]);

            $data = $response->toArray();
            return $data['success'] ?? false;
        } catch (\Exception $e) {
            // En cas d'erreur, on considère la validation comme échouée
            return false;
        }
    }
} 