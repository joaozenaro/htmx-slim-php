<?php

use Slim\Factory\AppFactory;

require __DIR__ . '/../vendor/autoload.php';

$app = AppFactory::create();

require __DIR__ . '/../app/routes.php';

$app->run();