<?php

namespace App\Controllers;

use App\Helpers\HtmlHelper;
use App\Models\Contact;
use Slim\Psr7\Request;
use Slim\Psr7\Response;

class ContactController {
    public static function index(Request $request, Response $response): Response {
        $html = file_get_contents(__DIR__ . '/../views/index.html');
        $response->getBody()->write($html);
        return $response->withHeader('Content-Type', 'text/html');
    }

    public static function getAll(Request $request, Response $response): Response {
        $contacts = Contact::getAll();
        $response->getBody()->write(json_encode($contacts));
        return $response->withHeader('Content-Type', 'application/json');
    }

    public static function add(Request $request, Response $response): Response {
        $data = $request->getParsedBody();
        $name = $data['name'] ?? null;
        $email = $data['email'] ?? null;

        if (!$name || !$email) {
            return $response->withStatus(422);
        }

        $id = Contact::add($name, $email);
        $contact = Contact::getById($id);
        $rowHtml = HtmlHelper::getRowHtml($contact, true);
        $response->getBody()->write($rowHtml);
        return $response->withHeader('Content-Type', 'text/html');
    }

    public static function getById(Request $request, Response $response, $args): Response {
        $contact = Contact::getById($args['id']);
        $rowHtml = HtmlHelper::getRowHtml($contact);
        $response->getBody()->write($rowHtml);
        return $response->withHeader('Content-Type', 'text/html');
    }

    public static function edit(Request $request, Response $response, $args): Response {
        $contact = Contact::getById($args['id']);
        $editRowHtml = HtmlHelper::getEditRowHtml($contact);
        $response->getBody()->write($editRowHtml);
        return $response->withHeader('Content-Type', 'text/html');
    }

    public static function update(Request $request, Response $response, $args): Response {
        parse_str($request->getBody()->getContents(), $data);
        $name = $data['name'] ?? null;
        $email = $data['email'] ?? null;

        Contact::update($args['id'], $name, $email);
        $contact = Contact::getById($args['id']);
        $response->getBody()->write(HtmlHelper::getRowHtml($contact));
        return $response->withHeader('Content-Type', 'text/html');
    }

    public static function delete(Request $request, Response $response, $args): Response {
        Contact::delete($args['id']);
        return $response->withStatus(200);
    }
}
