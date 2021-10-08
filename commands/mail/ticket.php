<?php

use App\App;
use App\Command;
use Discord\Builders\Components\ActionRow;
use Discord\Builders\Components\Button;
use Discord\Builders\MessageBuilder;
use Discord\Parts\Channel\Message;
use Discord\Parts\Embed\Embed;
use Discord\Parts\Interactions\Interaction;

new Command([
    'name' => 'ticket',
    'description' => 'Ticket Command',
    'ownerOnly' => true,
    'run' => function (Message $message, string $rest, App $app) {
        $button = ActionRow::new()->addComponent(Button::new(Button::STYLE_SECONDARY, 'open_ticket')->setLabel('Ouvir un ticket !')->setEmoji('✉')->setListener(function (Interaction $interaction) use ($message, $app) {
            $message->guild->channels->create([
                'name' => 'test',
                // 'parent_id' => $app->collec['channels']['category_ticket']
            ]);
        }, $message->discord));
        
        $builder = MessageBuilder::new()
        ->setEmbeds([[
            'title' => 'Ticket !',
            'description' => 'Ouvrez un ticket pour posez votre question, ou autre !',
            'color' => $app->color
        ]])
        ->addComponent($button);

        $message->channel->sendMessage($builder);
    }
]);