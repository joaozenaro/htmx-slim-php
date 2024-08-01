<?php

function getConnection()
{
    $pdo = new PDO('sqlite:' . __DIR__ . '/database.db');

    $sql = <<<SQL
        CREATE TABLE IF NOT EXISTS contacts (
            id INTEGER PRIMARY KEY AUTOINCREMENT,
            name TEXT NOT NULL,
            email TEXT NOT NULL
        );
    SQL;

    $pdo->exec($sql);

    return $pdo;
}

function getAllContacts()
{
    $conn = getConnection();
    $stmt = $conn->query('SELECT * FROM contacts');
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

function getContactById($id)
{
    $conn = getConnection();
    $stmt = $conn->prepare('SELECT * FROM contacts WHERE id = ?');
    $stmt->execute([$id]);
    return $stmt->fetch(PDO::FETCH_ASSOC);
}

function addContact($name, $email)
{
    $conn = getConnection();
    $stmt = $conn->prepare('INSERT INTO contacts (name, email) VALUES (:name, :email)');
    $stmt->execute(['name' => $name, 'email' => $email]);
    return $conn->lastInsertId();
}

function updateContact($id, $name, $email)
{
    $conn = getConnection();
    $stmt = $conn->prepare('UPDATE contacts SET name = :name, email = :email WHERE id = :id');
    $stmt->execute(['name' => $name, 'email' => $email, 'id' => $id]);
}

function deleteContact($id)
{
    $conn = getConnection();
    $stmt = $conn->prepare('DELETE FROM contacts WHERE id = ?');
    $stmt->execute([$id]);
}

function getRowHtml($contact, $append = false)
{
    if ($append) {
        $html = '<tbody hx-swap-oob="beforeend:#contacts-body">';
    }

    $html ??= "";

    $html .= <<<HTML
        <tr>
            <td>{$contact['name']}</td>
            <td>{$contact['email']}</td>
            <td>
                <button class="uk-button uk-button-default" hx-get="/contact/{$contact['id']}/edit" hx-on="click: checkEditing(event)">Edit</button>
                <button class="uk-button uk-button-default" hx-delete="/contact/{$contact['id']}" hx-confirm="Are you sure?">Delete</button>
            </td>
        </tr>
    HTML;

    if ($append) {
        $html .= "</tbody>";
    }

    return $html;
}

function getEditRowHtml($contact)
{
    return <<<HTML
        <tr hx-trigger="cancel" class="editing" hx-get="/contact/{$contact['id']}">
            <td>
                <input class="uk-input" type="text" value="{$contact['name']}" name="name">
            </td>
            <td>
                <input class="uk-input" type="text" value="{$contact['email']}" name="email">
            </td>
            <td>
                <button class="uk-button uk-button-default" hx-get="/contact/{$contact['id']}">Cancel</button>
                <button class="uk-button uk-button-default" hx-put="/contact/{$contact['id']}" hx-include="closest tr">Save</button>
            </td>
        </tr>
    HTML;
}
