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
// Quest table creation
->tableCreate('quests', [
    'id' => 'INTEGER PRIMARY KEY',
    'guildID' => 'TEXT',
    'level' => 'INTEGER',
    'data' => 'TEXT',
    'timestamp' => 'DATETIME'
])
// Sentences table creation
->tableCreate('sentences', [
    'id' => 'INTEGER PRIMARY KEY',
    'guildID' => 'TEXT',
    'sentence' => 'TEXT',
    'type' => 'VARCHAR(255)',
    'timestamp' => 'DATETIME'
])
// Role table creation
->tableCreate('roles', [
    'id' => 'INTEGER PRIMARY KEY',
    'guildID' => 'TEXT',
    'roleID' => 'TEXT',
    'type' => 'VARCHAR(255)',
    'timestamp' => 'DATETIME'
])
// Users table creation
->tableCreate('users', [
    'id' => 'INTEGER PRIMARY KEY',
    'userID' => 'TEXT',
    'guildID' => 'TEXT',
    'level' => 'INTEGER',
    'xp' => 'FLOAT',
    'quest' => 'INTEGER',
    'timestamp' => 'DATETIME'
]);
