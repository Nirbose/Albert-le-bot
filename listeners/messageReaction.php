<?php

use Discord\Discord;
use Discord\Parts\WebSockets\MessageReaction;
use Discord\WebSockets\Event;

$saveMessages = [];

new App\Listener([
    'listener' => Event::MESSAGE_REACTION_ADD,
    'run' => function(MessageReaction $reaction, Discord $discord) use ($saveMessages) {
        if ($reaction->emoji->name == '🚩') {
            
        }
    },
]);
