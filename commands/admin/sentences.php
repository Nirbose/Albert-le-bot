<?php

use App\Commands\Command;
use App\Commands\CommandOption;
use App\Commands\CommandOptionChoice;
use App\Commands\CommandOptionType;
use App\Commands\Context;
use Discord\Builders\MessageBuilder;
use Discord\Parts\Interactions\Interaction;

Command::add('sentence', function (Interaction $message) {
    $message->respondWithMessage(MessageBuilder::new()->setContent('Ceci est une phrase'), true);
})
->setDescription('Add a sentence to the database')
->setOptions(
    CommandOption::new('sentence', 'sentence to add')
    ->setRequired(true),

    CommandOption::new('type', 'type of the sentence')
    ->setType(CommandOptionType::INT)
    ->setChoices(
        CommandOptionChoice::new('welcome', 1),
        CommandOptionChoice::new('goodbye', 2),
        CommandOptionChoice::new('warn', 3),
        CommandOptionChoice::new('kick', 4),
        CommandOptionChoice::new('ban', 4),
    )
    ->setRequired(true)
)
->setContext(Context::SLASH)
->setGuilds('781105165754433537');
