<?php

namespace DB;

use \SQLite3;

class Database {

    private static SQLite3|null $db = null;

    /**
     * Get database instance
     *
     * @return SQLite3
     */
    public static function getDB(): SQLite3
    {
        return self::$db;
    }

    /**
     * Create new database instance
     * 
     * @param string $path
     * @return void
     */
    public static function connect(?string $path = ""): void
    {
        if ($path === "") {
            $path = __DIR__ . "/../data/database.db";
        }

        self::$db = new SQLite3($path);
    }

    /**
     * Table instance
     * 
     * @param string $table
     * @return Table
     */
    public static function table(string $table): Table
    {
        return new Table($table);
    }

}
