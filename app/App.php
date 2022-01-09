<?php

namespace App;

use Discord\Builders\MessageBuilder;
use Discord\Parts\Channel\Message;
use Discord\Parts\Interactions\Interaction;

class App {

    public $args = [];

    public function __construct(
        private Message|Interaction $message, 
        private Command $command
        )
    {
        $this->args = new Arguments($this->message, $this->command->args);
    }

    public function send(string $content, array $options = [])
    {
        $resp = MessageBuilder::new()->setContent($content);

        if ($this->command->slash) {
            return $this->message->respondWithMessage($resp);
        }

        return $this->message->channel->sendMessage($resp);
    }

}


