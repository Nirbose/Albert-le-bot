<?php

use App\Listener;
use Discord\Builders\MessageBuilder;
use Discord\Discord;
use Discord\Helpers\Collection;
use Discord\Parts\Interactions\Interaction;
use Discord\WebSockets\Event;

$collec = new Collection(
    json_decode(file_get_contents(dirname(__DIR__).'\\server.json'), true)
);

new Listener([
    'listener' => Event::INTERACTION_CREATE,
    'run' => function(Interaction $interaction, Discord $discord) use ($collec) {
        
        // RULE BUTTON !
        if ($interaction->data->custom_id == "submit_rule") {
            if(!$interaction->member->roles->has($collec['roles']['member_role'])) {
                $interaction->member->addRole($collec['roles']['member_role']);
            } else {
                $interaction->respondWithMessage(MessageBuilder::new()->setContent('Vous avez déjà validé'), true);
            }
        }

        // ROLE CHOICES
        if ($interaction->data->custom_id == "roles_choices") {
            foreach ($interaction->data->values as $item) {
                if($interaction->guild->roles->has($item)) {
                    if(!$interaction->member->roles->has($item)) {
                        $interaction->member->addRole($item);
                    } else {
                        $interaction->member->removeRole($item);
                    }
                }
            }

            $interaction->respondWithMessage(MessageBuilder::new()->setContent('Voilà votre profil est à jour !'), true);
        }
    }
]);