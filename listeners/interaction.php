<?php

use App\App;
use App\Commands\Command;
use App\Listener;
use Discord\Builders\MessageBuilder;
use Discord\Discord;
use Discord\Parts\Interactions\Interaction;
use Discord\WebSockets\Event;

new Listener([
    'listener' => Event::INTERACTION_CREATE,
    'run' => function(Interaction $interaction, Discord $discord) {
        if ($interaction->type === 2) {

            $cmd = Command::search($interaction->data->name);

            if ($interaction->data->options) {
                foreach ($interaction->data->options as $option) {
                    if ($option->type === 1) {
                        if (!array_key_exists('subcommands', $cmd)) {
                            $interaction->respondWithMessage(new MessageBuilder('Cette commande n\'a pas de sous-commandes.'));
                        }

                        $subcommand = $cmd['subcommands'][$option->name];

                        if (!$subcommand['run']) {
                            $interaction->respondWithMessage(new MessageBuilder('Cette commande n\'a pas de fonction associée.'));
                        } else {
                            $subcommand['run']($interaction, $discord);
                        }
                    }
                }
            }

            if ($cmd) {
                $cmd['run']($interaction, $interaction, $discord);
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