<?php

declare(strict_types=1);

use DI\ContainerBuilder;

$builder = new ContainerBuilder();
$builder->addDefinitions(__DIR__ . '/definitions.php');
return $builder->build();
