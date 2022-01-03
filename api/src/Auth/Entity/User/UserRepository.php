<?php

declare(strict_types=1);

namespace App\Auth\Entity\User;

use DomainException;

interface UserRepository
{
    public function hasByEmail(Email $email): bool;

    public function findByConfirmToken(string $token): ?User;

    public function add(User $user): void;

    public function hasByNetwork(Network $network): bool;

    /** @throws DomainException */
    public function get(Id $param): User;
}
