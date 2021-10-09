<?php

use App\Command;
use Discord\Parts\Channel\Channel;
use Discord\Parts\Channel\Message;

new Command([
    'name' => 'limit',
    'description' => 'Limit user in personnal voice Command',
    'boosterOnly' => true,
    'usage' => $_ENV['PREFIX'].'limit [number]',
    'run' => function (Message $message, string $rest) {
        $rest = str_replace(' ', '', $rest);

        $find = false;

        foreach ($message->guild->channels as $channel) {
            if ($channel->type == Channel::TYPE_VOICE) {
                foreach ($channel->members as $member) {
                    if ($member->user_id == $message->member->id) {
                        if (!in_array($channel->id, $GLOBALS['voice_save'])) {
                            return;
                        }

                        if (is_numeric($rest)) {
                            $number = $rest;
                        } else {
                            $number = 0;
                        }
                
                        $channel->user_limit = $number;
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
