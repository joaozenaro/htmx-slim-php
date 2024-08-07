<?php

use App\Controllers\ContactController;
use Slim\Factory\AppFactory;

require __DIR__ . '/../vendor/autoload.php';

$app = AppFactory::create();

$app->get('/', function ($request, $response, $args) {
    $html = file_get_contents(__DIR__ . '/../views/index.html');
    $response->getBody()->write($html);
    return $response->withHeader('Content-Type', 'text/html');
});

$contactController = new ContactController();

$app->get('/contacts', ContactController::class . ':getAll');
$app->post('/contact', ContactController::class . ':add');
$app->get('/contact/{id}', ContactController::class . ':getById');
$app->get('/contact/{id}/edit', ContactController::class . ':edit');
$app->put('/contact/{id}', ContactController::class . ':update');
$app->delete('/contact/{id}', ContactController::class . ':delete');

$app->run();