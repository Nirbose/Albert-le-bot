<?php

use App\App;
use App\Command;
use Discord\Builders\Components\Option;
use Discord\Builders\Components\SelectMenu;
use Discord\Builders\MessageBuilder;
use Discord\Parts\Channel\Message;

new Command([
    'name' => 'rolechoices',
    'run' => function (Message $message, string $rest, App $app) {
        $description = '';
        $select = SelectMenu::new('roles_choices')
            ->setPlaceholder('Tout les rôles dispo sont là !')
            ->setMinValues(1)
            ->setMaxValues(count($app->collec['roles']['user_choice_roles']));
        foreach($app->collec['roles']['user_choice_roles'] as $item) {
            $select->addOption(Option::new($item['name'], $item['roleID'])->setDescription($item['description'])->setEmoji($item['emoji']));
            $description .= "{$item['emoji']} - {$item['name']}\n> {$item['description']}\n\n";
        }

        $message->channel->sendMessage(MessageBuilder::new()
            ->setEmbeds([[
                'title' => 'Choisissez vos rôles !',
                'description' => $description,
                'color' => $app->color,
                'footer' => 'Sélectionnez les rôles que vous souhaitez.'
            ]])
            ->addComponent($select));
    }
]);