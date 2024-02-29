<?php

namespace App;

use Discord\Discord;
use Discord\Parts\Channel\Message;
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
        $client->on(Event::MESSAGE_CREATE, function(Message $message, Discord $client) {
            $this->message = $message;
            $this->client = $client;

            $this->onMessage($this->message, $client);

            $this->command();
        });

        $this->listener($client);
    }

    private function command(): void
    {
        if ($this->message->type != Message::TYPE_DEFAULT) return;

        if (str_starts_with($this->message->content, PREFIX))
        {
            $without_prefix = explode(" ", substr($this->message->content, 1));

            /** @var Command $command */
            foreach(Command::getCommands() as $command)
            {

                if (str_starts_with(strtolower($without_prefix[0]), $command->name) || in_array(strtolower($without_prefix[0]), $command->aliases))
                {
                    $rest = trim(substr(implode(" ", $without_prefix), strlen($command->name)));

                    $command->run($this->message, $rest);
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