<?php

use App\Datas;
use App\Listener;
use Discord\Discord;
use Discord\Parts\Channel\Channel;
use Discord\Parts\WebSockets\VoiceStateUpdate;
use Discord\WebSockets\Event;

$GLOBALS['voice_save'] = [];
$GLOBALS['user_save'] = [];

new Listener([
    'listener' => Event::VOICE_STATE_UPDATE,
    'run' => function (VoiceStateUpdate $state, Discord $discord) {

        $timer = 0;
        // Personnel voice create and delete
        foreach (Datas::PERSONNEL_VOICE as $voiceID) {
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
            } else {
                if (!isset($GLOBALS['user_save'][$state->member->id]) || empty($GLOBALS['user_save'][$state->member->id])) {
                    echo "\noui?";
                    $GLOBALS['user_save'][$state->member->id] = time();
                } else {
                    $timer = time() - $GLOBALS['user_save'][$state->member->id];
                    $GLOBALS['user_save'][$state->member->id] = 0;
                }
            }
        }

        if ($timer) {
            // Add dans la base de donner
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