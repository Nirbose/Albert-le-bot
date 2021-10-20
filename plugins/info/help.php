<?php

use App\Command;
use App\Namespaces\DefaultEmbed;
use Discord\Parts\Channel\Message;

new App\Command([
    'name' => 'help',
    'description' => 'Help Command',
    'run' => function(Message $message, string $rest) {

        if(empty($rest)) {
            $category = [
                'booster' => "\n> **Booster Commands**\n",
                'owner' => "\n> **Owner Commands**\n",
                'other' => "\n> **All Commands**\n"
            ];

            foreach(Command::$commands as $command) {
                if ($command->boosterOnly) {
                    $category['booster'] .= "**".PREFIX.$command->name."** - ".$command->description."\n";
                } elseif ($command->ownerOnly) {
                    $category['owner'] .= "**".PREFIX.$command->name."** - ".$command->description."\n";
                } else {
                    $category['other'] .= "**".PREFIX.$command->name."** - ".$command->description."\n";
                }
            }
    
            DefaultEmbed::new()->create($message, $message->discord, [
                'title' => 'Command List',
                'description' => implode("", $category),
            ]);
    
            return $message->channel->sendMessage('', false, DefaultEmbed::$embed, null, $message);
        } else {

            if(is_null(Command::$commands[$rest])) {
                $embed = DefaultEmbed::new()->create($message, $message->discord, [
                    'title' => 'Command ' . $rest,
                    'description' => 'Command Not Found',
                ]);
        
                return $message->channel->sendEmbed(DefaultEmbed::$embed);
            } else {

                if(Command::$commands[$rest]->aliases) {
                    $aliases = implode(", ", Command::$commands[$rest]->aliases);
                } else {
                    $aliases = "None";
                }

                $embed = DefaultEmbed::new()->create($message, $message->discord, [
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