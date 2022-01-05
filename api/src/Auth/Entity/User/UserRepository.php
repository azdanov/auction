<?php

declare(strict_types=1);

namespace App\Auth\Entity\User;

use DomainException;

interface UserRepository
{
    public function add(User $user): void;

    /** @throws DomainException */
    public function get(Id $param): User;

    /** @throws DomainException */
    public function getByEmail(Email $email): User;

    public function findByConfirmToken(string $token): ?User;

    public function findByPasswordResetToken(string $token): ?User;

    public function findByNewEmailToken(string $token): ?User;

    public function hasByEmail(Email $email): bool;

    public function hasByNetwork(Network $network): bool;
}
