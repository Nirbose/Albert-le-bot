<?php

namespace App\Database;

/**
 * Class BluePrint - migration class for database
 */
class Blueprint extends Schema {

    /**
     * insert Primary key in $columns
     *
     * @return void
     */
    public function id()
    {
        $this::$columns[] = 'id INTEGER PRIMARY KEY AUTOINCREMENT';
    }

    /**
     * insert vachar in $columns
     *
     * @param string $name
     * @param int $length
     * @return void
     */
    public function varchar(string $name, ?int $length = 255)
    {
        $this::$columns[] = "{$name} VARCHAR({$length})";
    }

    /**
     * insert text in $columns
     *
     * @param string $name
     * @return void
     */
    public function text(string $name)
    {
        $this::$columns[] = "{$name} TEXT";
    }

    /**
     * insert integer in $columns
     *
     * @param string $name
     * @return void
     */
    public function int(string $name)
    {
        $this::$columns[] = "{$name} INTEGER";
    }

    /**
     * insert float in $columns
     *
     * @param string $name
     * @return void
     */
    public function float(string $name)
    {
        $this::$columns[] = "{$name} FLOAT";
    }

    /**
     * insert timestamp in $columns
     * 
     * @return void
     */
    public function timestamp()
    {
        $this::$columns[] = 'timestamp DATETIME';
    }

    /**
     * insert JSON in $columns
     * 
     * @param string $name
     * @return void
     */
    public function json(string $name)
    {
        $this::$columns[] = "{$name} JSON";
    }

}
