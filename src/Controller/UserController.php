<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\UserType;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

#[Route('/user')]
final class UserController extends AbstractController{
    #[Route(name: 'app_user_index', methods: ['GET'])]
    public function index(UserRepository $userRepository): Response
    {
        return $this->render('user/index.html.twig', [
            'users' => $userRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_user_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager, UserPasswordHasherInterface $passwordHasher): Response
    {
        $user = new User();
        $user->setRole('ROLE_USER');
        $user->setIsactive(true);
        $user->setSubscriptionenddate(new \DateTime('+1 month'));
        $user->setCreatedAt(new \DateTime());
        $user->setUpdatedAt(new \DateTime());
        $user->setViolationCount(0);
        
        $form = $this->createForm(UserType::class, $user, [
            'is_new' => true
        ]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            if (empty($user->getPasswordHash())) {
                $this->addFlash('error', 'Le mot de passe est obligatoire pour un nouvel utilisateur.');
                return $this->render('user/new.html.twig', [
                    'user' => $user,
                    'form' => $form,
                ]);
            }
            
            // Hash the password
            $hashedPassword = $passwordHasher->hashPassword(
                $user,
                $user->getPasswordHash()
            );
            $user->setPasswordHash($hashedPassword);
            
            $entityManager->persist($user);
            $entityManager->flush();

            $this->addFlash('success', 'L\'utilisateur a été créé avec succès.');
            return $this->redirectToRoute('app_user_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('user/new.html.twig', [
            'user' => $user,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_user_show', methods: ['GET'])]
    public function show(User $user): Response
    {
        return $this->render('user/show.html.twig', [
            'user' => $user,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_user_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, User $user, EntityManagerInterface $entityManager, UserPasswordHasherInterface $passwordHasher): Response
    {
        $currentPassword = $user->getPasswordHash();
        
        $form = $this->createForm(UserType::class, $user, [
            'is_new' => false
        ]);
        
        // Store the user data before the form is submitted
        $originalData = clone $user;
        
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // Check if password is empty and restore the original password
            if (empty($user->getPasswordHash()) || $user->getPasswordHash() === null) {
                $user->setPasswordHash($currentPassword);
            } else if ($user->getPasswordHash() !== $currentPassword) {
                // Hash the new password only if it has changed
                $hashedPassword = $passwordHasher->hashPassword(
                    $user,
                    $user->getPasswordHash()
                );
                $user->setPasswordHash($hashedPassword);
            }
            
            $user->setUpdatedAt(new \DateTime());
            $entityManager->flush();
            
            $this->addFlash('success', 'L\'utilisateur a été modifié avec succès.');
            return $this->redirectToRoute('app_user_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('user/edit.html.twig', [
            'user' => $user,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_user_delete', methods: ['POST'])]
    public function delete(Request $request, User $user, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$user->getId(), $request->getPayload()->getString('_token'))) {
            $entityManager->remove($user);
            $entityManager->flush();
            $this->addFlash('success', 'L\'utilisateur a été supprimé avec succès.');
        }

        return $this->redirectToRoute('app_user_index', [], Response::HTTP_SEE_OTHER);
    }
}
 