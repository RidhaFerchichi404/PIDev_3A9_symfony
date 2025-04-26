<?php
namespace App\EventListener;

use App\Entity\Promotion;
use App\Service\EmailService;
use App\Repository\UserRepository;
use Doctrine\Persistence\Event\LifecycleEventArgs;

class PromotionListener
{
    private EmailService $emailService;
    private UserRepository $userRepository;

    public function __construct(EmailService $emailService, UserRepository $userRepository)
    {
        $this->emailService = $emailService;
        $this->userRepository = $userRepository;
    }

    public function postPersist(Promotion $promotion, LifecycleEventArgs $args): void
    {
        $users = $this->userRepository->findAll();
        
        $this->emailService->sendPromotionEmail(
            $users,
            $promotion->getTypeReduction(),
            $promotion->getDescription()
        );
    }
}