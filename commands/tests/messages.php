<?php

use App\App;
use App\Commands\Command;
use Discord\Parts\Channel\Message;

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
            'type' => 'string',
        ],
    ],
    'run' => function (App $message) {
        $message->metadata->channel->messages->fetch("937286740929028106")->done(function (Message $message) {
            var_dump($message);
            $message->reply(hexdec("#25b8b8"));
        });
    }
]);