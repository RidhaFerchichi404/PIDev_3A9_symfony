<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\ProfileEditType;
use App\Form\ChangePasswordType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Security\Csrf\TokenGenerator\TokenGeneratorInterface;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;
use App\Service\EmailService;
use App\Service\SmsService;

#[Route('/profile')]
class ProfileController extends AbstractController
{
    #[Route('/', name: 'app_profile_index')]
    #[IsGranted('ROLE_USER')]
    public function index(): Response
    {
        // Make sure a user is logged in
        $user = $this->getUser();
        if (!$user) {
            return $this->redirectToRoute('app_login');
        }

        return $this->render('profile/index.html.twig', [
            'user' => $user,
        ]);
    }
    
    #[Route('/edit', name: 'app_profile_edit')]
    #[IsGranted('ROLE_USER')]
    public function edit(Request $request, EntityManagerInterface $entityManager): Response
    {
        $user = $this->getUser();
        $form = $this->createForm(ProfileEditType::class, $user);
        $form->handleRequest($request);
        
        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();
            $this->addFlash('success', 'Your profile has been updated successfully! All changes have been saved.');
            
            return $this->redirectToRoute('app_profile_index');
        }
        
        return $this->render('profile/edit.html.twig', [
            'form' => $form->createView(),
            'user' => $user,
        ]);
    }
    
    #[Route('/change-password', name: 'app_profile_change_password')]
    #[IsGranted('ROLE_USER')]
    public function changePassword(Request $request, UserPasswordHasherInterface $passwordHasher, EntityManagerInterface $entityManager): Response
    {
        $user = $this->getUser();
        $form = $this->createForm(ChangePasswordType::class);
        $form->handleRequest($request);
        
        if ($form->isSubmitted() && $form->isValid()) {
            // Hash the new password
            $newPassword = $form->get('newPassword')->getData();
            
            $hashedPassword = $passwordHasher->hashPassword(
                $user,
                $newPassword
            );
            
            // Update user password
            $user->setPasswordHash($hashedPassword);
            $entityManager->flush();
            
            $this->addFlash('success', 'Your password has been changed successfully! Your account is now secure with the new password.');
            
            return $this->redirectToRoute('app_profile_index');
        }
        
        return $this->render('profile/change_password.html.twig', [
            'form' => $form->createView(),
        ]);
    }
    
    #[Route('/forgot-password', name: 'app_forgot_password')]
    public function forgotPassword(
        Request $request, 
        EntityManagerInterface $entityManager, 
        TokenGeneratorInterface $tokenGenerator,
        SmsService $smsService
    ): Response 
    {
        if ($this->getUser()) {
            return $this->redirectToRoute('app_profile_index');
        }
        
        $smsSent = false;
        $notFound = false;
        $noPhoneNumber = false;
        
        if ($request->isMethod('POST')) {
            $email = $request->request->get('email');
            $user = $entityManager->getRepository(User::class)->findOneBy(['email' => $email]);
            
            if (!$user) {
                $notFound = true;
                $this->addFlash('error', 'Aucun compte n\'a été trouvé avec cette adresse email.');
            } elseif (!$user->getPhonenumber()) {
                $noPhoneNumber = true;
                $this->addFlash('error', 'Votre compte n\'a pas de numéro de téléphone associé. Veuillez contacter le support.');
            } else {
                // Generate reset token
                $token = $tokenGenerator->generateToken();
                $user->setResetToken($token);
                $user->setResetTokenExpiresAt(new \DateTimeImmutable('+1 hour'));
                
                // Générer un code de vérification à 6 chiffres
                $verificationCode = sprintf('%06d', mt_rand(0, 999999));
                $user->setVerificationCode($verificationCode);
                $user->setVerificationCodeExpiresAt(new \DateTimeImmutable('+15 minutes'));
                
                $entityManager->flush();
                
                // Generate reset URL
                $resetUrl = $this->generateUrl('app_reset_password', ['token' => $token], true);
                
                try {
                    // Envoyer le lien de réinitialisation et le code par SMS
                    $message = "Votre code de vérification Sportify: $verificationCode. Vous pourrez également utiliser ce lien: $resetUrl";
                    $success = $smsService->sendSms(
                        $user->getPhonenumber(),
                        $message
                    );
                    
                    $smsSent = $success;
                    
                    if ($success) {
                        // Stocker l'email dans la session pour le récupérer sur la page de vérification
                        $request->getSession()->set('reset_email', $email);
                        
                        // Rediriger vers la page de vérification du code
                        return $this->redirectToRoute('app_verify_code');
                    } else {
                        error_log('Échec de l\'envoi du SMS au numéro: ' . $user->getPhonenumber());
                        $this->addFlash('error', 'L\'envoi du SMS a échoué. Veuillez réessayer plus tard.');
                    }
                } catch (\Exception $e) {
                    // Log the error but don't expose it to the user for security
                    error_log('Error sending password reset SMS: ' . $e->getMessage());
                    $this->addFlash('error', 'Une erreur est survenue lors de l\'envoi du SMS. Veuillez réessayer plus tard.');
                }
            }
        }
        
        return $this->render('profile/forgot_password.html.twig', [
            'notFound' => $notFound,
            'noPhoneNumber' => $noPhoneNumber
        ]);
    }
    
    #[Route('/verify-code', name: 'app_verify_code')]
    public function verifyCode(
        Request $request,
        EntityManagerInterface $entityManager
    ): Response
    {
        // Récupérer l'email de la session
        $email = $request->getSession()->get('reset_email');
        
        // Si pas d'email dans la session, rediriger vers forgot password
        if (!$email) {
            $this->addFlash('error', 'Votre session a expiré. Veuillez recommencer le processus de réinitialisation.');
            return $this->redirectToRoute('app_forgot_password');
        }
        
        $codeInvalid = false;
        $codeExpired = false;
        
        if ($request->isMethod('POST')) {
            // Récupérer le code de vérification
            $code = $request->request->get('verification_code');
            
            // Trouver l'utilisateur avec cet email
            $user = $entityManager->getRepository(User::class)->findOneBy(['email' => $email]);
            
            if (!$user) {
                $this->addFlash('error', 'Utilisateur introuvable. Veuillez recommencer le processus de réinitialisation.');
                return $this->redirectToRoute('app_forgot_password');
            }
            
            // Vérifier que le code est correct et n'a pas expiré
            if ($user->getVerificationCode() !== $code) {
                $codeInvalid = true;
                $this->addFlash('error', 'Le code de vérification est incorrect. Veuillez réessayer.');
            } elseif (!$user->getVerificationCodeExpiresAt() || $user->getVerificationCodeExpiresAt() < new \DateTimeImmutable()) {
                $codeExpired = true;
                $this->addFlash('error', 'Le code de vérification a expiré. Veuillez recommencer le processus de réinitialisation.');
                return $this->redirectToRoute('app_forgot_password');
            } else {
                // Code valide, on redirige vers la page de réinitialisation du mot de passe
                return $this->redirectToRoute('app_reset_password', ['token' => $user->getResetToken()]);
            }
        }
        
        return $this->render('profile/verify_code.html.twig', [
            'email' => $email,
            'codeInvalid' => $codeInvalid,
            'codeExpired' => $codeExpired
        ]);
    }
    
    #[Route('/reset-password/{token}', name: 'app_reset_password')]
    public function resetPassword(
        string $token, 
        Request $request, 
        EntityManagerInterface $entityManager,
        UserPasswordHasherInterface $passwordHasher
    ): Response 
    {
        // Find user by token
        $user = $entityManager->getRepository(User::class)->findOneBy(['reset_token' => $token]);
        
        // Check if token exists and is valid
        if (!$user || !$user->getResetTokenExpiresAt() || $user->getResetTokenExpiresAt() < new \DateTimeImmutable()) {
            $this->addFlash('error', 'Le lien de réinitialisation du mot de passe est invalide ou a expiré.');
            return $this->redirectToRoute('app_forgot_password');
        }
        
        if ($request->isMethod('POST')) {
            $password = $request->request->get('password');
            $confirmPassword = $request->request->get('confirm_password');
            
            if ($password !== $confirmPassword) {
                $this->addFlash('error', 'Les mots de passe ne correspondent pas.');
            } else {
                // Hash the new password
                $hashedPassword = $passwordHasher->hashPassword($user, $password);
                $user->setPasswordHash($hashedPassword);
                
                // Clear the reset token
                $user->setResetToken(null);
                $user->setResetTokenExpiresAt(null);
                
                $entityManager->flush();
                
                $this->addFlash('success', 'Votre mot de passe a été réinitialisé avec succès.');
                return $this->redirectToRoute('app_login');
            }
        }
        
        return $this->render('profile/reset_password.html.twig', [
            'token' => $token
        ]);
    }
} 