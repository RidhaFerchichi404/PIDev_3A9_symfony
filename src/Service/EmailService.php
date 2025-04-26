<?php

namespace App\Service;

use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;
use Symfony\Component\Mime\Address;
use Psr\Log\LoggerInterface;

class EmailService
{
    private $mailer;
    private $params;
    private $logger;

    public function __construct(
        MailerInterface $mailer, 
        ParameterBagInterface $params,
        LoggerInterface $logger = null
    ) {
        $this->mailer = $mailer;
        $this->params = $params;
        $this->logger = $logger;
    }

    /**
     * Send a password reset email to the user
     */
    public function sendPasswordResetEmail(string $recipientEmail, string $recipientName, string $resetUrl): void
    {
        try {
            $senderEmail = $this->params->get('sender_email');
            $senderName = $this->params->get('sender_name');
            $accountSid = $this->params->get('sendgrid_account_sid');
            
            if ($this->logger) {
                $this->logger->info('Preparing to send password reset email', [
                    'recipient' => $recipientEmail,
                    'reset_url' => $resetUrl
                ]);
            }

            $email = (new Email())
                ->from(new Address($senderEmail, $senderName))
                ->to(new Address($recipientEmail, $recipientName))
                ->subject('Password Reset Request')
                ->html($this->getPasswordResetTemplate($recipientName, $resetUrl));
                
            // Add Twilio account SID to custom headers for tracking
            $email->getHeaders()->addTextHeader('X-Twilio-Account-SID', $accountSid);

            // Désactiver temporairement pour le debug et toujours envoyer
            // if (str_contains($this->params->get('mailer_dsn', ''), 'null') || 
            //    $this->params->get('sendgrid_api_key') === 'null') {
            //    if ($this->logger) {
            //        $this->logger->info('Email not actually sent - using null mailer for testing', [
            //            'recipient' => $recipientEmail,
            //            'subject' => 'Password Reset Request',
            //            'reset_url' => $resetUrl
            //        ]);
            //    }
            //    // Log message in dev environment to show the link
            //    if ($this->params->get('kernel.environment') === 'dev') {
            //        echo "\n[DEV MODE] Password reset link: " . $resetUrl . "\n";
            //    }
            //} else {
                $this->mailer->send($email);
            //}
            
            if ($this->logger) {
                $this->logger->info('Successfully sent password reset email', [
                    'recipient' => $recipientEmail
                ]);
            }
        } catch (\Exception $e) {
            if ($this->logger) {
                $this->logger->error('Failed to send password reset email', [
                    'recipient' => $recipientEmail,
                    'error' => $e->getMessage(),
                    'trace' => $e->getTraceAsString()
                ]);
            }
            
            // Log the error with detailed info about the environment
            $this->logger->error('Email configuration details', [
                'mailer_dsn' => $this->params->get('mailer_dsn'),
                'sender_email' => $this->params->get('sender_email'),
                'sendgrid_api_key_length' => strlen($this->params->get('sendgrid_api_key', '')),
                'environment' => $this->params->get('kernel.environment')
            ]);
            
            throw $e;
        }
    }

    /**
     * Returns an HTML template for password reset emails
     */
    private function getPasswordResetTemplate(string $name, string $resetUrl): string
    {
        return "
        <div style='max-width: 600px; margin: 0 auto; padding: 20px; font-family: Arial, sans-serif;'>
            <div style='background-color: #8A2BE2; padding: 20px; border-radius: 10px 10px 0 0; text-align: center;'>
                <h1 style='color: white; margin: 0;'>Sportify</h1>
            </div>
            <div style='background-color: #f9f9f9; padding: 20px; border-radius: 0 0 10px 10px; border: 1px solid #ddd; border-top: none;'>
                <h2 style='color: #333; margin-top: 0;'>Password Reset Request</h2>
                <p>Hello {$name},</p>
                <p>We received a request to reset your password. If you didn't make this request, you can ignore this email.</p>
                <p>To reset your password, click the button below:</p>
                <div style='text-align: center; margin: 30px 0;'>
                    <a href='{$resetUrl}' style='background-color: #FF7700; color: white; padding: 12px 25px; text-decoration: none; border-radius: 50px; font-weight: bold; display: inline-block;'>
                        Reset Your Password
                    </a>
                </div>
                <p>This link will expire in 1 hour for security reasons.</p>
                <p>If you're having trouble clicking the button, copy and paste the URL below into your web browser:</p>
                <p style='word-break: break-all; font-size: 12px; color: #777;'>{$resetUrl}</p>
                <hr style='border: none; border-top: 1px solid #ddd; margin: 20px 0;'>
                <p style='font-size: 12px; color: #777;'>This is an automated message, please do not reply to this email.</p>
            </div>
        </div>
        ";
    }

    /**
     * Send a test email to verify the SendGrid configuration
     */
    public function sendTestEmail(string $recipientEmail): void
    {
        try {
            $senderEmail = $this->params->get('sender_email');
            $senderName = $this->params->get('sender_name');
            $apiKey = $this->params->get('sendgrid_api_key');
            $accountSid = $this->params->get('sendgrid_account_sid');
            
            // Skip validation for null API keys in dev mode
            if ($apiKey !== 'null' && strpos($apiKey, 'SG.') !== 0 && !is_string($apiKey)) {
                throw new \Exception('Invalid SendGrid API key format. Please update your configuration with a valid API key.');
            }
            
            if ($this->logger) {
                $this->logger->info('Sending test email', [
                    'recipient' => $recipientEmail,
                    'account_sid' => $accountSid
                ]);
            }

            $email = (new Email())
                ->from(new Address($senderEmail, $senderName))
                ->to($recipientEmail)
                ->subject('Sportify - Email Configuration Test')
                ->html($this->getTestEmailTemplate($accountSid));
                
            // Add Twilio account SID to custom headers for tracking
            $email->getHeaders()->addTextHeader('X-Twilio-Account-SID', $accountSid);

            // Désactiver temporairement pour le debug et toujours envoyer
            // if (str_contains($this->params->get('mailer_dsn', ''), 'null') || 
            //     $this->params->get('sendgrid_api_key') === 'null') {
            //     if ($this->logger) {
            //         $this->logger->info('Test email not actually sent - using null mailer for testing', [
            //             'recipient' => $recipientEmail,
            //             'subject' => 'Email Configuration Test'
            //         ]);
            //     }
            //     // Log message to console in dev environment
            //     if ($this->params->get('kernel.environment') === 'dev') {
            //         echo "\n[DEV MODE] Test email would have been sent to: " . $recipientEmail . "\n";
            //     }
            // } else {
                $this->mailer->send($email);
            // }
            
            if ($this->logger) {
                $this->logger->info('Successfully sent test email', [
                    'recipient' => $recipientEmail
                ]);
            }
        } catch (\Exception $e) {
            if ($this->logger) {
                $this->logger->error('Failed to send test email', [
                    'recipient' => $recipientEmail,
                    'error' => $e->getMessage(),
                    'trace' => $e->getTraceAsString()
                ]);
            }
            
            // Log the error with detailed info about the environment
            $this->logger->error('Email configuration details', [
                'mailer_dsn' => $this->params->get('mailer_dsn'),
                'sender_email' => $this->params->get('sender_email'),
                'sendgrid_api_key_length' => strlen($this->params->get('sendgrid_api_key', '')),
                'environment' => $this->params->get('kernel.environment')
            ]);
            
            throw $e;
        }
    }

    /**
     * Returns an HTML template for test emails
     */
    private function getTestEmailTemplate(string $accountSid = null): string
    {
        return "
        <div style='max-width: 600px; margin: 0 auto; padding: 20px; font-family: Arial, sans-serif;'>
            <div style='background-color: #8A2BE2; padding: 20px; border-radius: 10px 10px 0 0; text-align: center;'>
                <h1 style='color: white; margin: 0;'>Sportify</h1>
            </div>
            <div style='background-color: #f9f9f9; padding: 20px; border-radius: 0 0 10px 10px; border: 1px solid #ddd; border-top: none;'>
                <h2 style='color: #333; margin-top: 0;'>Email Configuration Test</h2>
                <p>Congratulations! If you're seeing this email, your Twilio SendGrid configuration is working correctly.</p>
                <p>You can now use this configuration for sending password reset emails and other transactional messages.</p>
                " . ($accountSid ? "<p><strong>Twilio Account:</strong> {$accountSid}</p>" : "") . "
                <hr style='border: none; border-top: 1px solid #ddd; margin: 20px 0;'>
                <p style='font-size: 12px; color: #777;'>This is a test email sent from your Sportify application.</p>
            </div>
        </div>
        ";
    }
} 