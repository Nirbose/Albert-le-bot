<?php

namespace App\Database;

class Table {
    private \SQLite3 $db;
    private $table = null;
    private $columns = [];
    private $data = [];
    private $where = [];

    public function __construct(\SQLite3 $db, string $table, array $columns = [])
    {
        $this->db = $db;
        $this->table = $table;
        $this->columns = $columns;
    }

    /**
     * Insert data into table
     * 
     * @param array $data
     * @return bool
     */
    public function insert(array $data): bool
    {
        $columns = array_keys($data);
        $values = array_values($data);

        $query = "INSERT INTO {$this->table} (" . implode(", ", $columns) . ") VALUES (" . implode(", ", array_fill(0, count($values), "?")) . ");";

        $stmt = $this->db->prepare($query);

        foreach ($values as $i => $value) {
            $stmt->bindValue($i + 1, $value);
        }

        return (bool)$stmt->execute();
    }

    /**
     * Update data in table, if exists $this->data use $this->data
     * 
     * @param array $data
     * @param array $where
     * @return bool
     */
    public function update(array $data, ?array $where = []): bool
    {
        $columns = array_keys($data);
        $values = array_values($data);

        $query = "UPDATE {$this->table} SET " . implode(", ", array_map(function ($column) {
            return "{$column} = ?";
        }, $columns));

        if (count($this->where)) {
            $query .= " WHERE " . implode(" AND ", array_map(function ($column) {
                return "{$column} = ?";
            }, array_keys($this->where)));
        } else {
            $query .= " WHERE " . implode(" AND ", array_map(function ($column) {
                return "{$column} = ?";
            }, array_keys($where)));
        }

        $stmt = $this->db->prepare($query);

        foreach ($values as $i => $value) {
            $stmt->bindValue($i + 1, $value);
        }

        foreach ($where as $i => $value) {
            $stmt->bindValue($i + count($values) + 1, $value);
        }

        return (bool)$stmt->execute();
    }

    /**
     * Where data in table with given conditions
     * 
     * @param array $where
     * @return self
     */
    public function where(array $where): self
    {
        $this->data = [];
        $this->where = $where;

        $query = "SELECT * FROM {$this->table} WHERE " . implode(" AND ", array_map(function ($column) {
            return "{$column} = ?";
        }, array_keys($where)));

        $stmt = $this->db->prepare($query);

        foreach ($where as $i => $value) {
            $stmt->bindValue($i + 1, $value);
        }

        $result = $stmt->execute();

        while ($row = $result->fetchArray(SQLITE3_ASSOC)) {
            $this->data[] = $row;
        }

        return $this;
    }

    /**
     * Get data from table
     * 
     * @return array
     */
    public function get(): array
    {
        return $this->data;
    }

    /**
     * OrderBy method for table
     * 
     * @param string $column
     * @param string $order
     * @return self
     */
    public function orderBy(string $column, string $order = "ASC"): self
    {
        $this->data = [];

        $query = "SELECT * FROM {$this->table} ORDER BY {$column} {$order}";

        $result = $this->db->query($query);

        while ($row = $result->fetchArray(SQLITE3_ASSOC)) {
            $this->data[] = $row;
        }

        return $this;
    }

    /**
     * Limit method for table
     * 
     * @param int $limit
     * @return self
     */
    public function limit(int $limit): self
    {
        $this->data = [];

        $query = "SELECT * FROM {$this->table} LIMIT {$limit}";

        $result = $this->db->query($query);

        while ($row = $result->fetchArray(SQLITE3_ASSOC)) {
            $this->data[] = $row;
        }

        return $this;
    }

    /**
     * Get first row from table
     * 
     * @return array
     */
    public function first(): array
    {
        return $this->data[0] ?? [];
    }

    /**
     * Get last row from table
     * 
     * @return array
     */
    public function last(): array
    {
        return $this->data[count($this->data) - 1] ?? [];
    }

    /**
     * Get count of rows from table
     * 
     * @return int
     */
    public function count(): int
    {
        return count($this->data);
    }

    

    

}
