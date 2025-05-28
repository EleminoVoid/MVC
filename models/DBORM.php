<?php
declare(strict_types=1);

namespace mvc\models;

use PDO;
use PDOException;
use Exception;

class DBORM implements iDBFuncs
{
    private object $db;
    private string $sql = '';
    private int $whereInstanceCounter = 0;
    private array $valueBag = [];
    private string $table = '';

public function __construct($host, $user, $password, $dbname) {
    $dsn = "mysql:host=$host;dbname=$dbname;charset=utf8mb4";
    $options = [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
    ];
    try {
        $this->db = new PDO($dsn, $user, $password, $options);
    } catch (PDOException $e) {
        throw new Exception('Database connection failed: ' . $e->getMessage());
    }
}

    public function select($fieldList = null): object
    {
        $this->sql = 'SELECT ';
        if ($fieldList === null) {
            $this->sql .= '*';
        } elseif (is_string($fieldList)) {
            $this->sql .= $fieldList;
        } elseif (is_array($fieldList)) {
            $this->sql .= implode(', ', $fieldList);
        } else {
            throw new Exception('Field list must be null, string, or array');
        }
        $this->sql .= " FROM {$this->table} ";
        return $this;
    }

    public function table($table): object
    {
        $this->table = $table;
        return $this;
    }

    public function from($table): object
    {
        $this->sql .= 'FROM ' . $table;
        return $this;
    }

    public function join(string $table, string $condition): object
    {
        $this->sql .= " INNER JOIN {$table} ON {$condition}";
        return $this;
    }

    public function leftJoin(string $table, string $condition): object
    {
        $this->sql .= " LEFT JOIN {$table} ON {$condition}";
        return $this;
    }

    public function limit(int $limit): object
    {
        $this->sql .= " LIMIT {$limit}";
        return $this;
    }

    public function offset(int $offset): object
    {
        $this->sql .= " OFFSET {$offset}";
        return $this;
    }

    public function groupBy(string $field): object
    {
        $this->sql .= " GROUP BY {$field}";
        return $this;
    }

    public function get(): array
    {
        $this->sql .= ';';
        $dbStatement = $this->db->prepare($this->sql);
        if ($this->valueBag) {
            $dbStatement->execute($this->valueBag);
            $this->valueBag = [];
        } else {
            $dbStatement->execute();
        }
        $recordset = $dbStatement->fetchAll(PDO::FETCH_ASSOC) ?: [];
        $this->whereInstanceCounter = 0;
        $this->sql = '';
        return $recordset;
    }

    public function getAll(): array
    {
        return $this->_runGetQuery(__METHOD__);
    }

    private function _runGetQuery($getMethod): array
    {
        $this->sql .= ';';
        $dbStatement = $this->db->prepare($this->sql);

        if ($this->valueBag) {
            $dbStatement->execute($this->valueBag);
            $this->valueBag = [];
        } else {
            $dbStatement->execute();
        }

        $recordset = [];
        if ($getMethod === 'App\\Models\\DBORM::get' || $getMethod === __METHOD__) {
            $result = $dbStatement->fetch(PDO::FETCH_BOTH);
            $recordset = $result !== false ? [$result] : [];
        } elseif ($getMethod === 'App\\Models\\DBORM::getAll') {
            $recordset = $dbStatement->fetchAll(PDO::FETCH_BOTH) ?: [];
        }

        $this->whereInstanceCounter = 0;
        $this->sql = '';

        return $recordset;
    }

    public function where(): object
    {
        if (func_num_args() <= 1) {
            throw new Exception('Expecting 2 to 3 parameters. Less than 2 parameters encountered.');
        }

        if (func_num_args() == 2) {
            $field = func_get_arg(0);
            $operator = '=';
            $value = func_get_arg(1);
        } else {
            $field = func_get_arg(0);
            $operator = func_get_arg(1);
            $value = func_get_arg(2);
        }

        if ($value === null) {
            error_log("Warning: null value passed to where clause for field: " . $field);
            $value = '';
        }

        $this->_runWhere($field, $operator, $value, __METHOD__);
        return $this;
    }

    public function whereOr(): object
    {
        if (func_num_args() <= 1) {
            throw new Exception('Expecting 2 to 3 parameters. Less than 2 parameters encountered.');
        }

        if (func_num_args() == 2) {
            $field = func_get_arg(0);
            $operator = '=';
            $value = func_get_arg(1);
        } else {
            $field = func_get_arg(0);
            $operator = func_get_arg(1);
            $value = func_get_arg(2);
        }

        $this->_runWhere($field, $operator, $value, __METHOD__);
        return $this;
    }

    private function _runWhere($field, $operator, $value, $whereMethod): void
    {
        if ($this->whereInstanceCounter > 0) {
            if ($whereMethod === 'DBORM::where' || $whereMethod === __METHOD__) {
                $this->sql .= ' AND ';
            } elseif ($whereMethod === 'DBORM::whereOr') {
                $this->sql .= ' OR ';
            }
        } else {
            $this->sql .= ' WHERE ';
        }

        $this->sql .= $field . ' ' . $operator . ' ?';
        $this->valueBag[] = $value;
        $this->whereInstanceCounter++;
    }

    public function showQuery(): string
    {
        $query = $this->sql;
        foreach ($this->valueBag as $value) {
            $pos = strpos($query, '?');
            if ($pos !== false) {
                $query = substr_replace($query, "'{$value}'", $pos, 1);
            }
        }
        return $query;
    }

    public function showValueBag(): array
    {
        return $this->valueBag;
    }

    public function insert(array $values): int
    {
        $columns = implode(', ', array_keys($values));
        $placeholders = str_repeat('?,', count($values) - 1) . '?';
        $this->sql = "INSERT INTO {$this->table} ($columns) VALUES ($placeholders)";
        $this->valueBag = array_values($values);
        return $this->_executeQuery();
    }

    private function _executeQuery(): int
    {
        try {
            $dbStatement = $this->db->prepare($this->sql);
            $dbStatement->execute($this->valueBag);

            $operation = strtoupper(strtok(trim($this->sql), ' '));
            $this->valueBag = [];
            $this->sql = '';

            if ($operation === 'INSERT') {
                return (int)$this->db->lastInsertId();
            } else {
                return $dbStatement->rowCount();
            }
        } catch (PDOException $e) {
            error_log($e->getMessage());
            return 0;
        }
    }

    public function update(array $values): int
    {
        $where = $this->sql;
        $this->sql = '';
        $this->sql = "UPDATE {$this->table} SET ";

        $setStatements = [];
        $valueBagTemp = [];

        foreach ($values as $key => $value) {
            if ($key === '_method') {
                continue; 
            }
            $setStatements[] = "{$key} = ?";
            $valueBagTemp[] = $value;
        }

        $this->sql .= implode(', ', $setStatements);
        $this->sql .= $where;

        $this->valueBag = array_merge($valueBagTemp, $this->valueBag);

        return $this->_executeQuery();
    }
    public function delete(): int
    {
        $where = trim($this->sql);
        $this->sql = '';
        $this->sql = "DELETE FROM {$this->table}";
        if ($where !== '') {
            if (stripos($where, 'where') !== 0) {
                $this->sql .= " WHERE {$where}";
            } else {
                $this->sql .= " {$where}";
            }
        }
        return $this->_executeQuery();
    }

    public function query(string $sql, array $params = []): array
    {
        try {
            $stmt = $this->db->prepare($sql);
            $stmt->execute($params);
            $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
            return $result ?: [];
        } catch (PDOException $e) {
            throw new Exception('Query failed: ' . $e->getMessage());
        }
    }

    public function beginTransaction(): bool
    {
        return $this->db->beginTransaction();
    }

    public function commit(): bool
    {
        return $this->db->commit();
    }

    public function rollback(): bool
    {
        return $this->db->rollBack();
    }

    public function first(): ?array
    {
        $this->sql .= " LIMIT 1";
        $dbStatement = $this->db->prepare($this->sql . ';');
        if ($this->valueBag) {
            $dbStatement->execute($this->valueBag);
            $this->valueBag = [];
        } else {
            $dbStatement->execute();
        }
        $result = $dbStatement->fetch(\PDO::FETCH_ASSOC);
        $this->whereInstanceCounter = 0;
        $this->sql = '';
        return $result ?: null;
    }
    public function selectRaw(string $raw): object
    {
        $this->sql = "SELECT $raw FROM {$this->table}";
        return $this;
    }

    public function count(): int
{
    $this->sql = "SELECT COUNT(*) as count FROM {$this->table}";
    $dbStatement = $this->db->prepare($this->sql);
    
    if ($this->valueBag) {
        $dbStatement->execute($this->valueBag);
    } else {
        $dbStatement->execute();
    }
    
    $result = $dbStatement->fetch(PDO::FETCH_ASSOC);
    $this->whereInstanceCounter = 0;
    $this->sql = '';
    $this->valueBag = [];
    
    return (int)($result['count'] ?? 0);
}
}