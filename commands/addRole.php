<?php

use App\App;
use App\Command;
use Discord\Builders\Components\Option;
use Discord\Builders\Components\SelectMenu;
use Discord\Builders\MessageBuilder;
use Discord\Helpers\Collection;
use Discord\Parts\Channel\Message;
use Discord\Parts\Interactions\Interaction;

new Command([
    'name' => 'addrole',
    'description' => 'addRole Command',
    'aliases' => ['role', 'roles'],
    'run' => function(Message $message, string $rest, App $app) {

        var_dump($app->collec['roles']['user_choice_roles'][0]);
        $message->delete();
        $select = SelectMenu::new('select_role_menu')
            ->setPlaceholder('Liste des Roles dispo ...')
            ->setMaxValues(1)
            ->setMinValues(1);
            foreach($app->collec['roles']['user_choice_roles'] as $item) {
                $select->addOption(Option::new($item['name'], $item['roleID'])->setDescription($item['description'])->setEmoji($item['emoji']));
            }
        
        $builder = MessageBuilder::new()->addComponent($select)->setContent('Choisie un role !');
        $message->channel->sendMessage($builder);

        $select->setListener(function (Interaction $interaction) use ($message) {
            $roleID = $interaction->data->values[0];

            if(!$interaction->guild->roles->has($roleID)) {
                return $interaction->respondWithMessage(MessageBuilder::new()->setContent('😅 Role indisponible sur se serveur ...'), true);
            }

            if(!$interaction->member->roles->has($roleID)) {
                $interaction->member->addRole($roleID);
                return $interaction->respondWithMessage(MessageBuilder::new()->setContent('💪 Et vous voila avec le role <@&'.$roleID.'>'), true);
            } else {
                return $interaction->respondWithMessage(MessageBuilder::new()->setContent('😅 Vous possédez déjà le role'), true);
            }

        }, $message->discord);

    }
]);