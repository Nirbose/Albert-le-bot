<?php

use App\Command;
use Discord\Builders\MessageBuilder;
use Discord\Parts\Interactions\Interaction;

new Command([
    'name' => 'slash',
    'description' => 'Test slash',
    'slash' => true,
    'run' => function (Interaction $interaction) {
        $interaction->respondWithMessage(MessageBuilder::new()->setContent('Hello World!'));
    }
]);