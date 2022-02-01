<?php

use App\App;
use App\Command;
use App\Listener;
use Discord\Builders\MessageBuilder;
use Discord\Discord;
use Discord\Parts\Interactions\Interaction;
use Discord\WebSockets\Event;

new Listener([
    'listener' => Event::INTERACTION_CREATE,
    'run' => function(Interaction $interaction, Discord $discord) {
        
        /** @var Command $command */
        foreach (Command::getCommands() as $command) {
            if ($interaction->data->name == $command->name) {
                $command->run->call($interaction, new App($interaction, $command));
            }
        }

        // RULE BUTTON !
        if ($interaction->data->custom_id == "submit_rule") {
            if(!$interaction->member->roles->has('')) {
                $interaction->member->addRole('');
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