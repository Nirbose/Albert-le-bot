<?php

namespace App;

use Discord\Builders\Components\Component;
use Discord\Builders\MessageBuilder;
use Discord\Helpers\Collection;
use Discord\Parts\Channel\Message;

class App {

    public int $color = 7096905;

    public Collection $collec;

    public Message $message;
    public MessageBuilder $builder;

    public \PDO $database;

    public function __construct(Message $message)
    {
        $this->collec = new Collection(
            json_decode(file_get_contents(dirname(__DIR__).'\\server.json'), true)
        );

        $this->message = $message;
        $this->builder = MessageBuilder::new();
    }

    public function send(string $content, Component $component)
    {
        return $this->message->channel->sendMessage(MessageBuilder::new()->setContent($content)->addComponent($component));
    }

}