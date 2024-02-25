<?php

namespace App\RMVC\Model;

abstract class Model
{
    private ?\mysqli $connection = null;
    protected string $table;

    public function __construct()
    {
        $this->connection = mysqli_connect(
            'localhost',
            'root',
            '',
            ''
        );
    }

    public function all(int $limit = 50): array
    {
        $items = mysqli_query($this->connection, "SELECT*FROM $this->table WHERE 1 LIMIT $limit");
        $items = mysqli_fetch_all($items, MYSQLI_ASSOC);

        return $items;
    }

    public function find(int $id): array|false|null
    {
        $item = mysqli_query($this->connection, "SELECT*FROM $this->table WHERE id = $id");
        $item = mysqli_fetch_assoc($item);

        return $item;
    }

    public function create(array $data): bool
    {
        $keys = implode(', ', array_keys($data));
        $values = "'" . implode("', '", $data) . "'";

        $is_created = mysqli_query($this->connection, "INSERT INTO $this->table ($keys) VALUES ($values)");

        return $is_created;
    }

    public function update(int $id, array $data): bool
    {
        $updates = [];
        foreach ($data as $key => $value) {
            $updates[] = "$key = '$value'";
        }

        $updates = implode(', ', $updates);

        $is_updated = mysqli_query($this->connection, "UPDATE $this->table SET $updates WHERE id = $id");

        return true;
    }

    public function delete(int $id): bool
    {
        $is_deleted = mysqli_query($this->connection, "DELETE FROM $this->table WHERE id = $id");

        return $is_deleted;
    }

    public function where(string $paramName, mixed $paramValue, string $operand = '='): array|false|null
    {
        $item = mysqli_query($this->connection, "SELECT*FROM $this->table WHERE $paramName $operand '$paramValue'");
        $item = mysqli_fetch_assoc($item);

        return $item;
    }
}
