<?php

use App\Command;
use Discord\Parts\Channel\Channel;
use Discord\Parts\Channel\Message;

new Command([
    'name' => 'rename',
    'description' => 'Rename Voice Command',
    'boosterOnly' => true,
    'usage' => $_ENV['PREFIX'].'rename [name]',
    'run' => function (Message $message, string $rest) {

        if (empty($rest)) {
            return;
        }

        $find = false;

        foreach ($message->guild->channels as $channel) {
            if ($channel->type == Channel::TYPE_VOICE) {
                foreach ($channel->members as $member) {
                    if ($member->user_id == $message->member->id) {
                        if (!in_array($channel->id, $GLOBALS['voice_save'])) {
                            return;
                        }
                
                        $channel->name = $rest;
                        $message->guild->channels->save($channel);
                        
                        $find = true;
                    }
                }
            }
        }

        if (!$find) {
            return $message->channel->sendMessage('Vous n\'êtes pas présent dans un salon vocal !');
        }
    }
]);
