<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use App\Service\SmsService;

class SmsController extends AbstractController
{
    private $smsService;

    public function __construct(SmsService $smsService)
    {
        $this->smsService = $smsService;
    }

    /**
     * @Route("/sms/send", name="app_sms_send", methods={"POST"})
     */
    public function sendSms(Request $request): Response
    {
        $data = json_decode($request->getContent(), true);
        $phoneNumber = $data['phone_number'] ?? null;
        $message = $data['message'] ?? 'Test message from Sportify';

        if (!$phoneNumber) {
            return new JsonResponse(['success' => false, 'message' => 'Numéro de téléphone requis'], 400);
        }

        $success = $this->smsService->sendSms($phoneNumber, $message);

        return new JsonResponse([
            'success' => $success,
            'message' => $success ? 'SMS envoyé avec succès' : 'Échec de l\'envoi du SMS'
        ]);
    }

    /**
     * @Route("/sms/password", name="app_sms_password", methods={"POST"})
     */
    public function sendTemporaryPassword(Request $request): Response
    {
        $data = json_decode($request->getContent(), true);
        $phoneNumber = $data['phone_number'] ?? null;
        $password = $data['password'] ?? substr(str_shuffle('0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ'), 0, 6);

        if (!$phoneNumber) {
            return new JsonResponse(['success' => false, 'message' => 'Numéro de téléphone requis'], 400);
        }

        $success = $this->smsService->sendTemporaryPassword($phoneNumber, $password);

        return new JsonResponse([
            'success' => $success,
            'message' => $success ? 'Code de vérification envoyé avec succès' : 'Échec de l\'envoi du code',
            'password' => $password
        ]);
    }

    /**
     * @Route("/sms/reset", name="app_sms_reset", methods={"POST"})
     */
    public function sendPasswordResetLink(Request $request): Response
    {
        $data = json_decode($request->getContent(), true);
        $phoneNumber = $data['phone_number'] ?? null;
        $resetToken = $data['token'] ?? substr(md5(uniqid(mt_rand(), true)), 0, 32);
        $resetUrl = $this->generateUrl('app_reset_password', ['token' => $resetToken], 0);

        if (!$phoneNumber) {
            return new JsonResponse(['success' => false, 'message' => 'Numéro de téléphone requis'], 400);
        }

        $success = $this->smsService->sendPasswordResetLink($phoneNumber, $resetUrl);

        return new JsonResponse([
            'success' => $success,
            'message' => $success ? 'Lien de réinitialisation envoyé avec succès' : 'Échec de l\'envoi du lien',
            'token' => $resetToken,
            'url' => $resetUrl
        ]);
    }

    /**
     * @Route("/sms", name="app_sms_form")
     */
    public function showSmsForm(): Response
    {
        return $this->render('sms/index.html.twig');
    }

    /**
     * @Route("/sms/test", name="app_sms_test", methods={"GET"})
     */
    public function testSms(): Response
    {
        // Générer un code aléatoire pour le test
        $code = substr(str_shuffle('0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ'), 0, 6);
        
        // Utiliser un numéro de téléphone de test
        $phoneNumber = "+33600000000";
        
        // Envoyer le SMS de test
        $success = $this->smsService->sendTemporaryPassword($phoneNumber, $code);
        
        // Retourner le résultat
        return new JsonResponse([
            'success' => $success,
            'message' => $success ? 'SMS de test envoyé avec succès (simulé)' : 'Échec de l\'envoi du SMS de test',
            'phone' => $phoneNumber,
            'code' => $code
        ]);
    }
} 