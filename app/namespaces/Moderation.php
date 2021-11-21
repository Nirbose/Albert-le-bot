<?php

namespace App\Namespaces;

use App\Database;

class Moderation
{

    private \PDO $db;

    public function __construct()
    {
        $this->db = Database::getDB();
    }

    public function addWarn(string $userID, string $authorID, string $reason)
    {
        $insert = $this->db->prepare('INSERT INTO warns (userID, authorID, reason) VALUES (?, ?, ?)');
        $insert->execute([$userID, $authorID, $reason]);
    }

    public function countWarn(string $userID)
    {
        $count = $this->db->prepare('SELECT COUNT(*) FROM warns WHERE userID = ?');
        $count->execute([$userID]);
        return $count->fetchColumn();
    }
}
