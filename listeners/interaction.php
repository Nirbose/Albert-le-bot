<?php

use App\Listener;
use Discord\Builders\MessageBuilder;
use Discord\Discord;
use Discord\Parts\Interactions\Interaction;
use Discord\WebSockets\Event;

$memberRoleID = '888723883677581332';

new Listener([
    'listener' => Event::INTERACTION_CREATE,
    'run' => function(Interaction $interaction, Discord $discord) use ($memberRoleID) {
    
    }
]);