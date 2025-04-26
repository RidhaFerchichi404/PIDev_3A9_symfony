<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{
    #[Route('/', name: 'app_home')]
    public function index(): Response
    {
        if ($this->getUser()) {
            if (in_array('ROLE_ADMIN', $this->getUser()->getRoles())) {
                return $this->redirectToRoute('app_user_index');
            } else {
                return $this->redirectToRoute('app_front_index');
            }
        }
        
        // Redirect to login if not logged in
        return $this->redirectToRoute('app_login');
    }
} 