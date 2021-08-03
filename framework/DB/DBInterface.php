<?php

namespace Framework\DB;

interface DBInterface
{
    public function getPDO(): \PDO;
    public function getRow(string $query, array $args = []): array|bool;
    public function getRows(string $query, array $args = []): array|bool;
    public function getObject(string $className, string $query, array $args = []): bool|object;
    public function getObjects(string $className, string $query, array $args = []): array;
    public function insert(string $query, array $args = [], string $name = null): int;
    public function update(string $query, array $args = []): int;
    public function delete(string $query, array $args = []): int;
}
