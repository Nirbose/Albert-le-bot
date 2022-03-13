<?php

use App\Commands\Command;
use App\Commands\Context;
use App\Listener;
use Discord\CommandClient\Command as CommandClientCommand;
use Discord\Parts\Interactions\Command\Command as SlashCommand;
use Discord\Parts\Interactions\Command\Overwrite;
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

            if (array_key_exists('context', $cmd) && $cmd['context'] === Context::SLASH->value) {
                $build = [
                    'type' => array_key_exists('type', $cmd) ? $cmd['type'] : 1,
                    'name' => $cmd['name'],
                    'description' => array_key_exists('description', $cmd) ? $cmd['description'] : "null",
                ];

                if ($build['type'] === 1) {
                    $build['options'] = array_key_exists('options', $cmd) ? $cmd['options'] : [];
                }

                if (array_key_exists('subcommands', $cmd)) {
                    foreach ($cmd['subcommands'] as $subcommand) {
                        $build['options'][] = [
                            'type' => 1,
                            'name' => $subcommand['name'],
                            'description' => array_key_exists('description', $subcommand) ? $subcommand['description'] : "null",
                            'options' => array_key_exists('options', $subcommand) ? $subcommand['options'] : [],
                        ];
                    }
                }

                $slash = new SlashCommand($discord, $build);

                if (array_key_exists('guilds', $cmd)) {

                    if (count($cmd['guilds']) > 0) {
                        foreach ($cmd['guilds'] as $guild) {
                            $saveCMD = $discord->guilds->get('id', $guild)->commands->save($slash);
                        }
                    } else {
                        $saveCMD = $discord->application->commands->save($slash);
                    }

                } else {
                    $saveCMD = $discord->application->commands->save($slash);
                }

                if (array_key_exists('permissions', $cmd)) {
                    foreach ($cmd['permissions'] as $permission) {
                        $overwrite = new Overwrite($discord, [
                            'id' => $permission['id'],
                            'type' => $permission['type'],
                            'permission' => $permission['permission'],
                        ]);

                        $saveCMD->done(function (SlashCommand $slash) use ($overwrite) {
                            $slash->setOverwrite($overwrite);
                        });
                    }
                }

            }
        }
    }
]);
