<?php

namespace DB;

class Table extends Database {

    private string $table;

    private string $request;

    public function __construct(string $table = "") {
        $this->table = $table;
    }

    /**
     * create a new table
     *
     * @param string $name
     * @param array $fields
     * @return self
     */
    public static function create(string $name, array $fields): self
    {
        $query = "CREATE TABLE IF NOT EXISTS " . $name . " (";

        foreach ($fields as $field => $type) {
            $query .= $field . " " . $type . ", ";
        }

        $query = rtrim($query, ", ");

        $query .= ");";

        self::getDB()->exec($query);

        return new static();

    }

    /**
     * Get mathod return result of request $request sql
     * 
     * @return array
     */
    public function get(): array
    {
        $result = self::getDB()->query($this->request);

        $data = [];

        while ($row = $result->fetchArray(SQLITE3_ASSOC)) {
            $data[] = $row;
        }

        return $data;
    }

    /**
     * Select all data from table
     * 
     * @return self
     */
    public function selectAll(): self
    {
        $this->request = "SELECT * FROM " . $this->table;

        return $this;
    }

    /**
     * Select data from table by id
     * 
     * @param int $id
     * @return self
     */
    public function select(int $id): self
    {
        $this->request = "SELECT * FROM " . $this->table . " WHERE id = " . $id;

        return $this;
    }

    /**
     * Select data from table by field
     * 
     * @param string $column
     * @param array $where
     * @return self
     */
    public function where(string $column, array $where): self
    {
        $this->request = "SELECT " . $column . " FROM " . $this->table;

        foreach ($where as $key => $value) {
            $this->request .= " WHERE " . $key . " = " . $value;
        }

        return $this;
    }

    /**
     * Insert data into table
     * 
     * @param array $data
     * @return void
     */
    public function insert(array $data): void
    {
        $query = "INSERT INTO " . $this->table . " (";

        foreach ($data as $key => $value) {
            $query .= $key . ", ";
        }

        $query = rtrim($query, ", ");

        $query .= ") VALUES (";

        foreach ($data as $key => $value) {
            $query .= "'" . $value . "', ";
        }

        $query = rtrim($query, ", ");

        $query .= ");";

        self::getDB()->exec($query);

        return;
    }

    /**
     * Update data in table
     * 
     * @param array $data
     * @return void
     */
    public function update(array $data): void
    {
        $query = "UPDATE " . $this->table . " SET ";

        foreach ($data as $key => $value) {
            $query .= $key . " = '" . $value . "', ";
        }

        $query = rtrim($query, ", ");

        $query .= ";";

        self::getDB()->exec($query);

        return;
    }

    /**
     * Delete data from table
     * 
     * @param int $id
     * @return void
     */
    public function delete(int $id): void
    {
        $query = "DELETE FROM " . $this->table . " WHERE id = " . $id;

        self::getDB()->exec($query);

        return;
    }

}
