<?php

declare(strict_types=1);

namespace App;

use InvalidArgumentException;

function env(string $name, ?string $default = null): string
{
    $value = getenv($name);

    if ($value !== false) {
        return $value;
    }

    $file = getenv($name . '_FILE');

    if ($file !== false) {
        return trim(file_get_contents($file));
    }

    if ($default !== null) {
        return $default;
    }

    throw new InvalidArgumentException('Undefined env ' . $name);
}
