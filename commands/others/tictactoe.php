<?php

use App\Command;
use Discord\Builders\Components\ActionRow;
use Discord\Builders\Components\Button;
use Discord\Builders\MessageBuilder;
use Discord\Parts\Channel\Message;
use Discord\Parts\Interactions\Interaction;

new Command([
    'name' => 'tictactoe',
    'description' => 'Tictactoe Command',
    'run' => function(Message $m, string $rest) {
            $buttons = [];
            $game = [];
            $turn_o = false;
            $message = MessageBuilder::new()
                ->setContent('tic tac toe!');
    
            $checkForWinner = function (Interaction $interaction, int $x, int $y) use (&$message, &$game, &$turn_o) {
                $check = function () use ($x, $y, &$game, &$turn_o) {
                    $turn = $turn_o ? -1 : 1;
                    for ($i = 0; $i < 3; $i++) {
                        if ($game[$x][$i] != $turn) {
                            break;
                        }
    
                        if ($i == 2) {
                            return true;
                        }
                    }
    
                    for ($i = 0; $i < 3; $i++) {
                        if ($game[$i][$y] != $turn) {
                            break;
                        }
    
                        if ($i == 2) {
                            return true;
                        }
                    }
    
                    if ($x == $y) {
                        for ($i = 0; $i < 3; $i++) {
                            if ($game[$i][$i] != $turn) {
                                break;
                            }
    
                            if ($i == 2) {
                                return true;
                            }
                        }
                    }
    
                    if ($x + $y == 2) {
                        for ($i = 0; $i < 3; $i++) {
                            if ($game[$i][2-$i] != $turn) {
                                break;
                            }
    
                            if ($i == 2) {
                                return true;
                            }
                        }
                    }
    
                    return false;
                };
    
                if ($check()) {
                    /** @var ActionRow */
                    foreach ($message->getComponents() as $row) {
                        /** @var Button */
                        foreach ($row->getComponents() as $button) {
                            $button->setDisabled(true);
                        }
                    }
    
                    $message->setContent(($turn_o ? 'O' : 'X').' wins!');
    
                    $interaction->updateMessage($message);
                    return true;
                }
    
                return false;
            };
    
            for ($i = 0; $i < 3; $i++) {
                $row = ActionRow::new();
    
                for ($j = 0; $j < 3; $j++) {
                    $game[$i][$j] = 0;
                    $button = Button::new(Button::STYLE_PRIMARY)
                        ->setLabel('-');
                    $button->setListener(function (Interaction $interaction) use ($button, &$game, $i, $j, &$turn_o, $message, $checkForWinner) {
                        $button->setStyle(Button::STYLE_DANGER)
                            ->setLabel($turn_o ? 'O' : 'X')
                            ->setDisabled(true);
                        $game[$i][$j] = $turn_o ? -1 : 1;
                        if (! $checkForWinner($interaction, $i, $j)) {
                            $interaction->updateMessage($message);
                            $turn_o = ! $turn_o;
                        }
                    }, $m->discord);
    
                    $row->addComponent($button);
                    $buttons[$i][$j] = $button;
                }
    
                $message->addComponent($row);
            }
    
            $m->channel->sendMessage($message);
    }
]);