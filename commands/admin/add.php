<?php

use App\Commands\Command;
use App\Commands\CommandOption;
use App\Commands\CommandOptionType;
use App\Commands\Context;
use App\Database\DB;
use Discord\Builders\Components\ActionRow;
use Discord\Builders\Components\Component;
use Discord\Builders\Components\TextInput;
use Discord\Builders\MessageBuilder;
use Discord\Helpers\Collection;
use Discord\Parts\Interactions\Interaction;

Command::add('add', function() {})
->setDescription('Add a new command')
->addSubcommand(
    Command::add('quest', function(Interaction $message) {
        $row1 = ActionRow::new();
        $row2 = ActionRow::new();

        $name = TextInput::new('type', TextInput::STYLE_SHORT, 'name')->setPlaceholder('Type de la quête');
        $json = TextInput::new('Quest Content', TextInput::STYLE_PARAGRAPH, 'json')->setPlaceholder('JSON de la quête');

        $row1->addComponent($name);
        $row2->addComponent($json);
        
        $message->showModal('Create a new quest', 'create', [$row1, $row2], function (Interaction $i, Collection $components) {

            DB::table('quests')->insert([
                'userID' => $i->member->user->id,
                'guildID' => $i->guild->id,
                'type' => $components['type']->value,
                'datas' => $components['json']->value,
                'timestamp' => time(),
            ]);

            $i->acknowledge();
        });
    })
    ->setDescription('Add a new quest')
)
->setContext(Context::SLASH)
->setGuilds('781105165754433537');
