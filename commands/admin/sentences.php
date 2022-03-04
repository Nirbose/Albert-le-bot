<?php

use App\Commands\Command;
use App\Commands\CommandOption;
use App\Commands\Context;
use Discord\Parts\Channel\Message;

Command::add('sentence', function (Message $message) {
    $message->reply('Hello !');
})
->setDescription('Add a sentence to the database')
->setOptions(
    CommandOption::new('sentence', 'sentence to add')
    ->setRequired(true)
)
->setContext(Context::SLASH);
