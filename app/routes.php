<?php

use Slim\Psr7\Request;
use Slim\Psr7\Response;

$app->get('/', function (Request $request, Response $response) {
    $html = file_get_contents('../index.html');
    $response->getBody()->write($html);
    return $response->withHeader('Content-Type', 'text/html');
});
