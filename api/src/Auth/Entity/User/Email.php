<?php

declare(strict_types=1);

namespace App\Auth\Entity\User;

use InvalidArgumentException;

final class Email
{
    private string $value;

    public function __construct(string $value)
    {
        if (empty($value)) {
            throw new InvalidArgumentException('Empty email.');
        }

        if (!filter_var($value, FILTER_VALIDATE_EMAIL)) {
            throw new InvalidArgumentException('Incorrect email');
        }

        $this->value = mb_strtolower($value);
    }

    public function getValue(): string
    {
        return $this->value;
    }
}
