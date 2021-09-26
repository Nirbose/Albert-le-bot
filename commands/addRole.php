<?php

use App\App;
use App\Command;
use Discord\Builders\Components\Option;
use Discord\Builders\Components\SelectMenu;
use Discord\Builders\MessageBuilder;
use Discord\Helpers\Collection;
use Discord\Parts\Channel\Message;
use Discord\Parts\Interactions\Interaction;

$collec = new Collection([
    [
        'id' => 1,
        'emoji' => '📰',
        'name' => 'News',
        'description' => 'Vous permet d\'etre au courant de toute les dernières news !',
        'roleID' => '891719691108769884'
    ],
    [
        'id' => 2,
        'emoji' => '🎁',
        'name' => 'Event',
        'description' => 'Vous permet d\'etre au courant des Events !',
        'roleID' => '891719934403543080'
    ],
    [
        'id' => 3,
        'emoji' => '📣',
        'name' => 'Sondage',
        'description' => 'Vous permet d\'etre au courant des derniers sondages !',
        'roleID' => '891720078951850014'
    ]
], null, null);

new Command([
    'name' => 'addrole',
    'description' => 'addRole Command',
    'aliases' => ['role'],
    'run' => function(Message $message, string $rest, App $app) use ($collec) {
        $message->delete();
        $select = SelectMenu::new('select_role_menu')
            ->setPlaceholder('Liste des Roles dispo ...')
            ->setMaxValues(1)
            ->setMinValues(1);
            foreach($collec as $item) {
                $select->addOption(Option::new($item['name'], $item['roleID'])->setDescription($item['description'])->setEmoji($item['emoji']));
            }
        
        $builder = MessageBuilder::new()->addComponent($select)->setContent('Choisie un role !');
        $message->channel->sendMessage($builder);

        $select->setListener(function (Interaction $interaction) use ($message) {
            $roleID = $interaction->data->values[0];

            if(!$interaction->guild->roles->has($roleID)) {
                return $interaction->respondWithMessage(MessageBuilder::new()->setContent('😅 Role indisponible sur se serveur ...'), true);
            }
            
            if($interaction->user->id != $message->author->id) {
                return $interaction->respondWithMessage(MessageBuilder::new()->setContent('😁 Executez vous aussi le command `'.$_ENV['PREFIX'].'addRole` !'), true);
            }

            if(!$interaction->member->roles->has($roleID)) {
                $interaction->member->addRole($roleID);
                $interaction->message->delete();
                return $interaction->respondWithMessage(MessageBuilder::new()->setContent('💪 Et vous voila avec le role <@&'.$roleID.'>'), true);
            } else {
                return $interaction->message->delete();
            } 

        }, $message->discord);

    }
]);