<?php

namespace App\Command;

use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

#[AsCommand(
    name: 'app:create-admin',
    description: 'Creates a new admin user',
)]
class CreateAdminUserCommand extends Command
{
    private EntityManagerInterface $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        parent::__construct();
        $this->entityManager = $entityManager;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);

        // Check if admin already exists
        $existingAdmin = $this->entityManager->getRepository(User::class)->findOneBy(['email' => 'admin@sportify.com']);
        
        if ($existingAdmin) {
            $io->warning('Admin user already exists!');
            return Command::SUCCESS;
        }
        
        // Create new admin user
        $user = new User();
        $user->setFirstname('Admin');
        $user->setLastname('Sportify');
        $user->setEmail('admin@sportify.com');
        // Password is '123123' - already hashed using bcrypt
        $user->setPasswordHash('$2y$10$.Aazg06KuECQzSPSWkodU.i2rA9mBJCcjnNz9d37X8nLq1ccNO9vq');
        $user->setRole('ROLE_ADMIN');
        $user->setIsactive(true);
        $user->setPhonenumber('12345678');
        $user->setLocation('Sportify HQ');
        $user->setAge(30);

        $this->entityManager->persist($user);
        $this->entityManager->flush();

        $io->success('Admin user created successfully!');
        $io->text([
            'Email: admin@sportify.com',
            'Password: 123123',
            'Role: ROLE_ADMIN'
        ]);

        return Command::SUCCESS;
    }
} 