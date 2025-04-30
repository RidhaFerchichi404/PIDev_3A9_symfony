<?php

namespace App\Controller;

use App\Entity\Post;
use App\Repository\PostRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/blog')]
final class FrontPostController extends AbstractController
{
    #[Route('/', name: 'app_front_post_index', methods: ['GET'])]
    public function index(Request $request, PostRepository $postRepository): Response
    {
        $type = $request->query->get('type');
        $sort = $request->query->get('sort');
        $page = $request->query->getInt('page', 1);
        $limit = 5; // Number of posts per page

        if ($type) {
            $paginator = $postRepository->findByType($type, $page, $limit);
        } elseif ($sort === 'most_commented') {
            $paginator = $postRepository->findMostCommented($page, $limit);
        } else {
            $paginator = $postRepository->findAllPaginated($page, $limit);
        }

        // Get recent posts for right sidebar
        $recentPosts = $postRepository->findBy([], ['dateU' => 'DESC'], 5);
        
        // Get most commented posts for left sidebar
        $mostCommentedPosts = $postRepository->findMostCommented(1, 5);

        $totalPosts = count($paginator);
        $totalPages = ceil($totalPosts / $limit);

        return $this->render('postandcomment/front/post/index.html.twig', [
            'posts' => $paginator,
            'recentPosts' => $recentPosts,
            'mostCommentedPosts' => $mostCommentedPosts,
            'currentType' => $type,
            'currentSort' => $sort,
            'currentPage' => $page,
            'totalPages' => $totalPages,
        ]);
    }

    #[Route('/{id}', name: 'app_front_post_show', methods: ['GET'])]
    public function show(Post $post, PostRepository $postRepository): Response
    {
        // Get recent posts for right sidebar
        $recentPosts = $postRepository->findBy([], ['dateU' => 'DESC'], 5);
        
        // Get most commented posts for left sidebar
        $mostCommentedPosts = $postRepository->findMostCommented(1, 5);
        
        return $this->render('postandcomment/front/post/show.html.twig', [
            'post' => $post,
            'recentPosts' => $recentPosts,
            'mostCommentedPosts' => $mostCommentedPosts,
        ]);
    }
} 