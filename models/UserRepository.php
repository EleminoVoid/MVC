<?php
namespace mvc\models;

use mvc\classes\DataRepositoryInterface;

class UserRepository implements DataRepositoryInterface {
    private $db;
    public function __construct(DBORM $db) { $this->db = $db; }

    public function getAll() {
        return $this->db->table('users')->select()->get();
    }
    public function getById($id) {
        return $this->db->table('users')->select()->where('id', $id)->first();
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
    public function getByEmail($email) {
        return $this->db->table('users')->select()->where('email', $email)->first();
    }
    public function getByName($name) {
        return $this->db->table('users')->select()->where('name', $name)->first();
    }
    public function countAll() {
        $result = $this->db->table('users')->selectRaw('COUNT(*) as cnt')->first();
        return isset($result['cnt']) ? (int)$result['cnt'] : 0;
    }
}