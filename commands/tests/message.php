<?php

use App\Command;
use Discord\Parts\Channel\Message;

new Command([
    'name' => 'message',
    'description' => 'Test message',
    'slash' => true,
    'run' => function (Message $message) {
        $message->reply('Hello World!');
    }
]);