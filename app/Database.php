<?php

namespace App;

use \SQLite3;

class Database {

    protected static SQLite3|null $db = null;

    public static function new(): self
    {
        self::$db = new SQLite3(dirname(__DIR__) . '/data/database.db');

        return new static();
    }

    public function tableCreate(string $name, array $fields): self
    {
        $query = "CREATE TABLE IF NOT EXISTS $name (";

        foreach ($fields as $field => $type) {
            $query .= "$field $type, ";
        }

        $query = rtrim($query, ", ");

        $query .= ");";

        $this::$db->exec($query);

        return $this;
    }

    public function insert(string $name, array $values): self
    {
        $query = "INSERT INTO $name (";

        foreach ($values as $key => $value) {
            $query .= "$key, ";
        }

        $query = rtrim($query, ", ");

        $query .= ") VALUES (";

        foreach ($values as $key => $value) {
            $query .= ":$key, ";
        }

        $query = rtrim($query, ", ");

        $query .= ");";

        $stmt = $this::$db->prepare($query);

        foreach ($values as $key => $value) {
            $stmt->bindValue(":$key", $value);
        }

        $stmt->execute();

        return $this;
    }

    public function get(string $name, array $where): array
    {
        $all = $this->getAll($name);

        $result = [];

        foreach ($all as $data) {
            
            foreach ($where as $key => $value) {
                if ($data[$key] == $value) {
                    $result[] = $data;
                }
            }
        }

        return $result;
    }

    public function getAll(string $name): array
    {
        $query = "SELECT * FROM $name";

        $result = $this::$db->query($query);

        $data = [];

        while ($row = $result->fetchArray(SQLITE3_ASSOC)) {
            $data[] = $row;
        }

        return $data;
    }

}
