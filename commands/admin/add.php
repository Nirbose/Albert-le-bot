<?php

use App\Commands\Command;
use App\Commands\Context;
use Discord\Builders\MessageBuilder;
use Discord\Parts\Channel\Message;
use Discord\Parts\Interactions\Interaction;

Command::add('add', function() {})
->setDescription('Add a new command')
->addSubcommand(
    Command::add('quest', function(Interaction $message) {
        $message->respondWithMessage(MessageBuilder::new()->setContent('Hello world !'));
    })
)
->setContext(Context::SLASH)
->setGuilds('781105165754433537');
