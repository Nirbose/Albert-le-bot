<?php

use App\App;
use App\Command;

new Command([
    'name' => 'disquette',
    'description' => 'Cite une disquette de l\'api disquette-api',
    'aliases' => ['d'],
    'args' => [
        [
            'name' => 'id',
            'description' => 'id de la disquette',
        ]
    ],
    'run' => function (App $message) {
        if ($message->args->id) {
            $file = file_get_contents('https://disquettes-api.nirbose.fr/' . $message->args->id);
        } else {
            $file = file_get_contents('https://disquettes-api.nirbose.fr/random');
        }
        $json = json_decode($file);

        $message->send(html_entity_decode("> " . $json->sentence));
    }
]);
