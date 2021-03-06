<?php

declare(strict_types=1);

use Doctrine\Migrations;
use Doctrine\ORM;

return [
    'config' => [
        'console' => [
            'commands' => [
                ORM\Tools\Console\Command\ValidateSchemaCommand::class,

                Migrations\Tools\Console\Command\ExecuteCommand::class,
                Migrations\Tools\Console\Command\MigrateCommand::class,
                Migrations\Tools\Console\Command\LatestCommand::class,
                Migrations\Tools\Console\Command\ListCommand::class,
                Migrations\Tools\Console\Command\StatusCommand::class,
                Migrations\Tools\Console\Command\UpToDateCommand::class,
            ],
        ],
    ],
];
