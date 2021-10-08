<?php

use Discord\Discord;
use Discord\Parts\Channel\Message;
use Discord\Parts\WebSockets\MessageReaction;
use Discord\WebSockets\Event;

$saveMessages = [];
const LIMIT_EMOJI_WARN = 5;

new App\Listener([
    'listener' => Event::MESSAGE_REACTION_ADD,
    'run' => function(MessageReaction $reaction, Discord $discord) use ($saveMessages) {
        if ($reaction->emoji->name == '🚩') {
            $reaction->channel->messages->fetch($reaction->message_id, true)->done(function (Message $m) use ($saveMessages, $reaction) {
                if($m->reactions['🚩']->count == LIMIT_EMOJI_WARN) {
                    $saveMessages .= $m->reactions['🚩']->message_id;
                    $reaction->channel->sendMessage('Se message est warn !');
                }
            });
        }
    },
]);
