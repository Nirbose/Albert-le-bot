<?php

use App\App;
use App\Command;

new Command([
    'name' => 'profil',
    'description' => 'Affiche le profil de l\'utilisateur',
    'slash' => true,
    'slashType' => 2,
    'slashGuilds' => ['781105165754433537'],
    'run' => function (App $message) {
        $result = $message->database->get('messages', ['authorID' => $message->metadata->data->target_id]);
        var_dump(count($result));
    }
]);
