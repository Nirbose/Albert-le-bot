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
    'timestamp' => 'TEXT'
]);
