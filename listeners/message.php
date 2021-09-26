<?php

use Discord\Discord;
use Discord\Parts\Channel\Message;

new App\Listener([
    'listener' => 'message',
    'run' => function(Message $message, Discord $client) {

        // var_dump($message->content);

        // $content = strtolower(trim($message->content));
        // var_dump($content);

        // if($content == 'salut') {
        //     $message->channel->sendMessage('Hey !');
        // }

        // if($content == '<@!'.$client->id.'>') {
        //     $message->channel->sendMessage('Que puis je faire pour vous ?');
        // }
    },
]);