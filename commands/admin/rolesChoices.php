<?php

use App\App;
use App\Command;
use Discord\Builders\Components\Option;
use Discord\Builders\Components\SelectMenu;
use Discord\Builders\MessageBuilder;
use Discord\Parts\Channel\Message;
use Discord\Parts\Interactions\Interaction;

new Command([
    'name' => 'rolechoices',
    'ownerOnly' => true,
    'run' => function (Message $message, string $rest, App $app) {
        $message->delete();
        $description = '';
        $select = SelectMenu::new('roles_choices')
            ->setPlaceholder('Tout les rôles dispo sont là !')
            ->setMinValues(1)
            ->setMaxValues(count($app->collec['roles']['user_choice_roles']))
            ->setListener(function (Interaction $interaction) {
                $return = 0;

                foreach ($interaction->data->values as $item) {
                    if($interaction->guild->roles->has($item)) {
                        if(!$interaction->member->roles->has($item)) {
                            $interaction->member->addRole($item);
                        } else {
                            $return += 1;
                        }
                    }
                }

                if($return == count($interaction->data->values)) {
                    $interaction->respondWithMessage(MessageBuilder::new()->setContent('Vous possedez déjà tout les roles !'), true);
                } else {
                    $interaction->respondWithMessage(MessageBuilder::new()->setContent('Voila, regarder votre profil pour voir tout vos nouveau role ! si il vous en manque 1 ou plus je vous invite à faire la commande '.$_ENV['PREFIX'].'role'), true);
                }
            }, $message->discord);
        foreach($app->collec['roles']['user_choice_roles'] as $item) {
            $select->addOption(Option::new($item['name'], $item['roleID'])->setDescription($item['description'])->setEmoji($item['emoji']));
            $description .= "{$item['emoji']} - {$item['name']}\n> {$item['description']}\n\n";
        }

        $message->channel->sendMessage(MessageBuilder::new()
            ->addComponent($select)
            ->setEmbeds([[
                'title' => 'Choisissez vos rôles !',
                'description' => $description,
                'color' => $app->color,
                'footer' => [
                    'text' => 'Sélectionnez les rôles que vous souhaitez.'
                ]
            ]]));
    }
]);