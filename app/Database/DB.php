<?php

namespace App\Database;

/**
 * Database class
 */
class DB {
    private static \SQLite3|null $db = null;

    public static function create() {
        if (self::$db === null) {
            self::$db = new \SQLite3(__DIR__ . '/../../data/database.db');
        }
    }

    /**
     * Get Table instance
     *
     * @param string $table
     * @param array $columns
     * @return Table
     */
    public static function table(string $table, array $columns = []): Table
    {
        return new Table(self::$db, $table, $columns);
    }

    /**
     * Get Schema instance
     * 
     * @return Schema
     */
    public static function schema(): Schema
    {
        return new Schema(self::$db);
    }

    /**
     * Get SQLite3 instance
     * 
     * @return SQLite3
     */
    public static function sqlite(): \SQLite3
    {
        return self::$db;
    }
}
