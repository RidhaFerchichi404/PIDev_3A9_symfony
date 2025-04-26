<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[Route('/front')]
class FrontController extends AbstractController
{
    #[Route('/', name: 'app_front_index')]
    #[IsGranted('ROLE_USER')]
    public function index(): Response
    {
        return $this->render('front/index.html.twig', [
            'user' => $this->getUser(),
        ]);
    }
    
    #[Route('/profile', name: 'app_front_profile')]
    #[IsGranted('ROLE_USER')]
    public function profile(): Response
    {
        return $this->render('front/profile.html.twig', [
            'user' => $this->getUser(),
        ]);
    }
} 