<?php

namespace App\Controller\Admin;

use App\Repository\PostRepository;
use App\Repository\CommentRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/admin')]
class DashboardController extends AbstractController
{
    #[Route('', name: 'app_admin_dashboard')]
    public function index(PostRepository $postRepository, CommentRepository $commentRepository): Response
    {
        // Get statistics
        $totalPosts = $postRepository->count([]);
        $totalComments = $commentRepository->count([]);
        
        // Get posts by type
        $postsByType = $postRepository->countByType();
        
        // Get recent posts
        $recentPosts = $postRepository->findBy([], ['dateU' => 'DESC'], 5);
        
        // Get recent comments
        $recentComments = $commentRepository->findBy([], ['date' => 'DESC'], 5);

        return $this->render('admin/dashboard/index.html.twig', [
            'totalPosts' => $totalPosts,
            'totalComments' => $totalComments,
            'postsByType' => $postsByType,
            'recentPosts' => $recentPosts,
            'recentComments' => $recentComments,
        ]);
    }
} 