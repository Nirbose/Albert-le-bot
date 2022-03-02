<?php

namespace App\Database;

// Create Table in SQLite3 database
class Schema {

    private \SQLite3 $db;
    private $table = null;

    public function __construct(\SQLite3 $db)
    {
        $this->db = $db;
    }

    public function create(string $table, array $columns)
    {
        $this->table = $table;
        $this->columns = $columns;

        $this->db->exec("CREATE TABLE IF NOT EXISTS {$this->table} (
            id INTEGER PRIMARY KEY,
            " . implode(", ", array_keys($this->columns)) . "
        )");

        return $this;
    }

    public function drop(string $table)
    {
        $this->db->exec("DROP TABLE {$table}");
    }

}
