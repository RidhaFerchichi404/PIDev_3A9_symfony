<?php

namespace App\Controller;

use App\Service\StaticSession;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class SecurityController extends AbstractController
{
    #[Route('/switch-role/{role}', name: 'app_switch_role')]
    public function switchRole(string $role, StaticSession $staticSession): Response
    {
        $staticSession->setRole($role);
        return $this->redirectToRoute('app_home');
    }
} 