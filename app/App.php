<?php

namespace App;

use Discord\Builders\Components\Component;
use Discord\Builders\MessageBuilder;
use Discord\Helpers\Collection;
use Discord\Parts\Channel\Message;
use Discord\Parts\Part;

class App {

    public int $color = 7096905;

    public Collection $collec;

    public Message $message;

    public function __construct(Message $message)
    {
        $this->collec = new Collection([
            'channels' => [
                'personnel_voice' => ['123', '123']
            ]            
        ]);

        $this->message = $message;
    }

    public function send(string $content, Component $component)
    {
        return $this->message->channel->sendMessage(MessageBuilder::new()->setContent($content)->addComponent($component));
    }

}