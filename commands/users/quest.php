<?php

use App\App;
use App\Commands\Command;

new Command([
    'name' => 'quest',
    'aliases' => ['quest'],
    'description' => 'Affiche la quête actuelle à faire',
    'slash' => true,
    'slashGuilds' => ['781105165754433537'],
    'run' => function (App $message) {
        // quest to generate exponentially
        // We recover the level of the quest
        // they do not exist in the database
        // we create it
        $quest = $message->database->get('quests', ['guildID' => $message->metadata->guild->id]);

        if (!$quest) {
            $message->database->insert('quests', [
                'guildID' => $message->metadata->guild->id,
                'level' => 1
            ]);
        } else {
            $quest = $quest[0];



        }
    }
]);
