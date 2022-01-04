<?php

declare(strict_types=1);

namespace App\Auth\Test\Entity\User\Token;

use App\Auth\Entity\User\Token;
use DateTimeImmutable;
use PHPUnit\Framework\TestCase;
use Ramsey\Uuid\Uuid;

/**
 * @covers \App\Auth\Entity\User\Token::isValidBefore
 *
 * @internal
 */
final class ExpiresTest extends TestCase
{
    public function testNot(): void
    {
        $token = new Token(
            Uuid::uuid4()->toString(),
            $expires = new DateTimeImmutable()
        );

        self::assertFalse($token->isValidBefore($expires->modify('-1 secs')));
        self::assertTrue($token->isValidBefore($expires));
        self::assertTrue($token->isValidBefore($expires->modify('+1 secs')));
    }
}
