<?php

use App\Command;
use Discord\Parts\Channel\Message;

$pseudo = ['Truc', 'Machin', 'Mwa', 'Eddy', 'Jonathan'];

new Command([
    'name' => 'pseudo',
    'description' => 'Auto rename command',
    'run' => function(Message $message, string $rest) use ($pseudo) {

        $rename = $rest;

        if(empty($rename)) {
            $rename = $pseudo[array_rand($pseudo)];
        }

        $message->member->setNickname($rename)->done(function() {}, function() use ($message) {
            $message->channel->sendMessage('Je ne peux pas vous rename...');
        });
    }
]);