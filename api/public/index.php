<?php

declare(strict_types=1);

use Psr\Container\ContainerInterface;
use Slim\App;

// Set initial status to 500 in case there's a throw to prevent default 200.
http_response_code(500);

require __DIR__ . '/../vendor/autoload.php';

/** @var ContainerInterface $container */
$container = require __DIR__ . '/../config/container.php';

/** @var App $app */
$app = (require __DIR__ . '/../config/app.php')($container);
$app->run();
