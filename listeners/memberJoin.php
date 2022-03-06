<?php

use App\Database\DB;
use App\Listener;
use Discord\Builders\MessageBuilder;
use Discord\Discord;
use Discord\Parts\User\Member;
use Discord\WebSockets\Event;

new Listener([
    'listener' => Event::GUILD_MEMBER_ADD,
    'run' => function (Member $member, Discord $discord) {
        $sentences = DB::table('sentences')->where([
            'guildID' => $member->guild->id,
            'type' => 1,
        ])->get();

        $message = str_replace(['{user}', '{server}'], [$member->user, $member->guild->name], $sentences[rand(0, count($sentences) - 1)]['sentence']);

        $channel = $discord->getChannel('781105165754433540');

        $channel->sendMessage(MessageBuilder::new()->setContent($message));
    }
]);
