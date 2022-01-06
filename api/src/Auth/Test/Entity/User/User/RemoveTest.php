<?php

declare(strict_types=1);

namespace App\Auth\Test\Entity\User\User;

use App\Auth\Test\UserBuilder;
use PHPUnit\Framework\TestCase;

/**
 * @internal
 */
final class RemoveTest extends TestCase
{
    /**
     * @doesNotPerformAssertions
     */
    public function testSuccess(): void
    {
        $user = (new UserBuilder())
            ->build();

        $user->remove();
    }

    public function testActive(): void
    {
        $user = (new UserBuilder())
            ->active()
            ->build();

        $this->expectExceptionMessage('Unable to remove active user.');

        $user->remove();
    }
}
