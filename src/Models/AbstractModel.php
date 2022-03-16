<?php

namespace App\Models;

use App\Core\Container;
use App\Core\Database\Database;
use App\Core\Database\Paginator;
use App\Core\Request;
use Exception;

abstract class AbstractModel
{
    protected Database $database;

    protected array $attributes = [];
    protected string $primaryKey = 'id';

    public function __construct()
    {
        $this->database = Container::getInstance()->get(Database::class);
    }

    abstract protected function getTableName(): string;

    public static function make(): self
    {
        return new static;
    }

    public function save(): void
    {
        if (!array_key_exists($this->primaryKey, $this->attributes)) {
            throw new Exception('Cannot save when primary key is not exists.');
        }

        $set = implode(',', array_map(fn ($column) => "`$column`= ?", array_keys($this->attributes)));

        $primaryValue = $this->attributes[$this->primaryKey];

        $this->database->statement("UPDATE {$this->getTableName()} SET {$set} WHERE {$this->primaryKey} = $primaryValue", array_values($this->attributes));
    }

    public function find($id)
    {
        $res =  $this->database->get("SELECT * FROM `{$this->getTableName()}` where {$this->primaryKey} = ? LIMIT 1", [
            $id
        ]);

        if (empty($res)) {
            return null;
        }

        return (new static())->fill($res[0]);
    }

    public function all(): array
    {
        return $this->mapRecord(
            $this->database->fetchAll($this->getTableName())
        );
    }

    public function delete()
    {
        $primaryValue = $this->attributes[$this->primaryKey];

        $this->database->statement("DELETE FROM {$this->getTableName()} WHERE {$this->primaryKey} = $primaryValue");
        $this->attributes = [];
    }

    public function create(array $data)
    {
        $id = $this->database->insert($this->getTableName(), $data);

        if ($id === false) {
            return null;
        }

        return $this->fill(['id' => $id, ...$data]);
    }

    public function query(string $query, array $bindings = [])
    {
        return $this->mapRecord($this->database->get($query, $bindings));
    }

    public function fill(array $data): self
    {
        $this->attributes = [...$this->attributes, ...$data];

        return $this;
    }

    public function paginate(string $sql, array $bindings = [], $perPage = 10): Paginator
    {
        $offset = max((Request::get('page') - 1) * $perPage, 0);
        $items = $this->query("$sql LIMIT ? OFFSET ?", [...$bindings, $perPage, $offset]);

        $total = $this->database->first("SELECT COUNT(*) as aggregate FROM ($sql) FINAL", $bindings);

        return new Paginator($items, $total['aggregate'], $perPage);
    }

    public function __get($name)
    {
        if (array_key_exists($name, $this->attributes)) {
            return $this->attributes[$name];
        }

        return null;
    }

    public function __set($name, $value)
    {
        $this->attributes[$name] = $value;
    }

    private function mapRecord(array $records)
    {
        return array_map(
            fn ($data) => static::make()->fill($data),
            $records
        );
    }
}
