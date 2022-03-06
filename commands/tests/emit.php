<?php

use App\Commands\Command;
use Discord\Discord;
use Discord\Parts\Channel\Message;
use Discord\WebSockets\Event;

Command::add('emit', function (Message $m, Discord $discord) {
    $discord->emit(Event::GUILD_MEMBER_ADD, [$m->member, $discord]);
});
