<?php

use App\App;
use App\Command;
use App\Namespaces\Permissions;
use Discord\Builders\MessageBuilder;
use Discord\Parts\Channel\Message;

$randomPhrases = ['{user} c\'est pris la foudre du modérateur {author} !', '{author} à bannis {user} !'];

new Command([
    'name' => 'ban',
    'description' => 'Ban Command',
    'usage' => $_ENV['PREFIX'].'ban [userID] [reason]',
    'run' => function (Message $message, string $rest, App $app) use ($randomPhrases) {
        if(Permissions::hasPermission($message->member, 'ban_members')) {
            $reply = $randomPhrases[rand(0, count($randomPhrases) - 1)];
            $reply = str_replace(['{user}', '{author}'], [$rest, $message->author], $reply);
            
            $builder = MessageBuilder::new()->setEmbeds([[
                'title' => 'Ban !',
                'description' => $reply
            ]]);

            $message->channel->sendMessage($builder);
        }
    }
]);