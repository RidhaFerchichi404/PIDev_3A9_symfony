<?php

namespace App\Service;

use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Psr\Log\LoggerInterface;
use Twilio\Rest\Client;
use Twilio\Exceptions\TwilioException;

class SmsService
{
    private $params;
    private $logger;
    private $twilioClient;
    private $simulationMode;

    public function __construct(
        ParameterBagInterface $params,
        LoggerInterface $logger = null
    ) {
        $this->params = $params;
        $this->logger = $logger;
        $this->simulationMode = $params->get('app.env') === 'dev';
        
        try {
            // Initialiser le client Twilio avec les identifiants de configuration
            $sid = $this->params->get('sendgrid_account_sid');
            $token = $this->params->get('twilio_auth_token');
            
            if (!empty($sid) && !empty($token)) {
                $this->twilioClient = new Client($sid, $token);
            } else {
                if ($this->logger) {
                    $this->logger->warning('Identifiants Twilio manquants, service SMS en mode simulation');
                }
                $this->simulationMode = true;
            }
        } catch (\Exception $e) {
            if ($this->logger) {
                $this->logger->error('Erreur lors de l\'initialisation du client Twilio', [
                    'error' => $e->getMessage()
                ]);
            }
            $this->simulationMode = true;
        }
    }

    /**
     * Envoyer un SMS
     */
    public function sendSms(string $phoneNumber, string $message): bool
    {
        try {
            // Nettoyer le numéro de téléphone
            $phoneNumber = $this->formatPhoneNumber($phoneNumber);
            
            // Vérification du numéro de téléphone
            if (!$this->isValidPhoneNumber($phoneNumber)) {
                if ($this->logger) {
                    $this->logger->error('Numéro de téléphone invalide', [
                        'phone' => $phoneNumber
                    ]);
                }
                error_log("SMS ERROR: Numéro de téléphone invalide: " . $phoneNumber);
                return false;
            }
            
            // Log des informations de configuration
            error_log("SMS DEBUG: Mode simulation: " . ($this->simulationMode ? 'OUI' : 'NON'));
            error_log("SMS DEBUG: SID: " . $this->params->get('sendgrid_account_sid'));
            error_log("SMS DEBUG: Auth Token: " . substr($this->params->get('twilio_auth_token'), 0, 5) . '...');
            error_log("SMS DEBUG: Numéro Twilio: " . $this->params->get('twilio_phone_number'));
            
            // Mode simulation pour les tests
            if ($this->simulationMode) {
                if ($this->logger) {
                    $this->logger->info('SMS SIMULATION', [
                        'phone' => $phoneNumber,
                        'message' => $message
                    ]);
                }
                
                // Afficher dans la console pour faciliter le test en dev
                error_log('===========================================');
                error_log('SIMULATION SMS: ' . $phoneNumber);
                error_log('-------------------------------------------');
                error_log($message);
                error_log('===========================================');
                
                // Toujours retourner true en mode simulation
                return true;
            }
            
            // Vérifier que le client Twilio est initialisé
            if (!$this->twilioClient) {
                error_log("SMS ERROR: Client Twilio non initialisé");
                return false;
            }
            
            try {
                if ($this->logger) {
                    $this->logger->info('Envoi d\'un SMS', [
                        'phone' => $phoneNumber
                    ]);
                }

                // Utiliser le service Twilio Messages pour envoyer un SMS
                error_log("SMS DEBUG: Tentative d'envoi à " . $phoneNumber . " depuis " . $this->params->get('twilio_phone_number'));
                $message = $this->twilioClient->messages->create(
                    $phoneNumber, // Destinataire
                    [
                        'from' => $this->params->get('twilio_phone_number'), // Utiliser le numéro configuré
                        'body' => $message
                    ]
                );
                
                if ($this->logger) {
                    $this->logger->info('SMS envoyé avec succès', [
                        'phone' => $phoneNumber,
                        'sid' => $message->sid
                    ]);
                }
                
                error_log("SMS DEBUG: Envoi réussi, SID: " . ($message->sid ?? 'Non disponible'));
                return true;
            } catch (TwilioException $e) {
                if ($this->logger) {
                    $this->logger->error('Échec de l\'envoi du SMS', [
                        'phone' => $phoneNumber,
                        'error' => $e->getMessage()
                    ]);
                }
                error_log("SMS ERROR: Exception Twilio: " . $e->getMessage());
                return false;
            }
        } catch (\Exception $e) {
            // Capture toutes les exceptions possibles
            error_log("SMS ERROR: Exception générale: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Envoyer un mot de passe temporaire par SMS
     */
    public function sendTemporaryPassword(string $phoneNumber, string $tempPassword): bool
    {
        $message = "Votre code de vérification Sportify est : $tempPassword. Ne partagez ce code avec personne.";
        return $this->sendSms($phoneNumber, $message);
    }

    /**
     * Envoyer un lien de réinitialisation de mot de passe par SMS
     */
    public function sendPasswordResetLink(string $phoneNumber, string $resetUrl): bool
    {
        $message = "Pour réinitialiser votre mot de passe Sportify, cliquez sur ce lien: $resetUrl";
        return $this->sendSms($phoneNumber, $message);
    }

    /**
     * Envoyer une notification générique par SMS
     */
    public function sendNotification(string $phoneNumber, string $title, string $content): bool
    {
        $message = "$title\n\n$content";
        return $this->sendSms($phoneNumber, $message);
    }
    
    /**
     * Formater un numéro de téléphone pour l'API Twilio
     */
    private function formatPhoneNumber(string $phoneNumber): string
    {
        // Supprimer tous les caractères non numériques
        $phoneNumber = preg_replace('/[^0-9+]/', '', $phoneNumber);
        
        // S'assurer que le numéro commence par +
        if (!str_starts_with($phoneNumber, '+')) {
            // Si c'est un numéro tunisien sans le +, on ajoute +216
            if (strlen($phoneNumber) === 8) {
                $phoneNumber = '+216' . $phoneNumber;
            } else if (str_starts_with($phoneNumber, '216')) {
                $phoneNumber = '+' . $phoneNumber;
            } else {
                // Sinon on ajoute juste le +
                $phoneNumber = '+' . $phoneNumber;
            }
        }
        
        return $phoneNumber;
    }
    
    /**
     * Vérifier si un numéro de téléphone est valide
     */
    private function isValidPhoneNumber(string $phoneNumber): bool
    {
        // Vérifier que le numéro a une longueur minimale
        if (strlen($phoneNumber) < 10) {
            return false;
        }
        
        // Vérifier que le numéro commence par +
        if (!str_starts_with($phoneNumber, '+')) {
            return false;
        }
        
        return true;
    }
} 