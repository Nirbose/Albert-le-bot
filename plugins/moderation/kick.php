<?php

use App\Command;
use Discord\Parts\Channel\Message;

new Command([
    'name' => 'kick',
    'description' => 'Kick Command',
    'run' => function (Message $message, string $rest) {
        
    }
]);
