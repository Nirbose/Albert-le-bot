<?php

use App\Command;
use App\Namespaces\DefaultEmbed;
use BrianMcdo\ImagePalette\ImagePalette;
use Discord\Builders\MessageBuilder;
use Discord\Parts\Channel\Message;

new Command([
    'name' => 'palette',
    'description' => 'Palette Command',
    'run' => function(Message $message, string $rest) {

        if(!isset($message->attachments[0])) 
            $msg = MessageBuilder::new()->setContent('Une image est attendue.');
            return $message->channel->sendMessage($msg);

        $message->channel->broadcastTyping()->done(function() use ($message) {

            $palette = new ImagePalette($message->attachments[0]->url, 10, 10);
            $array = [];

            foreach($palette as $key => $color) {
                $int = intval($key, 10);
                $result = $int + 1; 
                array_push($array, '> **'.  $result .' -** '. $color);
            }

            DefaultEmbed::create($message, $message->discord, [
                'title' => 'Palette Color',
                'description' => "🎨 - Your image contains the following colors \n\n" . implode("\n", $array),
                'attachments' => [$message->attachments[0]->url]
            ]);

        });
    }
]);