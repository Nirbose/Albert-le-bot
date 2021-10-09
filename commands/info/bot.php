<?php

use App\App;
use App\Command;
use Discord\Builders\MessageBuilder;
use Discord\Parts\Channel\Message;

new Command([
    'name' => 'botinfo',
    'aliases' => ['bot'],
    'description' => 'BotInfo Command',
    'run' => function (Message $message, string $rest, App $app) {
        // Code botInfo
        $builder = MessageBuilder::new();

        $builder->setEmbeds([
                [
                    'title' => 'Bot Information :',
                    'description' => '[Code source sur GitHub](https://github.com/Nirbose/Albert-le-bot)',
                    'color' => $app->color,
                    'fields' => [
                        [
                            'name' => 'PHP Version :',
                            'value' => phpversion(), 
                            'inline' => true
                        ],
                        [
                            'name' => 'Owner :',
                            'value' => '<@'.$_ENV['OWNER_ID'].'>',
                            'inline' => true
                        ]
                    ]
                ]
            ]);

        $message->channel->sendMessage($builder);
    }
]);