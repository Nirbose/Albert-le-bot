<?php

namespace App;

class Database {

    public static function getDB() {
        $db = new \PDO('mysql:host='.$_ENV['DB_HOST'].';dbname='.$_ENV['DB_NAME'], $_ENV['DB_USER'], $_ENV['DB_PASS']);
        $db->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
        return $db;
    }

}