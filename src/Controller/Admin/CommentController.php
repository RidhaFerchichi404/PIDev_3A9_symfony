<?php

namespace App\Controller\Admin;

use App\Entity\Comment;
use App\Entity\Post;
use App\Form\CommentType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use App\Repository\CommentRepository;

#[Route('/admin/comment')]
final class CommentController extends AbstractController
{
    #[Route('/', name: 'app_admin_comment_index', methods: ['GET'])]
    public function index(Request $request, CommentRepository $commentRepository): Response
    {
        $page = $request->query->getInt('page', 1);
        $sort = $request->query->get('sort');
        $limit = 10;

        $paginator = $commentRepository->findAllPaginated($page, $limit, $sort);
        $totalComments = count($paginator);
        $totalPages = ceil($totalComments / $limit);

        return $this->render('admin/comment/index.html.twig', [
            'comments' => $paginator,
            'currentPage' => $page,
            'totalPages' => $totalPages,
            'currentSort' => $sort,
        ]);
    }

    #[Route('/post/{id}', name: 'app_admin_post_comments', methods: ['GET'])]
    public function postComments(Request $request, Post $post, CommentRepository $commentRepository): Response
    {
        $page = $request->query->getInt('page', 1);
        $sort = $request->query->get('sort');
        $limit = 10;

        $paginator = $commentRepository->findByPostPaginated($post->getId(), $page, $limit, $sort);
        $totalComments = count($paginator);
        $totalPages = ceil($totalComments / $limit);

        return $this->render('admin/comment/post_comments.html.twig', [
            'post' => $post,
            'comments' => $paginator,
            'currentPage' => $page,
            'totalPages' => $totalPages,
            'currentSort' => $sort,
        ]);
    }

    #[Route('/new/post/{id}', name: 'app_admin_comment_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager, Post $post): Response
    {
        $comment = new Comment();
        $comment->setIdPost($post);
        $comment->setDate(new \DateTime());
        $comment->setLikes(0);
        // In a real application, you would set the user from the security context
        // $comment->setIdUser($this->getUser());
        
        $form = $this->createForm(CommentType::class, $comment);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($comment);
            $entityManager->flush();

            return $this->redirectToRoute('app_admin_post_comments', ['id' => $post->getId()], Response::HTTP_SEE_OTHER);
        }

        return $this->render('admin/comment/new.html.twig', [
            'comment' => $comment,
            'post' => $post,
            'form' => $form,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_admin_comment_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Comment $comment, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(CommentType::class, $comment);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_admin_post_comments', ['id' => $comment->getIdPost()->getId()], Response::HTTP_SEE_OTHER);
        }

        return $this->render('admin/comment/edit.html.twig', [
            'comment' => $comment,
            'form' => $form,
        ]);
    }

    #[Route('/{id}/show', name: 'app_admin_comment_show', methods: ['GET'])]
    public function show(Comment $comment): Response
    {
        return $this->render('admin/comment/show.html.twig', [
            'comment' => $comment,
        ]);
    }

    #[Route('/{id}', name: 'app_admin_comment_delete', methods: ['POST'])]
    public function delete(Request $request, Comment $comment, EntityManagerInterface $entityManager): Response
    {
        $postId = $comment->getIdPost()->getId();
        
        if ($this->isCsrfTokenValid('delete'.$comment->getId(), $request->getPayload()->getString('_token'))) {
            $entityManager->remove($comment);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_admin_post_comments', ['id' => $postId], Response::HTTP_SEE_OTHER);
    }
} 