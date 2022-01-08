<?php

namespace App;

use Discord\Discord;
use Discord\Parts\Channel\Message;
use App\Command;
use App\Listener;
use App\Namespaces\Permissions;
use Discord\Builders\Components\ActionRow;
use Discord\Builders\Components\Button;
use Discord\Builders\MessageBuilder;
use Discord\Parts\Interactions\Interaction;
use Discord\WebSockets\Event;

class Handler {

    public Message $message;
    public Discord $client;

    public function handler(Discord $client)
    {
        // Handle slash command
        $client->on(Event::INTERACTION_CREATE, function (Interaction $interaction) {
            foreach (Command::getCommand() as $command) {
                if ($interaction->data->name == $command->name) {
                    $command->run->call($interaction, $interaction);
                }
            }
        });

        // Handle message command
        $client->on('message', function(Message $message, Discord $client) {
            $this->message = $message;
            $this->client = $client;

            $this->onMessage($this->message, $client);

            $this->command();
        });

        $this->listener($client);
    }

    private function command()
    {
        if ($this->message->type != Message::TYPE_NORMAL) return null;

        if (str_starts_with($this->message->content, PREFIX)){
            $without_prefix = explode(" ", substr($this->message->content, 1));

            /** @var Command $command */
            foreach (Command::getCommand() as $command) {

                // Handle eval command
                if (str_starts_with(strtolower($without_prefix[0]), $command->name) || in_array(strtolower($without_prefix[0]), $command->aliases)){

                    if ($command->permission && !Permissions::hasPermission($this->message->author, $command->permission)) {
                        return $this->message->channel->sendMessage("Vous n'avez pas la permition requise");
                    }

                    if ($command->ownerOnly && $this->message->author->id != $_ENV['OWNER_ID']) {
                        return $this->message->channel->sendMessage("Vous n'êtes pas le propio.");
                    } 

                    if ($command->boosterOnly) {
                        $findBoosterRole = false;

                        foreach ($this->message->author->roles as $role) {
                            if (in_array($role->id, Datas::BOOSTER_ROLES)) {
                                $findBoosterRole = true;
                            }
                        }

                        if (Permissions::hasPermission($this->message->author, 'administrator')) {
                            $findBoosterRole = true;
                        }

                        if (!$findBoosterRole) {
                            return $this->message->channel->sendMessage("Vous n'êtes pas booster !");
                        }
                    }
    
                    $rest = trim(substr(implode(" ", $without_prefix), strlen($command->name)));

                    // Tkt c'est normal x)
                    // Il demande en premier arg un obj puis les args de la fonction, donc le pourquoi du comment le voila.
                    $command->run->call($this->message, $this->message, $rest, new App($this->message));

                    return $command;
    
                }
            }
        }
    }

    private function listener(Discord $client): void
    {
        // Listener for events
        foreach(Listener::$events as $event) {
            $client->on($event->listener, $event->run);
        }
    }

    private function onMessage(Message $message, Discord $client)
    {
        $hello = ['salut', 'hello', 'bjr', 'bonjour', 'yo'];

        $content = strtolower(trim($message->content));

        if ($message->author->bot) return;

        if(in_array($content, $hello)) {
            $message->channel->sendMessage($hello[array_rand($hello)] . " !");
        }

        if($content == '<@!'.$client->id.'>') {
            $buttons = ActionRow::new()
                ->addComponent(Button::new(Button::STYLE_SECONDARY)->setLabel('Ton prefix ?')
                ->setListener(function(Interaction $interaction) { 
                    $interaction->respondWithMessage(MessageBuilder::new()->setContent('Mon préfix est :  `' . $_ENV['PREFIX'] . '`')); 
                }, $client));

            $message->channel->sendMessage(MessageBuilder::new()->setContent('Que puis je faire pour vous ?')->addComponent($buttons));
        }
    }

}