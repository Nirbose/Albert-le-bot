<?php

use App\Listener;
use Discord\Discord;
use Discord\Helpers\Collection;
use Discord\Parts\Channel\Channel;
use Discord\Parts\WebSockets\PresenceUpdate;
use Discord\WebSockets\Event;

new Listener([
    'listener' => Event::PRESENCE_UPDATE,
    'run' => function (PresenceUpdate $presence, Discord $discord) {

        if (!is_null($presence->game->state)) {
            $presence->guild->getInvites()->done(function (Collection $collection) use ($presence) {
                foreach ($collection as $item) {
                    if (str_contains($presence->game->state." ", $item->code)) {
                        if (!$presence->member->roles->has('891707949997785148')) {
                            $presence->member->addRole('891707949997785148');
                            $presence->guild->channels->fetch($presence->guild->system_channel_id)->done(function (Channel $channel) {
                                $channel->sendMessage('Merci d\'avoir mis une invitation du serveur dans ton profil !');
                            });
                        }
                    } else {
                        $presence->member->removeRole('891707949997785148');
                    }
                }
            });
        }
    }
]);
