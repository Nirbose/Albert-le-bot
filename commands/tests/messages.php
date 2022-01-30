<?php

use App\App;
use App\Command;
use Discord\Builders\MessageBuilder;
use Discord\Parts\Embed\Embed;

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
        $message->send('Hello World! ' . $message->args->truc);
        $message->metadata->channel->sendMessage(MessageBuilder::new()->addEmbed(
            [
                'title' => "test",
                'description' => "test",
                'fields' => [
                    [
                        'name' => "\xE2\x80\x8C",
                        'value' => "```diff\n- test```",
                    ],
                ]
            ]
        ));
    }
]);