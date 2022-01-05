<?php

declare(strict_types=1);

namespace App\Auth\Test\Entity\User\User\ChangeEmail;

use App\Auth\Entity\User\Email;
use App\Auth\Entity\User\Token;
use App\Auth\Test\UserBuilder;
use DateTimeImmutable;
use PHPUnit\Framework\TestCase;
use Ramsey\Uuid\Uuid;

/**
 * @covers \App\Auth\Entity\User\User
 *
 * @internal
 */
final class ConfirmTest extends TestCase
{
    public function testSuccess(): void
    {
        $user = (new UserBuilder())->active()->build();

        $now = new DateTimeImmutable();
        $token = new Token(Uuid::uuid4()->toString(), $now->modify('+1 day'));

        $user->requestEmailChange($token, $now, $new = new Email('new-email@app.test'));

        self::assertNotNull($user->getNewEmail());
        self::assertNotNull($user->getNewEmailToken());

        $user->confirmEmailChange($token->getValue(), $now);

        self::assertEquals($new, $user->getEmail());
    }

    public function testInvalidToken(): void
    {
        $user = (new UserBuilder())->active()->build();

        $now = new DateTimeImmutable();
        $token = new Token(Uuid::uuid4()->toString(), $now->modify('+1 day'));

        $user->requestEmailChange($token, $now, new Email('new-email@app.test'));

        $this->expectExceptionMessage('Token is invalid.');
        $user->confirmEmailChange('invalid', $now);
    }

    public function testExpiredToken(): void
    {
        $user = (new UserBuilder())->active()->build();

        $now = new DateTimeImmutable();
        $token = new Token(Uuid::uuid4()->toString(), $now);

        $user->requestEmailChange($token, $now, new Email('new-email@app.test'));

        $this->expectExceptionMessage('Token has expired.');
        $user->confirmEmailChange($token->getValue(), $now->modify('+1 day'));
    }

    public function testNotRequested(): void
    {
        $user = (new UserBuilder())->active()->build();

        $now = new DateTimeImmutable();
        $token = new Token(Uuid::uuid4()->toString(), $now->modify('+1 day'));

        $this->expectExceptionMessage('Email change was not requested.');
        $user->confirmEmailChange($token->getValue(), $now);
    }
}
