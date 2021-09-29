<?php

use App\Command;
use App\Namespaces\DefaultEmbed;
use Discord\Parts\Channel\Message;

new App\Command([
    'name' => 'help',
    'description' => 'Help Command',
    'run' => function(Message $message, string $rest) {

        if(empty($rest)) {
            $commands = "Here are all my available command:\n\n";

            foreach(Command::$commands as $command) {
                $commands .= "**".PREFIX.$command->name."** - ".$command->description."\n";
            }
    
            $embed = new DefaultEmbed($message, $message->discord, [
                'title' => 'Command List',
                'description' => $commands,
            ]);
    
            return $message->channel->sendEmbed($embed->embed);
        } else {

            if(is_null(Command::$commands[$rest])) {
                $embed = DefaultEmbed::create($message, $message->discord, [
                    'title' => 'Command ' . $rest,
                    'description' => 'Command Not Found',
                ]);
        
                return $message->channel->sendEmbed($embed->embed);
            } else {

                if(Command::$commands[$rest]->aliases) {
                    $aliases = implode(", ", Command::$commands[$rest]->aliases);
                } else {
                    $aliases = "None";
                }

                $embed = DefaultEmbed::create($message, $message->discord, [
                    'title' => 'Command ' . $rest,
                    'description' => "\n",
                    'fields' => [
                        [
                            'name' => 'Description :',
                            'value' => Command::$commands[$rest]->description,
                            'inline' => false
                        ],
                        [
                            'name' => 'Aliases :',
                            'value' => $aliases,
                            'inline' => false
                        ],
                        [
                            'name' => 'Usage :',
                            'value' => Command::$commands[$rest]->usage,
                            'inline' => false
                        ]
                    ]
                ]);

                return $message->channel->sendEmbed(DefaultEmbed::$embed);
            }
        }

    }
]);