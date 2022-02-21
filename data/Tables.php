<?php

use App\Database;

Database::new()
->tableCreate('messages', [
    'id' => 'INTEGER PRIMARY KEY',
    'message' => 'TEXT',
    'authorID' => 'TEXT',
    'channelID' => 'TEXT',
    'guildID' => 'TEXT',
    'timestamp' => 'TEXT'
])
->tableCreate('voice_times', [
    'id' => 'INTEGER PRIMARY KEY',
    'userID' => 'TEXT',
    'guildID' => 'TEXT',
    'time' => 'TEXT',
    'timestamp' => 'TEXT'
])
// Levels table creation
->tableCreate('levels', [
    'id' => 'INTEGER PRIMARY KEY',
    'userID' => 'TEXT',
    'guildID' => 'TEXT',
    'level' => 'INTEGER',
    'xp' => 'FLOAT',
    'timestamp' => 'TEXT'
])
// Quest table creation
->tableCreate('quests', [
    'id' => 'INTEGER PRIMARY KEY',
    'guildID' => 'TEXT',
    'level' => 'INTEGER',
    'data' => 'TEXT',
    'timestamp' => 'DATETIME'
]);
