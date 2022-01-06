<?php

declare(strict_types=1);

namespace App\Auth\Entity\User;

use App\Auth\Service\PasswordHasher;
use ArrayObject;
use DateTimeImmutable;
use DomainException;

final class User
{
    private Id $id;
    private DateTimeImmutable $date;
    private Email $email;
    private Status $status;
    private ArrayObject $networks;
    private Role $role;
    private ?Email $newEmail = null;
    private ?string $passwordHash = null;
    private ?Token $joinConfirmToken = null;
    private ?Token $passwordResetToken = null;
    private ?Token $newEmailToken = null;

    private function __construct(Id $id, DateTimeImmutable $date, Email $email, Status $status)
    {
        $this->id = $id;
        $this->date = $date;
        $this->email = $email;
        $this->status = $status;
        $this->role = Role::user();
        $this->networks = new ArrayObject();
    }

    public static function requestJoinByEmail(
        Id $id,
        DateTimeImmutable $date,
        Email $email,
        string $passwordHash,
        Token $token
    ): self {
        $user = new self($id, $date, $email, Status::wait());
        $user->passwordHash = $passwordHash;
        $user->joinConfirmToken = $token;
        return $user;
    }

    public static function joinByNetwork(
        Id $id,
        DateTimeImmutable $date,
        Email $email,
        Network $network
    ): self {
        $user = new self($id, $date, $email, Status::active());
        $user->networks->append($network);
        return $user;
    }

    public function confirmJoin(string $token, DateTimeImmutable $date): void
    {
        if ($this->joinConfirmToken === null) {
            throw new DomainException('Confirmation is not required.');
        }
        $this->joinConfirmToken->validate($token, $date);
        $this->status = Status::active();
        $this->joinConfirmToken = null;
    }

    public function attachNetwork(Network $network): void
    {
        /** @var Network $existing */
        foreach ($this->networks as $existing) {
            if ($existing->isEqualTo($network)) {
                throw new DomainException('Network is already attached.');
            }
        }
        $this->networks->append($network);
    }

    public function requestPasswordReset(Token $token, DateTimeImmutable $date): void
    {
        if (!$this->isActive()) {
            throw new DomainException('User is not active.');
        }
        if ($this->passwordResetToken !== null && !$this->passwordResetToken->isValidBefore($date)) {
            throw new DomainException('Password reset was already requested.');
        }
        $this->passwordResetToken = $token;
    }

    public function resetPassword(string $token, DateTimeImmutable $date, string $hash): void
    {
        if ($this->passwordResetToken === null) {
            throw new DomainException('Password reset was not requested.');
        }
        $this->passwordResetToken->validate($token, $date);
        $this->passwordResetToken = null;
        $this->passwordHash = $hash;
    }

    public function changePassword(string $current, string $new, PasswordHasher $hasher): void
    {
        if ($this->passwordHash === null) {
            throw new DomainException('User does not have an old password.');
        }
        if (!$hasher->validate($current, $this->passwordHash)) {
            throw new DomainException('Incorrect current password.');
        }
        $this->passwordHash = $hasher->hash($new);
    }

    public function requestEmailChange(Token $token, DateTimeImmutable $date, Email $email): void
    {
        if (!$this->isActive()) {
            throw new DomainException('User is not active.');
        }
        if ($this->email->isEqualTo($email)) {
            throw new DomainException('Current email is equal to the provided email.');
        }
        if ($this->newEmailToken !== null && !$this->newEmailToken->isValidBefore($date)) {
            throw new DomainException('Changing was already requested.');
        }
        $this->newEmail = $email;
        $this->newEmailToken = $token;
    }

    public function confirmEmailChange(string $token, DateTimeImmutable $date): void
    {
        if ($this->newEmail === null || $this->newEmailToken === null) {
            throw new DomainException('Email change was not requested.');
        }
        $this->newEmailToken->validate($token, $date);
        $this->email = $this->newEmail;
        $this->newEmail = null;
        $this->newEmailToken = null;
    }

    public function changeRole(Role $role): void
    {
        $this->role = $role;
    }

    public function remove(): void
    {
        if (!$this->isWait()) {
            throw new DomainException('Unable to remove active user.');
        }
    }

    public function getId(): Id
    {
        return $this->id;
    }

    public function getDate(): DateTimeImmutable
    {
        return $this->date;
    }

    public function getEmail(): Email
    {
        return $this->email;
    }

    public function getPasswordHash(): ?string
    {
        return $this->passwordHash;
    }

    public function getJoinConfirmToken(): ?Token
    {
        return $this->joinConfirmToken;
    }

    public function isWait(): bool
    {
        return $this->status->isWait();
    }

    public function isActive(): bool
    {
        return $this->status->isActive();
    }

    public function getNetworks(): array
    {
        return $this->networks->getArrayCopy();
    }

    public function getPasswordResetToken(): ?Token
    {
        return $this->passwordResetToken;
    }

    public function getNewEmail(): ?Email
    {
        return $this->newEmail;
    }

    public function getNewEmailToken(): ?Token
    {
        return $this->newEmailToken;
    }

    public function getRole(): Role
    {
        return $this->role;
    }
}
