<?php

namespace App\Command;

use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

#[AsCommand(
    name: 'app:fix-password',
    description: 'Fix user passwords',
)]
class FixPasswordCommand extends Command
{
    public function __construct(
        private EntityManagerInterface $entityManager,
        private UserRepository $userRepository,
        private UserPasswordHasherInterface $passwordHasher
    ) {
        parent::__construct();
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);
        $users = $this->userRepository->findAll();

        foreach ($users as $user) {
            // Si le mot de passe est en clair (non hashé)
            if ($user->getPasswordHash() === '123456789') {
                $hashedPassword = $this->passwordHasher->hashPassword($user, '123456789');
                $user->setPasswordHash($hashedPassword);
                $io->note(sprintf('Password for user %s (%s) has been fixed', $user->getId(), $user->getEmail()));
            }
        }

        $this->entityManager->flush();

        $io->success('All passwords have been fixed.');

        return Command::SUCCESS;
    }
} 