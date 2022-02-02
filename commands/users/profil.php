<?php

use App\App;
use App\Command;
use Carbon\Carbon;
use Discord\Builders\MessageBuilder;
use Discord\Parts\Channel\Message;

new Command([
    'name' => 'profil',
    'description' => 'Affiche le profil de l\'utilisateur',
    'slash' => true,
    'slashType' => 2,
    'slashGuilds' => ['781105165754433537'],
    'run' => function (App $message) {
        $result = $message->database->get('messages', ['authorID' => $message->metadata->data->target_id]);
        $storage = [];
        $data = [];
        $graph = "";
        
        foreach ($result as $val) {
            $storage[Carbon::parse($val['timestamp'])->format("Y/m/d")][] = $val;
        }

        for ($i = 0; $i < 4; $i++) {
            if (@$storage[Carbon::now()->addDays(-$i)->format("Y/m/d")]){
                $data[Carbon::now()->addDays(-$i)->format("d")] = count($storage[Carbon::now()->addDays(-$i)->format("Y/m/d")]);
            } else {
                $data[Carbon::now()->addDays(-$i)->format("d")] = 0;
            }
        }

        $scale = intdiv(max($data), 4);

        foreach ($data as $key => $val) {
            $graph = " ". $key . $graph;
        }
        $graph = "\n> `   " . $graph."`";

        for ($i = 0; $i < 4; $i++) {
            foreach ($data as $val) {
                if ($val - $scale >= 0 || $val == 0) {
                    $graph = "🟦" . $graph;
                    $val -= $scale;
                } else {
                    $graph = "◼️" . $graph;
                }
            }

            $number = @($scale - $data[$i]) * $i;

            if ($number < 10) {
                $number = "0" . $number;
            }

            $graph = "> ` ". $number ." `" . $graph;
            $graph = "\n" . $graph;
        }

        $member = $message->metadata->guild->members->get('id', $message->metadata->data->target_id);

        $embed = [
            'color' => COLOR,
            'author' => [
                'name' => $member->user->username,
                'icon_url' => $member->user->avatar,
            ],
            'description' => "Profil de " . $member . ". Membre `ACTIF` depuis le <t:" . Carbon::parse($member->joined_at)->timestamp . ">.",
            'fields' => [
                [
                    'name' => "\xE2\x80\x8C",
                    'value' => "```diff\n- Stats Messages```",
                ],
                [
                    'name' => "\xE2\x80\x8C",
                    'value' => "✉️ - **Messages**\n\n> Total : ` " . count($result) . " messages `\n> Auj : ` " . $data[Carbon::now()->format("d")] . " messages `",
                    'inline' => true,
                ],
                [
                    'name' => "\xE2\x80\x8C",
                    'value' => "📈 - **Graphiques**\n" . $graph,
                    'inline' => true,
                ],
            ],
        ];

        $message->send('', ['embeds' => [$embed]]);
    }
]);
