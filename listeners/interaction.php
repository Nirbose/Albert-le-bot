<?php

use App\App;
use App\Commands\Command;
use App\Database\DB;
use App\Listener;
use Discord\Builders\MessageBuilder;
use Discord\Discord;
use Discord\Parts\Channel\Message;
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

        // WELCOME INTERACTION
        if ($interaction->data->custom_id == "welcome") {
            $welcome = DB::table('welcomes')->where([
                'guildID' => $interaction->guild->id,
                'messageID' => $interaction->message->id,
            ])->first();

            $channel = $discord->getChannel($interaction->channel_id);

            $channel->sendMessage(
                MessageBuilder::new()
                ->setContent("<@" . $welcome['memberID'] . ">")
                ->setReplyTo($interaction->message)
            )->done(function (Message $m) use ($interaction) {
                $m->edit(
                    MessageBuilder::new()
                        ->setContent(' ')
                        ->addEmbed([
                            'color' => hexdec('#2F3136'),
                            'author' => [
                                'name' => $interaction->member->user->username,
                                'icon_url' => $interaction->member->user->avatar
                            ],
                            'image' => [
                                'url' => "https://c.tenor.com/Q7lJ9piCh2YAAAAC/youre-welcome-take-a-bow.gif"
                            ]
                        ])
                );
            });

            $interaction->acknowledge();
        }
    }
]);