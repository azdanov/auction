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
final class RequestTest extends TestCase
{
    public function testSuccess(): void
    {
        $user = (new UserBuilder())
            ->withEmail($old = new Email('old-email@app.test'))
            ->active()
            ->build();

        $now = new DateTimeImmutable();
        $token = $this->createToken($now->modify('+1 day'));

        $user->requestEmailChange($token, $now, $new = new Email('new-email@app.test'));

        self::assertEquals($token, $user->getNewEmailToken());
        self::assertEquals($old, $user->getEmail());
        self::assertEquals($new, $user->getNewEmail());
    }

    public function testSame(): void
    {
        $user = (new UserBuilder())
            ->withEmail($old = new Email('old-email@app.test'))
            ->active()
            ->build();

        $now = new DateTimeImmutable();
        $token = $this->createToken($now->modify('+1 day'));

        $this->expectExceptionMessage('Current email is equal to the provided email.');
        $user->requestEmailChange($token, $now, $old);
    }

    public function testAlready(): void
    {
        $user = (new UserBuilder())->active()->build();

        $now = new DateTimeImmutable();
        $token = $this->createToken($now->modify('+1 day'));

        $user->requestEmailChange($token, $now, $email = new Email('new-email@app.test'));

        $this->expectExceptionMessage('Changing was already requested.');
        $user->requestEmailChange($token, $now, $email);
    }

    public function testExpired(): void
    {
        $user = (new UserBuilder())->active()->build();

        $now = new DateTimeImmutable();
        $token = $this->createToken($now->modify('+1 hour'));
        $user->requestEmailChange($token, $now, new Email('temp-email@app.test'));

        $newDate = $now->modify('+2 hours');
        $newToken = $this->createToken($newDate->modify('+1 hour'));
        $user->requestEmailChange($newToken, $newDate, $newEmail = new Email('new-email@app.test'));

        self::assertEquals($newToken, $user->getNewEmailToken());
        self::assertEquals($newEmail, $user->getNewEmail());
    }

    public function testNotActive(): void
    {
        $now = new DateTimeImmutable();
        $token = $this->createToken($now->modify('+1 day'));

        $user = (new UserBuilder())->build();

        $this->expectExceptionMessage('User is not active.');
        $user->requestEmailChange($token, $now, new Email('temp-email@app.test'));
    }

    private function createToken(DateTimeImmutable $date): Token
    {
        return new Token(
            Uuid::uuid4()->toString(),
            $date
        );
    }
}
