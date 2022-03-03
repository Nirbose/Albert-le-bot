<?php

namespace App\Database;

// Create Table in SQLite3 database
class Schema {

    private \SQLite3 $db;
    protected static array $columns = [];

    public function __construct(\SQLite3 $db)
    {
        $this::$columns = [];
        $this->db = $db;
    }

    /**
     * Create table
     *
     * @param string $table
     * @param callable $callback
     * @return void
     */
    public function create(string $table, callable $columns)
    {
        $columns(new Blueprint($this->db));

        $this->table = $table;

        $this->db->exec("CREATE TABLE IF NOT EXISTS {$table} (" . implode(", ", $this::$columns) . ");");
    }

    public function drop(string $table)
    {
        $this->db->exec("DROP TABLE {$table}");
    }

}
