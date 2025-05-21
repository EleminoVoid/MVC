<?php
namespace mvc\models;

use mvc\classes\DataRepositoryInterface;
use mvc\models\DBORM;

class StudentRepository implements DataRepositoryInterface {
    private $db;

    public function __construct(DBORM $db) {
        $this->db = $db;
    }

    public function getAll() {
        return $this->db->table('students')->select()->getAll();
    }

    public function getById($id) {
        return $this->db->table('students')->select()->where('id', $id)->first();
    }

    public function getByEmail($email) {
        return $this->db->table('students')->select()->where('email', $email)->first();
    }

    public function create($data) {
        return $this->db->table('students')->insert($data);
    }

    public function update($id, $data) {
        return $this->db->table('students')->where('id', $id)->update($data);
    }

    public function delete($id) {
        return $this->db->table('students')->where('id', $id)->delete();
    }
}