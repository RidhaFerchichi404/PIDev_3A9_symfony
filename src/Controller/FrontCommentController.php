<?php

namespace App\Controller;

use App\Entity\Comment;
use App\Entity\Post;
use App\Form\CommentType;
use App\Service\BadWordFilter;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

#[Route('/blog/comment')]
final class FrontCommentController extends AbstractController
{
    public function __construct(
        private readonly BadWordFilter $badWordFilter
    ) {
    }

    #[Route('/new/{id}', name: 'app_front_comment_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager, Post $post): Response
    {
        $comment = new Comment();
        $comment->setIdPost($post);
        $comment->setDate(new \DateTime());
        $comment->setLikes(0);
        
        $form = $this->createFormBuilder($comment)
            ->add('comment', TextareaType::class, [
                'label' => 'Your Comment',
                'attr' => [
                    'class' => 'form-control',
                    'rows' => 5
                ]
            ])
            ->add('submit', SubmitType::class, [
                'label' => 'Submit Comment',
                'attr' => ['class' => 'btn btn-primary mt-3']
            ])
            ->getForm();
            
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // Filter bad words from the comment
            $commentText = $comment->getComment();
            $filteredComment = $this->badWordFilter->filterBadWords($commentText);
            $comment->setComment($filteredComment);
            
            // Generate a unique ID for the comment
            $lastComment = $entityManager->getRepository(Comment::class)
                ->findOneBy([], ['id' => 'DESC']);
            
            $newId = 1;
            if ($lastComment) {
                $newId = $lastComment->getId() + 1;
            }
            
            $comment->setId($newId);
            
            $entityManager->persist($comment);
            $entityManager->flush();

            return $this->redirectToRoute('app_front_post_show', ['id' => $post->getId()], Response::HTTP_SEE_OTHER);
        }

        return $this->render('front/comment/new.html.twig', [
            'comment' => $comment,
            'post' => $post,
            'form' => $form,
        ]);
    }

    #[Route('/{id}/like', name: 'app_front_comment_like', methods: ['POST'])]
    public function like(Comment $comment, EntityManagerInterface $entityManager): Response
    {
        $comment->setLikes($comment->getLikes() + 1);
        $entityManager->flush();

        return $this->redirectToRoute('app_front_post_show', [
            'id' => $comment->getIdPost()->getId()
        ]);
    }
} 