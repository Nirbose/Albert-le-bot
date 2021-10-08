<?php

use App\Listener;
use Discord\Discord;
use Discord\Parts\WebSockets\VoiceStateUpdate;
use Discord\WebSockets\Event;

new Listener([
    'listener' => Event::VOICE_STATE_UPDATE,
    'run' => function (VoiceStateUpdate $state, Discord $discord) {
        var_dump($state);
    }
]);