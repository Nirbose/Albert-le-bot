<?php

use App\App;
use App\Command;
use Discord\Builders\MessageBuilder;
use Discord\Parts\Interactions\Interaction;

new Command([
    'name' => 'slash',
    'description' => 'Test slash',
    'slash' => true,
    'run' => function (App $message) {
        $message->send('Hello World!');
    }
]);