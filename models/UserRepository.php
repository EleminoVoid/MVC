<?php
namespace mvc\models;

use mvc\classes\DataRepositoryInterface;

class UserRepository implements DataRepositoryInterface {
    private $db;

    public function __construct(Database $db) {
        $this->db = $db;
    }

    public function getAll() {
        return $this->db->query("SELECT id, name, email FROM users");
    }

    public function getById($id) {
        return $this->db->query("SELECT * FROM users WHERE id = ?", [$id]);
    }

    public function create($data) {
        $columns = implode(", ", array_keys($data));
        $placeholders = implode(", ", array_fill(0, count($data), "?"));
        $this->db->query(
            "INSERT INTO users ($columns) VALUES ($placeholders)",
            array_values($data)
        );
    }

    public function update($id, $data) {
        $setClause = implode(", ", array_map(fn($key) => "$key = ?", array_keys($data)));
        $this->db->query(
            "UPDATE users SET $setClause WHERE id = ?",
            [...array_values($data), $id]
        );
    }

    public function delete($id) {
        $this->db->query("DELETE FROM users WHERE id = ?", [$id]);
    }

    public function getByEmail($email) {
        $query = "SELECT id, name, email, password FROM users WHERE email = ?";
        $result = $this->db->query($query, [$email]);
        return $result ? $result[0] : null;
    }
}