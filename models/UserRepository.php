<?php
namespace mvc\models;

use mvc\classes\DataRepositoryInterface;
use mvc\models\DBORM;

class UserRepository implements DataRepositoryInterface {
    private $db;

    public function __construct(DBORM $db) {
        $this->db = $db;
    }

    public function getAll() {
        return $this->db->table('users')->select(['id', 'name', 'email'])->get();
    }

    public function getById($id) {
        return $this->db->table('users')->select()->where('id', $id)->first();
    }

    public function getByName($name) {
        return $this->db->table('users')->select()->where('name', $name)->first();
    }
    
    public function getByEmail($email) {
        return $this->db->table('users')->select(['id', 'name', 'email', 'password'])->where('email', $email)->first();
    }

    public function create($data) {
        return $this->db->table('users')->insert($data);
    }

    public function update($id, $data) {
        return $this->db->table('users')->where('id', $id)->update($data);
    }

    public function delete($id) {
        return $this->db->table('users')->where('id', $id)->delete();
    }

    public function countAll(): int {
        $result = $this->db->table('users')->select(['COUNT(*) as count'])->get();
        return isset($result[0]['count']) ? (int)$result[0]['count'] : 0;
    }

    public function getPaginated(int $offset, int $limit): array {
        return $this->db->table('users')->select()->limit($limit)->offset($offset)->get();
    }
}