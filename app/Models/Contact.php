<?php

namespace App\Models;

use PDO;

class Contact {
    public $id;
    public $name;
    public $email;

    public function validate() {
        return isset($this->name) && isset($this->email);
    }

    public static function getConnection() {
        $pdo = new PDO('sqlite:' . __DIR__ . '/../database.db');

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

    public static function getAll() {
        $conn = self::getConnection();
        $stmt = $conn->query('SELECT * FROM contacts');
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public static function getById($id) {
        $conn = self::getConnection();
        $stmt = $conn->prepare('SELECT * FROM contacts WHERE id = ?');
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public static function add($name, $email) {
        $conn = self::getConnection();
        $stmt = $conn->prepare('INSERT INTO contacts (name, email) VALUES (:name, :email)');
        $stmt->execute(['name' => $name, 'email' => $email]);
        return $conn->lastInsertId();
    }

    public static function update($id, $name, $email) {
        $conn = self::getConnection();
        $stmt = $conn->prepare('UPDATE contacts SET name = :name, email = :email WHERE id = :id');
        $stmt->execute(['name' => $name, 'email' => $email, 'id' => $id]);
    }

    public static function delete($id) {
        $conn = self::getConnection();
        $stmt = $conn->prepare('DELETE FROM contacts WHERE id = ?');
        $stmt->execute([$id]);
    }
}
