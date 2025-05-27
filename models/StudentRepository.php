<?php
namespace mvc\models;

use mvc\classes\DataRepositoryInterface;

class StudentRepository  implements DataRepositoryInterface  {
    private $db;
    public function __construct(DBORM $db) { $this->db = $db; }

    public function getAll() {
        return $this->db->table('students')->select()->get();
    }
    public function getById($id) {
        $result = $this->db->table('students')
            ->select()
            ->where('id', $id)
            ->first();
        
        error_log('Repository getById result: ' . print_r($result, true));
        return $result;
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
      public function getByEmail($email) {
        return $this->db->table('students')->select()->where('email', $email)->first();
    }
    public function getByName($name) {
        return $this->db->table('students')->select()->where('name', $name)->first();
    }
    public function getPaginated($limit, $offset) {
        return $this->db
            ->table('students')
            ->select()
            ->limit($limit)
            ->offset($offset)
            ->get();
    }

    public function countAll() {
        $result = $this->db->table('students')->selectRaw('COUNT(*) as cnt')->first();
        return isset($result['cnt']) ? (int)$result['cnt'] : 0;
    }
}
