<?php

declare(strict_types=1);

use function App\env;

return [
    'config' => [
        'env' => env('APP_ENV') ?: 'prod',
        'debug' => (bool)env('APP_DEBUG'),
    ],
];
