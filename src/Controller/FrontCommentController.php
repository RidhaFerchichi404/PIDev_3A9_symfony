<?php

namespace App\Controller;

use App\Entity\Comment;
use App\Entity\Post;
use App\Entity\CommentLike;
use App\Form\CommentType;
use App\Service\BadWordFilter;
use App\Service\EmojiService;
use App\Repository\CommentLikeRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;

#[Route('/blog/comment')]
final class FrontCommentController extends AbstractController
{
    public function __construct(
        private readonly BadWordFilter $badWordFilter,
        private readonly EmojiService $emojiService
    ) {
    }

    #[Route('/new/{id}', name: 'app_front_comment_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager, Post $id): Response
    {
        // Check if the user is logged in
        $user = $this->getUser();
        if (!$user) {
            throw new AccessDeniedException('You must be logged in to post a comment');
        }

        $comment = new Comment();
        $comment->setIdPost($id);
        $comment->setDate(new \DateTime());
        $comment->setLikes(0);
        $comment->setIdUser($user); // Set the current user
        
        $form = $this->createFormBuilder($comment)
            ->add('comment', TextareaType::class, [
                'label' => 'Your Comment',
                'attr' => [
                    'class' => 'form-control',
                    'rows' => 5,
                    'placeholder' => 'Enter your comment here. You can use emoji shortcodes like :smile: :heart: :thumbsup:'
                ]
            ])
            ->add('submit', SubmitType::class, [
                'label' => 'Submit Comment',
                'attr' => ['class' => 'btn btn-primary mt-3']
            ])
            ->getForm();
            
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // Get the raw comment text
            $commentText = $comment->getComment();
            
            // First convert any emoji shortcodes to actual emojis
            $emojiComment = $this->emojiService->convertToEmoji($commentText);
            
            // Then filter bad words from the emoji-converted text
            $filteredComment = $this->badWordFilter->filterBadWords($emojiComment);
            
            // Set the processed comment
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

            return $this->redirectToRoute('app_front_post_show', ['id' => $id->getId()], Response::HTTP_SEE_OTHER);
        }

        return $this->render('postandcomment/front/comment/new.html.twig', [
            'comment' => $comment,
            'post' => $id,
            'form' => $form,
            'emojis' => $this->emojiService->getAvailableEmojis()
        ]);
    }

    #[Route('/{id}/like', name: 'app_front_comment_like', methods: ['POST'])]
    public function like(Comment $comment, EntityManagerInterface $entityManager, CommentLikeRepository $likeRepository): Response
    {
        // Check if the user is logged in
        $user = $this->getUser();
        if (!$user) {
            throw new AccessDeniedException('You must be logged in to like a comment');
        }

        // Check if the user has already liked the comment
        $existingLike = $likeRepository->findOneByUserAndComment($user, $comment);

        if ($existingLike) {
            // User already liked this comment, so remove the like
            $entityManager->remove($existingLike);
            $comment->setLikes($comment->getLikes() - 1);
        } else {
            // User has not liked this comment yet, so add a new like
            $like = new CommentLike();
            $like->setUser($user);
            $like->setComment($comment);
            $entityManager->persist($like);
            $comment->setLikes($comment->getLikes() + 1);
        }

        $entityManager->flush();

        return $this->redirectToRoute('app_front_post_show', [
            'id' => $comment->getIdPost()->getId()
        ]);
    }
} 