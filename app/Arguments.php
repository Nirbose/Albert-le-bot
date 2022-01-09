<?php

namespace App;

use Discord\Parts\Channel\Message;

class Arguments {

    private array $render = [];

    public function __construct(
        private Message $message,
        private array $args
        ) 
    {
        $split = explode(' ', $this->message->content);
        $split = array_splice($split, 1, count($split));

        foreach ($this->args as $key => $value) {
            $this->{$value['name']} = $split[$key];
        }
    }

    public function getRest() {
        $count = count($this->render);

        foreach ($this->render as $value) {
            $count += strlen($value);
        }

        return trim(substr($this->message, $count));
    }

}
