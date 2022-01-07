<?php

use App\Command;
use Discord\Parts\Interactions\Interaction;

new Command([
    'name' => 'test',
    'description' => 'test',
    'slash' => true,
    'run' => function(Interaction $interaction) {
        $interaction->channel->sendMessage('test');
    }
]);