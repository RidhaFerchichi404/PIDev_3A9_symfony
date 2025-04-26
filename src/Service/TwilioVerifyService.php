<?php

namespace App\Service;

use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Psr\Log\LoggerInterface;
use Twilio\Rest\Client;
use Twilio\Exceptions\TwilioException;

class TwilioVerifyService
{
    private $params;
    private $logger;
    private $twilioClient;

    public function __construct(
        ParameterBagInterface $params,
        LoggerInterface $logger = null
    ) {
        $this->params = $params;
        $this->logger = $logger;
        
        // Initialiser le client Twilio avec les identifiants de configuration
        $sid = $this->params->get('sendgrid_account_sid');
        $token = $this->params->get('twilio_auth_token');
        $this->twilioClient = new Client($sid, $token);
    }

    /**
     * Envoyer un code de vérification par SMS
     */
    public function sendVerificationSms(string $phoneNumber): bool
    {
        try {
            $serviceSid = $this->params->get('twilio_verify_service_sid');
            
            if ($this->logger) {
                $this->logger->info('Envoi d\'un code de vérification SMS', [
                    'phone' => $phoneNumber,
                    'service' => $serviceSid
                ]);
            }

            $verification = $this->twilioClient->verify->v2->services($serviceSid)
                ->verifications
                ->create($phoneNumber, "sms");
            
            if ($this->logger) {
                $this->logger->info('Code de vérification SMS envoyé avec succès', [
                    'phone' => $phoneNumber,
                    'status' => $verification->status
                ]);
            }
            
            return true;
        } catch (TwilioException $e) {
            if ($this->logger) {
                $this->logger->error('Échec de l\'envoi du code de vérification SMS', [
                    'phone' => $phoneNumber,
                    'error' => $e->getMessage()
                ]);
            }
            return false;
        }
    }

    /**
     * Envoyer un code de vérification par email
     */
    public function sendVerificationEmail(string $email): bool
    {
        try {
            $serviceSid = $this->params->get('twilio_verify_service_sid');
            
            if ($this->logger) {
                $this->logger->info('Envoi d\'un code de vérification par email', [
                    'email' => $email,
                    'service' => $serviceSid
                ]);
            }

            $verification = $this->twilioClient->verify->v2->services($serviceSid)
                ->verifications
                ->create($email, "email");
            
            if ($this->logger) {
                $this->logger->info('Code de vérification email envoyé avec succès', [
                    'email' => $email,
                    'status' => $verification->status
                ]);
            }
            
            return true;
        } catch (TwilioException $e) {
            if ($this->logger) {
                $this->logger->error('Échec de l\'envoi du code de vérification par email', [
                    'email' => $email,
                    'error' => $e->getMessage()
                ]);
            }
            return false;
        }
    }

    /**
     * Vérifier un code reçu par SMS ou email
     */
    public function checkVerificationCode(string $to, string $code): bool
    {
        try {
            $serviceSid = $this->params->get('twilio_verify_service_sid');
            
            if ($this->logger) {
                $this->logger->info('Vérification d\'un code', [
                    'to' => $to,
                    'service' => $serviceSid
                ]);
            }

            $verificationCheck = $this->twilioClient->verify->v2->services($serviceSid)
                ->verificationChecks
                ->create([
                    'to' => $to,
                    'code' => $code
                ]);
            
            $isValid = $verificationCheck->status === 'approved';
            
            if ($this->logger) {
                $this->logger->info('Résultat de la vérification du code', [
                    'to' => $to,
                    'status' => $verificationCheck->status,
                    'valid' => $isValid
                ]);
            }
            
            return $isValid;
        } catch (TwilioException $e) {
            if ($this->logger) {
                $this->logger->error('Échec de la vérification du code', [
                    'to' => $to,
                    'error' => $e->getMessage()
                ]);
            }
            return false;
        }
    }
} 