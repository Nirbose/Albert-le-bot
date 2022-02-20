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

    /**
     * select method
     * 
     * @param string $name
     * @param array $where
     * @return array
     */
    public function select(string $name, array $where): array
    {
        $query = "SELECT * FROM $name WHERE ";

        foreach ($where as $key => $value) {
            $query .= "$key = :$key AND ";
        }

        $query = rtrim($query, " AND ");

        $stmt = $this::$db->prepare($query);

        foreach ($where as $key => $value) {
            $stmt->bindValue(":$key", $value);
        }

        $stmt = $stmt->execute();

        $data = [];

        while ($row = $stmt->fetchArray(SQLITE3_ASSOC)) {
            $data[] = $row;
        }

        return $data;
    }

    /**
     * update table method SQLite3
     * 
     * @param string $name
     * @param array $values
     * @param array $where
     * @return void
     * @throws \Exception
     * @throws \SQLite3Exception
     */
    public function update(string $name, array $values, array $where): void
    {
        $query = "UPDATE $name SET ";

        foreach ($values as $key => $value) {
            $query .= "$key = $value, ";
        }

        $query = rtrim($query, ", ");

        $query .= " WHERE ";

        foreach ($where as $key => $value) {
            $query .= "$key = $value AND ";
        }

        $query = rtrim($query, " AND ");

        $stmt = $this::$db->prepare($query);

        $stmt->execute();

    }

}
