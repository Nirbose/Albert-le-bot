<?php

use App\Command;
use Discord\Builders\Components\Option;
use Discord\Builders\Components\SelectMenu;
use Discord\Builders\MessageBuilder;
use Discord\Parts\Channel\Message;
use Discord\Parts\Guild\Emoji;

new Command([
    'name' => 'btn',
    'run' => function(Message $message, string $rest) {
        $select = SelectMenu::new()
        ->setPlaceholder('Click Me...')
        ->addOption(Option::new('test', 'tests'))
        ->addOption(Option::new('teste', 'testd'))
        ->addOption(Option::new('testd', 'testf'))
        ->addOption(Option::new('tests', 'testg'))
        ->setMaxValues(2)
        ->setMinValues(1);

        $builder = MessageBuilder::new();

        $component = SelectMenu::new();
        $builder->addComponent($select);
        $builder->setContent('test');

        $message->channel->sendMessage($builder);
    }
]);