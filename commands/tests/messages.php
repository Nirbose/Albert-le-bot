<?php

use App\App;
use App\Command;

new Command([
    'name' => 'messages',
    'description' => 'Test messages',
    'aliases' => ['m'],
    'args' => [
        [
            'name' => 'truc',
            'description' => 'truc test',
            'type' => 'string',
        ],
        [
            'name' => 'machin',
            'description' => 'machin test',
            'required' => true,
            'type' => 'string',
        ],
    ],
    'run' => function (App $message) {
        $message->send('Hello World! ' . $message->args->truc);
    }
]);