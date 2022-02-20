<?php

use App\App;
use App\Command;
use App\Database;
use App\Datas;
use App\Listener;
use App\Namespaces\Permissions;
use Discord\Builders\Components\ActionRow;
use Discord\Builders\Components\Button;
use Discord\Builders\MessageBuilder;
use Discord\Discord;
use Discord\Parts\Channel\Message;
use Discord\Parts\Interactions\Interaction;
use Discord\WebSockets\Event;

new Listener([
    'listener' => Event::MESSAGE_CREATE,
    'run' => function (Message $message, Discord $discord) {
        if (str_starts_with($message->content, PREFIX)) {
            $without_prefix = explode(" ", substr($message->content, 1));

            /** @var Command $command */
            foreach (Command::getCommands() as $command) {

                // Handle eval command
                if (str_starts_with(strtolower($without_prefix[0]), $command->name) || in_array(strtolower($without_prefix[0]), $command->aliases)) {

                    if ($command->permission && !Permissions::hasPermission($message->author, $command->permission)) {
                        return $message->channel->sendMessage("Vous n'avez pas la permition requise");
                    }

                    if ($command->ownerOnly && $message->author->id != $_ENV['OWNER_ID']) {
                        return $message->channel->sendMessage("Vous n'êtes pas le propio.");
                    }

                    if ($command->boosterOnly) {
                        $findBoosterRole = false;

                        foreach ($message->author->roles as $role) {
                            if (in_array($role->id, Datas::BOOSTER_ROLES)) {
                                $findBoosterRole = true;
                            }
                        }

                        if (Permissions::hasPermission($message->author, 'administrator')) {
                            $findBoosterRole = true;
                        }

                        if (!$findBoosterRole) {
                            return $message->channel->sendMessage("Vous n'êtes pas booster !");
                        }
                    }

                    $rest = trim(substr(implode(" ", $without_prefix), strlen($command->name)));

                    // Tkt c'est normal x)
                    // Il demande en premier arg un obj puis les args de la fonction, donc le pourquoi du comment le voila.
                    $command->run->call($message, new App($message, $command));

                    return $command;
                }
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

        Database::new()->insert('messages', [
            'message' => $message->id,
            'authorID' => $message->author->id,
            'channelID' => $message->channel->id,
            'guildID' => $message->channel->guild->id,
            'timestamp' => $message->timestamp
        ]);

        // LEVELS
        $levels = Database::new()->select('levels', ['userID' => $message->author->id, 'guildID' => $message->channel->guild->id]);

        if (!$levels) {
            Database::new()->insert('levels', [
                'userID' => $message->author->id,
                'guildID' => $message->channel->guild->id,
                'level' => 1,
                'xp' => 0,
                'timestamp' => $message->timestamp
            ]);
        } else {
            // Calcul de l'xp de façon exponentielle (^) et crée une suite pour connecter les xp et les levels
            $xp = $levels[0]->xp + pow(($message->timestamp - $levels[0]->timestamp) / 3600, 2);

            // On regarde si on a atteint un niveau
            if ($xp >= pow(($levels[0]->level + 1), 2)) {

                // On ajoute le niveau et remet l'xp à 0
                Database::new()->update('levels', ['level' => $levels[0]->level + 1, 'xp' => 0], ['userID' => $message->author->id, 'guildID' => $message->channel->guild->id]);

                // On envoie un message en mentionnant la personne et son niveau
                $message->channel->sendMessage(MessageBuilder::new()->setContent($message->author . ' a atteint le niveau ' . $levels[0]->level + 1 . ' !'));
            } else {

                // On update les xp
                Database::new()->update('levels', ['xp' => $xp], ['userID' => $message->author->id, 'guildID' => $message->channel->guild->id]);
            }
        }
    }
]);
