<?php

namespace App;

use Discord\Builders\Components\Component;
use Discord\Builders\MessageBuilder;
use Discord\Helpers\Collection;
use Discord\Parts\Channel\Message;

class App {

    public int $color = 7096905;

    public Collection $collec;

    public Message $message;
    public MessageBuilder $builder;

    public function __construct(Message $message)
    {
        $this->collec = new Collection([
            'channels' => [
                'category_ticket' => '781105165754433538',

                'personnel_voice' => ['123', '123']
            ], 
            
            'roles' => [
                'member_role' => '883401672955678780',

                'user_choice_roles' => [
                    [
                        'id' => 1,
                        'emoji' => '📰',
                        'name' => 'News',
                        'description' => 'Vous permet d\'etre au courant de toute les dernières news !',
                        'roleID' => '891719691108769884'
                    ],
                    [
                        'id' => 2,
                        'emoji' => '🎁',
                        'name' => 'Event',
                        'description' => 'Vous permet d\'etre au courant des Events !',
                        'roleID' => '891719934403543080'
                    ],
                    [
                        'id' => 3,
                        'emoji' => '📣',
                        'name' => 'Sondage',
                        'description' => 'Vous permet d\'etre au courant des derniers sondages !',
                        'roleID' => '891720078951850014'
                    ],
                    [
                        'id' => 4,
                        'emoji' => '📑',
                        'name' => 'Projets',
                        'description' => 'Vous permet d\'etre imformer des derniers projet en cours !',
                        'roleID' => '893143264654413864'
                    ]
                ]
            ]
        ]);

        $this->message = $message;
        $this->builder = MessageBuilder::new();
    }

    public function send(string $content, Component $component)
    {
        return $this->message->channel->sendMessage(MessageBuilder::new()->setContent($content)->addComponent($component));
    }

}