<?php

namespace Framework\DB;

class DB
{
    protected \PDO $connection;

    public function __construct(\PDO $connection)
    {
        $this->connection = $connection;
    }

    public function getPdo(): \PDO
    {
        return $this->connection;
    }

    public function getRow(string $query, array $args = []): array|bool
    {
        $stmt = $this->sql($query, $args);
        $stmt->setFetchMode(\PDO::FETCH_ASSOC);
        return $stmt->fetch();
    }

    public function getRows(string $query, array $args = []): array|bool
    {
        $stmt = $this->sql($query, $args);
        $stmt->setFetchMode(\PDO::FETCH_ASSOC);
        return $stmt->fetchAll();
    }

    public function getObject(string $className, string $query, array $args = []): bool|object
    {
        return $this->sql($query, $args)->fetchObject($className);
    }

    public function getObjects(string $className, string $query, array $args = []): array
    {
        $stmt = $this->sql($query, $args);
        $stmt->setFetchMode(\PDO::FETCH_CLASS, $className);
        return $stmt->fetchAll();
    }

    public function insert(string $query, array $args = [], string $name = null): int
    {
        $this->sql($query, $args);
        return (int) $this->connection->lastInsertId($name);
    }

    public function update(string $query, array $args = []): int
    {
        return $this->sql($query, $args)->rowCount();
    }

    public function delete(string $query, array $args = []): int
    {
        return $this->sql($query, $args)->rowCount();
    }

    private function sql(string $query, array $args): bool|\PDOStatement
    {
        $stmt = $this->connection->prepare($query);
        $stmt->execute($args);
        return $stmt;
    }
}
