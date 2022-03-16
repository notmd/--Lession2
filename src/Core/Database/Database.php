<?php

namespace App\Core\Database;

use PDO;
use PDOStatement;

class Database
{
    private PDO $pdo;

    public function __construct(PDO $pdo)
    {
        $this->pdo = $pdo;
    }

    public function fetchAll(string $table)
    {
        $statement = $this->pdo->prepare("select * from `{$table}`");

        $statement->execute();

        return $statement->fetchAll(PDO::FETCH_ASSOC);
    }

    public function insert(string $table, array $parameters): string | false
    {
        $sql = sprintf(
            'insert into %s (%s) values (%s)',
            $table,
            implode(', ', array_keys($parameters)),
            ':' . implode(', :', array_keys($parameters))
        );

        $this->prepareAndExecute($sql, $parameters);

        return $this->pdo->lastInsertId();
    }

    public function exec(string $sql)
    {
        $this->pdo->exec($sql);
    }

    public function get(string $query, array $bindings = []): array
    {
        return $this->prepareAndExecute($query, $bindings)->fetchAll(PDO::FETCH_ASSOC);
    }

    public function first(string $query, array $bindings = []): array
    {
        return $this->prepareAndExecute($query, $bindings)->fetch(PDO::FETCH_ASSOC);
    }

    public function statement(string $query, array $bindings = [])
    {
        $this->prepareAndExecute($query, $bindings);
    }

    private function prepareAndExecute(string $query, array $bindings = [])
    {
        $statement = $this->pdo->prepare($query);
        $this->bindValues($statement, $bindings);
        $statement->execute();

        return $statement;
    }

    private function bindValues(PDOStatement $statement, $bindings)
    {
        foreach ($bindings as $key => $value) {
            $statement->bindValue(
                is_string($key) ? $key : $key + 1,
                $value,
                is_int($value) ? PDO::PARAM_INT : PDO::PARAM_STR
            );
        }
    }
}
