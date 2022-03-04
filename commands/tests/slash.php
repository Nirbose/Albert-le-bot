<?php

use App\App;
use App\Commands\Command;
use Discord\Builders\MessageBuilder;
use Discord\Parts\Interactions\Interaction;

new Command([
    'name' => 'slash',
    'description' => 'Test slash',
    'slash' => true,
    'slashType' => 3,
    'slashGuilds' => ['781105165754433537'],
    'run' => function (App $message) {
        $message->send('Hello World!');
    }
]);