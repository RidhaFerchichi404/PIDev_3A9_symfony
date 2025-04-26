<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use App\Service\TwilioVerifyService;
use App\Service\EmailService;

class VerificationController extends AbstractController
{
    private $twilioVerifyService;
    private $emailService;

    public function __construct(
        TwilioVerifyService $twilioVerifyService,
        EmailService $emailService
    ) {
        $this->twilioVerifyService = $twilioVerifyService;
        $this->emailService = $emailService;
    }

    /**
     * @Route("/verification/send-sms", name="app_verification_send_sms", methods={"POST"})
     */
    public function sendSmsVerification(Request $request): Response
    {
        $data = json_decode($request->getContent(), true);
        $phoneNumber = $data['phone_number'] ?? null;

        if (!$phoneNumber) {
            return new JsonResponse(['success' => false, 'message' => 'Numéro de téléphone requis'], 400);
        }

        $success = $this->twilioVerifyService->sendVerificationSms($phoneNumber);

        return new JsonResponse([
            'success' => $success,
            'message' => $success ? 'Code de vérification envoyé avec succès' : 'Échec de l\'envoi du code'
        ]);
    }

    /**
     * @Route("/verification/send-email", name="app_verification_send_email", methods={"POST"})
     */
    public function sendEmailVerification(Request $request): Response
    {
        $data = json_decode($request->getContent(), true);
        $email = $data['email'] ?? null;

        if (!$email) {
            return new JsonResponse(['success' => false, 'message' => 'Adresse email requise'], 400);
        }

        $success = $this->twilioVerifyService->sendVerificationEmail($email);

        return new JsonResponse([
            'success' => $success,
            'message' => $success ? 'Code de vérification envoyé avec succès' : 'Échec de l\'envoi du code'
        ]);
    }

    /**
     * @Route("/verification/check", name="app_verification_check", methods={"POST"})
     */
    public function checkVerification(Request $request): Response
    {
        $data = json_decode($request->getContent(), true);
        $to = $data['to'] ?? null; // Email ou numéro de téléphone
        $code = $data['code'] ?? null;

        if (!$to || !$code) {
            return new JsonResponse(['success' => false, 'message' => 'Destinataire et code requis'], 400);
        }

        $success = $this->twilioVerifyService->checkVerificationCode($to, $code);

        return new JsonResponse([
            'success' => $success,
            'message' => $success ? 'Code vérifié avec succès' : 'Code invalide ou expiré'
        ]);
    }

    /**
     * @Route("/verification/test-email", name="app_verification_test_email")
     */
    public function testEmail(Request $request): Response
    {
        $email = $request->query->get('email', 'test@example.com');
        
        try {
            $this->emailService->sendTestEmail($email);
            return new JsonResponse([
                'success' => true,
                'message' => 'Email de test envoyé avec succès à ' . $email
            ]);
        } catch (\Exception $e) {
            return new JsonResponse([
                'success' => false,
                'message' => 'Erreur lors de l\'envoi de l\'email: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * @Route("/verification", name="app_verification_form")
     */
    public function showVerificationForm(): Response
    {
        return $this->render('verification/index.html.twig');
    }
} 