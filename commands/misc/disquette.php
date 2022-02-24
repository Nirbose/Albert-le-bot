<?php

use App\Command;
use App\Type;
use Discord\Parts\Channel\Message;

Command::add(
    'disquette', 

    function (Message $message) {
        $file = file_get_contents('https://disquettes-api.nirbose.fr/random');
        $json = json_decode($file);

        $message->channel->sendMessage(html_entity_decode("> " . $json->sentence));
    }
)
->setDescription('Disquette aléatoire')
->setCategory('Fun')
->setType(Type::ALL)
->setAliases('d');

