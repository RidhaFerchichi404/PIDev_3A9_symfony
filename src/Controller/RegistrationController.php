<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\RegistrationFormType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;

class RegistrationController extends AbstractController
{
    #[Route('/register', name: 'app_register')]
    public function register(Request $request, UserPasswordHasherInterface $userPasswordHasher, EntityManagerInterface $entityManager): Response
    {
        $user = new User();
        $form = $this->createForm(RegistrationFormType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // Récupérer le mot de passe en clair
            $plainPassword = $form->get('plainPassword')->getData();
            
            // Vérifier que le mot de passe n'est pas vide
            if (empty($plainPassword)) {
                $this->addFlash('error', 'Le mot de passe ne peut pas être vide.');
                return $this->render('registration/register.html.twig', [
                    'registrationForm' => $form->createView(),
                ]);
            }
            
            // Hasher le mot de passe avant de l'enregistrer
            $user->setPasswordHash(
                $userPasswordHasher->hashPassword(
                    $user,
                    $plainPassword
                )
            );
            
            // Set default role for regular users (ROLE_USER is already set by default in the entity)
            $user->setRole('ROLE_USER');
            
            // Set subscription end date to one month from now
            $user->setSubscriptionenddate(new \DateTime('+1 month'));
            
            // Ensure user is active
            $user->setIsactive(true);
            
            // Ensure required fields are set with proper validation
            // Age, CIN, and Location are already handled by the form
            
            // Log registration data for debugging
            error_log(sprintf(
                "New user registration: %s %s, Email: %s, Age: %d, CIN: %s, Location: %s",
                $user->getFirstname(),
                $user->getLastname(),
                $user->getEmail(),
                $user->getAge() ?? 0,
                $user->getCin() ?? 'Not provided',
                $user->getLocation() ?? 'Not provided'
            ));

            $entityManager->persist($user);
            $entityManager->flush();

            $this->addFlash('success', 'Votre compte a été créé avec succès. Vous pouvez maintenant vous connecter.');

            return $this->redirectToRoute('app_login');
        }

        return $this->render('registration/register.html.twig', [
            'registrationForm' => $form->createView(),
        ]);
    }
} 