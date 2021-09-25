<?php

use App\Namespaces\Permissions;
use Discord\Builders\MessageBuilder;
use Discord\Parts\Channel\Message;

new App\Command([
    'name' => 'clear',
    'description' => 'Clear Command',
    'run' => function(Message $message, string $rest) {
        $arg = explode(" ", $rest);
        $number = intval($arg[0]);
        $number += 1;
        $build = MessageBuilder::new();

        if($number < 1)
            return $message->channel->sendMessage($build->setContent('You cannot delete less than 1 message'));
        if($number > 100)
            return $message->channel->sendMessage($build->setContent('You cannot delete more than 100 messages'));

        $perm = new Permissions($message->author);

        if($perm->hasPermission('manage_messages')) {
            $message->channel->limitDelete($number);
        } else {
            $message->channel->sendMessage($build->setContent('You do not have the required authorization'));
        }

    }
]);