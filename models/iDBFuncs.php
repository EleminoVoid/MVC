<?php
declare(strict_types=1);

namespace mvc\models;

interface iDBFuncs {
    public function table($tablename): object;
    public function insert(array $values): int;
    public function get(): array; 
    public function getAll(): array;
    public function select($fieldList = null): object;
    public function from($table): object;
    public function where(): object;
    public function whereOr(): object;
    public function showQuery(): string;
    public function update(array $values): int;
    public function delete(): int;
    public function showValueBag(): array;
    public function join(string $table, string $condition): object;
    public function leftJoin(string $table, string $condition): object;
    public function groupBy(string $field): object;
    public function beginTransaction(): bool;
    public function commit(): bool;
    public function rollback(): bool;
}