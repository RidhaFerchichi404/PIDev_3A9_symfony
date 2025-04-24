<?php

namespace App\Service;

class StaticSession
{
    private string $role;
    private const ROLE_ADMIN = 'ROLE_ADMIN';
    private const ROLE_CLIENT = 'ROLE_CLIENT';

    public function __construct()
    {
        // Default role for testing
        $this->role = self::ROLE_CLIENT;
    }

    public function getRole(): string
    {
        return $this->role;
    }

    public function setRole(string $role): void
    {
        $this->role = $role;
    }

    public function isAdmin(): bool
    {
        return $this->role === self::ROLE_ADMIN;
    }

    public function isClient(): bool
    {
        return $this->role === self::ROLE_CLIENT;
    }

    public function hasRole(string $role): bool
    {
        return $this->role === $role;
    }
} 