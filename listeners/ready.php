<?php

use App\Commands\Command;
use App\Commands\CommandBuilder;
use App\Listener;
use Discord\Parts\Interactions\Command\Command as SlashCommand;
use Discord\Parts\User\Activity;

new Listener([
    'listener' => 'ready',
    'run' => function (\Discord\Discord $discord) {
        echo "Bot is ready!", PHP_EOL;

        $activity = new Activity($discord, [
            'name' => 'les services de Nirbose',
            'type' => Activity::TYPE_LISTENING
        ], true);
        $discord->updatePresence($activity, false, 'online');

        $cmds = Command::getCommands();

        foreach ($cmds as $cmd) {
            $cmd = new CommandBuilder($cmd);

            if ($cmd->isSlash()) {
                $slash = new SlashCommand($discord, $cmd->slashBuilder());

                if (count($cmd->guilds) > 0) {
                    foreach ($cmd->guilds as $guild) {
                        $discord->guilds->get('id', $guild)->commands->save($slash);
                    }
                } else {
                    $discord->application->commands->save($slash);
                }

            }
        }
    }
]);
