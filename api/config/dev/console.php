<?php

declare(strict_types=1);

use Doctrine\Migrations;
use Doctrine\ORM;

return [
    'config' => [
        'console' => [
            'commands' => [
                ORM\Tools\Console\Command\SchemaTool\DropCommand::class,

                Migrations\Tools\Console\Command\DiffCommand::class,
                Migrations\Tools\Console\Command\GenerateCommand::class,
            ],
            'fixture_paths' => [
                __DIR__ . '/../../src/Auth/Fixture',
            ],
        ],
    ],
];
