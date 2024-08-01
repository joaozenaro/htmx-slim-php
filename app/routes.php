<?php

use Slim\Psr7\Request;
use Slim\Psr7\Response;

require_once __DIR__ . '/helpers.php';

$app->get('/', function (Request $request, Response $response) {
    $html = file_get_contents('../index.html');
    $response->getBody()->write($html);
    return $response->withHeader('Content-Type', 'text/html');
});

$app->get('/contacts', function (Request $request, Response $response) {
    $contacts = getAllContacts();
    $response->getBody()->write(json_encode($contacts));
    return $response->withHeader('Content-Type', 'application/json');
});

$app->post('/contact', function (Request $request, Response $response) {
    $data = $request->getParsedBody();

    ['name' => $name, 'email' => $email] = $data;

    if (!$name || !$email) {
        return $response->withStatus(422);
    }

    $id = addContact($name, $email);

    $contact = getContactById($id);
    $rowHtml = getRowHtml($contact, true);
    $response->getBody()->write($rowHtml);
    return $response->withHeader('Content-Type', 'text/html');
});

$app->get('/contact/{id}', function (Request $request, Response $response, $args) {
    $contact = getContactById($args['id']);
    $rowHtml = getRowHtml($contact);
    $response->getBody()->write($rowHtml);
    return $response->withHeader('Content-Type', 'text/html');
});

$app->get('/contact/{id}/edit', function (Request $request, Response $response, $args) {
    $contact = getContactById($args['id']);
    $editRowHtml = getEditRowHtml($contact);
    $response->getBody()->write($editRowHtml);
    return $response->withHeader('Content-Type', 'text/html');
});

$app->put('/contact/{id}', function (Request $request, Response $response, $args) {
    parse_str($request->getBody()->getContents(), $data);
    ['name' => $name, 'email' => $email] = $data;

    updateContact($args['id'], $name, $email);
    $contact = getContactById($args['id']);

    $response->getBody()->write(getRowHtml($contact));
    return $response->withHeader('Content-Type', 'text/html');
});

$app->delete('/contact/{id}', function (Request $request, Response $response, $args) {
    deleteContact($args['id']);
    return $response->withStatus(200);
});
