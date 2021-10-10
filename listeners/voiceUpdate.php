<?php

use App\Listener;
use Discord\Discord;
use Discord\Helpers\Collection;
use Discord\Parts\Channel\Channel;
use Discord\Parts\WebSockets\VoiceStateUpdate;
use Discord\WebSockets\Event;

$collec = new Collection(
    json_decode(file_get_contents(dirname(__DIR__).'\\server.json'), true)
);

$GLOBALS['voice_save'] = [];

new Listener([
    'listener' => Event::VOICE_STATE_UPDATE,
    'run' => function (VoiceStateUpdate $state, Discord $discord) use ($collec) {
        foreach ($collec['channels']['personnel_voice'] as $voiceID) {
            if ($state->channel_id == $voiceID) {
                $channel = $state->guild->channels->create([
                    'name' => $state->user->username . ' Voice',
                    'type' => Channel::TYPE_VOICE,
                    'parent_id' => $state->channel->parent_id
                ]);

                $state->guild->channels->save($channel)->done(function (Channel $channel) use ($state) {
                    $channel->moveMember($state->user->id);
                    return array_push($GLOBALS['voice_save'], $channel->id);
                });
            }
        }

        foreach ($GLOBALS['voice_save'] as $id) {
            $state->guild->channels->fetch($id)->done(function (Channel $channel) {
                if ($channel->members->count() == 0 && $channel->type == Channel::TYPE_VOICE) {
                    $channel->guild->channels->delete($channel);
                }
            });
        }
    }
]);