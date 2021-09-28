<?php

namespace App;

use Discord\Discord;
use Discord\Parts\Channel\Message;
use App\Command;
use App\Listener;
use Discord\Builders\Components\ActionRow;
use Discord\Builders\Components\Button;
use Discord\Builders\MessageBuilder;
use Discord\Parts\Interactions\Interaction;

class Handler {

    public $message;
    public $client;

    public $command;

    public function handler(Discord $client)
    {
        $client->on('message', function(Message $message, Discord $client) {
            $this->message = $message;
            $this->client = $client;

            $this->exec($this->message, $client);

            $this->command();
            $this->listener();
        });
    }

    private function command()
    {
        if($this->message->type != Message::TYPE_NORMAL) return null;

        if(str_starts_with($this->message->content, PREFIX)){
            $without_prefix = explode(" ", substr($this->message->content, 1));
    
            foreach(Command::$commands as $command){

                // Handle eval command
                if(str_starts_with(strtolower($without_prefix[0]), $command->name) || in_array(strtolower($without_prefix[0]), $command->aliases)){

                    if($command->ownerOnly && $this->message->author->id != $_ENV['OWNER_ID']) {
                        return $this->message->channel->sendMessage("Nope.");
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

    private function listener(): void
    {
        // Listener for events
        foreach(Listener::$events as $event) {
            $this->client->on($event->listener, $event->run);
        }
    }

    private function exec(Message $message, Discord $client)
    {
        $content = strtolower(trim($message->content));

        if($content == 'salut') {
            $message->channel->sendMessage('Hey !');
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