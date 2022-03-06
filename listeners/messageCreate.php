<?php

use App\App;
use App\Commands\Command;
use App\Commands\CommandBuilder;
use App\Commands\Context;
use App\Database\DB;
use App\Listener;
use Discord\Builders\Components\ActionRow;
use Discord\Builders\Components\Button;
use Discord\Builders\MessageBuilder;
use Discord\Discord;
use Discord\Parts\Channel\Message;
use Discord\Parts\Interactions\Interaction;
use Discord\WebSockets\Event;
use Illuminate\Database\Capsule\Manager as Capsule;

new Listener([
    'listener' => Event::MESSAGE_CREATE,
    'run' => function (Message $message, Discord $discord) {
        if (str_starts_with($message->content, PREFIX)) {
            $without_prefix = explode(" ", substr($message->content, 1));

            $command = Command::search($without_prefix[0]);

            if (is_null($command)) {
                return;
            }

            if (array_key_exists('subcommands', $command) && count($without_prefix) > 1) {
                $subcommand = $command['subcommands'][$without_prefix[1]];

                if ($subcommand['run']) {
                    $subcommand['run']->call($message, $message, $discord);
                }
            }

            $cmd = new CommandBuilder($command);

            /** @var Command $command */
            if ($cmd->context != Context::MESSAGE->value) {
                return;
            }
            
            if ($command['run']) {
                $command['run']->call($message, $message, $discord);
            }
        }

        // MESSAGES DE BONJOUR
        $hello = ['salut', 'hello', 'bjr', 'bonjour', 'yo'];

        $content = strtolower(trim($message->content));

        if ($message->author->bot) return;

        if (in_array($content, $hello)) {
            $message->channel->sendMessage($hello[array_rand($hello)] . " !");
        }

        if ($content == '<@!' . $discord->id . '>') {
            $buttons = ActionRow::new()
                ->addComponent(Button::new(Button::STYLE_SECONDARY)->setLabel('Ton prefix ?')
                    ->setListener(function (Interaction $interaction) {
                        $interaction->respondWithMessage(MessageBuilder::new()->setContent('Mon préfix est :  `' . $_ENV['PREFIX'] . '`'));
                    }, $discord));

            $message->channel->sendMessage(MessageBuilder::new()->setContent('Que puis je faire pour vous ?')->addComponent($buttons));
        }

        // Insert a message
        $r = DB::table('messages')->insert([
            'message' => $message->content,
            'authorID' => $message->author->id,
            'channelID' => $message->channel->id,
            'guildID' => $message->channel->guild->id,
            'timestamp' => $message->timestamp
        ]);

        $db = DB::table('users', ['userID']);

        // LEVELS
        $levels = $db->where([
            'userID' => $message->author->id,
            'guildID' => $message->channel->guild->id
        ]);
        
        // Regarde si il a deja un level
        if (!$levels->get()) {
            $db->insert([
                'userID' => $message->author->id,
                'guildID' => $message->channel->guild->id,
                'level' => 1,
                'xp' => 0,
                'timestamp' => $message->timestamp
            ]);
        } else {
            // Calcule l'xp de facon exponentielle
            // Puis ramener l'xp en pourcentage
            // Puis regarde il a attent 100% de xp
            // Si oui, augmente le level
            // Et supprime l'xp
            $level = $levels->first();
            
            $xp = $level['xp'] + 90.5;

            $xp_percent = $xp / (pow(($level['level'] + 1), 2) * 100);

            if ($xp_percent >= 1) {
                $level['level']++;
                $level['xp'] = 0;

                // Ecrit le message
                $message->channel->sendMessage(MessageBuilder::new()->setContent('Bravo ! Vous avez atteint le niveau ' . $level['level']));
            } else {
                $level['xp'] = $xp;
            }

            $r = $db->where([
                'userID' => $message->author->id,
                'guildID' => $message->channel->guild->id
            ])->update([
                'level' => $level['level'],
                'xp' => $level['xp']
            ]);

        }

    }
]);
