<?php

use App\App;
use App\Command;

new Command([
    'name' => 'pseudo',
    'description' => 'Change le pseudo de l\'utilisateur',
    'args' => [
        [
            'name' => 'pseudo',
            'description' => 'Le nouveau pseudo',
            'type' => 'string',
            'required' => true
        ]
    ],
    'run' => function (App $message) {
        $message->metadata->member->setNickname($message->args->pseudo);

        $message->send("Votre pseudo à été changé en `{$message->args->pseudo}`");
    }
]);
