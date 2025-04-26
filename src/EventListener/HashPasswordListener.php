<?php

namespace App\EventListener;

use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Attribute\AsDoctrineListener;
use Doctrine\ORM\Event\PrePersistEventArgs;
use Doctrine\ORM\Event\PreUpdateEventArgs;
use Doctrine\ORM\Events;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

#[AsDoctrineListener(event: Events::prePersist)]
#[AsDoctrineListener(event: Events::preUpdate)]
class HashPasswordListener
{
    public function __construct(
        private UserPasswordHasherInterface $passwordHasher
    ) {
    }

    public function prePersist(PrePersistEventArgs $args): void
    {
        $entity = $args->getObject();
        if (!$entity instanceof User) {
            return;
        }

        $this->encodePassword($entity);
    }

    public function preUpdate(PreUpdateEventArgs $args): void
    {
        $entity = $args->getObject();
        if (!$entity instanceof User) {
            return;
        }

        // Seulement si le mot de passe a été modifié
        $changeSet = $args->getEntityChangeSet();
        if (isset($changeSet['password_hash'])) {
            $this->encodePassword($entity);
        }
    }

    private function encodePassword(User $entity): void
    {
        $password = $entity->getPasswordHash();
        
        // Vérifie si le mot de passe n'est pas déjà haché
        if (!$password || empty($password) || strlen($password) < 20) {
            return;
        }
        
        // Si le mot de passe ressemble déjà à un hash bcrypt, ne pas le hacher à nouveau
        if (strpos($password, '$2y$') === 0) {
            return;
        }
        
        // Hacher le mot de passe
        $hashedPassword = $this->passwordHasher->hashPassword($entity, $password);
        $entity->setPasswordHash($hashedPassword);
    }
} 