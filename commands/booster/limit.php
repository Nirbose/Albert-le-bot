<?php

use App\Command;
use Discord\Parts\Channel\Message;

new Command([
    'name' => 'limit',
    'description' => 'Limit user in personnal voice Command',
    'boosterOnly' => true,
    'usage' => $_ENV['PREFIX'].'limit [number]',
    'run' => function (Message $message, string $rest) {
        
    }
]);
