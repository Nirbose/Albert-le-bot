<?php

namespace App;

use Discord\Parts\Channel\Message;

class Arguments {

    public array $render = [];

    public function __construct(
        private Message $message,
        private array $args
        ) 
    {
        $split = explode(' ', $this->message->content);

    }

    public function getRest() {
        $count = count($this->render);

        foreach ($this->render as $value) {
            $count += strlen($value);
        }

        return trim(substr($this->message, $count));
    }

}
