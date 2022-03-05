<?php

use App\Commands\Command;
use App\Commands\CommandOption;
use App\Commands\CommandOptionChoice;
use App\Commands\CommandOptionType;
use App\Commands\Context;
use App\Database\DB;
use Discord\Builders\MessageBuilder;
use Discord\Parts\Interactions\Interaction;

Command::add('sentence', function (Interaction $message) {

    DB::table('sentences')->insert([
        'guildID' => $message->guild->id,
        'sentence' => $message->data->options->get('name', 'sentence')->value,
        'type' => $message->data->options->get('name', 'type')->value,
        'timestamp' => time(),
    ]);

    $message->respondWithMessage(MessageBuilder::new()->setContent('Sentence enregistrer !'), true);
})
->setDescription('Add a sentence to the database')
->setOptions(
    CommandOption::new('type', 'type of the sentence')
    ->setType(CommandOptionType::INT)
    ->setChoices(
        CommandOptionChoice::new('welcome', 1),
        CommandOptionChoice::new('goodbye', 2),
        CommandOptionChoice::new('warn', 3),
        CommandOptionChoice::new('kick', 4),
        CommandOptionChoice::new('ban', 4),
    )
    ->setRequired(true),

    CommandOption::new('sentence', 'sentence to add')
    ->setRequired(true),
)
->setContext(Context::SLASH)
->setGuilds('781105165754433537');
