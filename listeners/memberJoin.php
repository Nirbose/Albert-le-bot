<?php

use App\Database\DB;
use App\Listener;
use Discord\Builders\Components\ActionRow;
use Discord\Builders\Components\Button;
use Discord\Builders\MessageBuilder;
use Discord\Discord;
use Discord\Parts\Channel\Message;
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

        $btn = Button::new(Button::STYLE_SECONDARY, 'welcome')
            ->setLabel('Welcome')
            ->setEmoji('👋');

        $row = ActionRow::new();
        $row->addComponent($btn);

        $channel->sendMessage(
            MessageBuilder::new()
            ->setContent('<:emoji_3:829625615090712576> ' . $message)
            ->addComponent($row)
        )->done(function (Message $message) use ($member) {
            DB::table('welcomes')->insert([
                'guildID' => $message->channel->guild->id,
                'messageID' => $message->id,
                'memberID' => $member->id,
                'timestamp' => time(),
            ]);
        });
    }
]);
