<?php

use App\App;
use App\Command;
use Carbon\Carbon;

new Command([
    'name' => 'profil',
    'description' => 'Affiche le profil de l\'utilisateur',
    'slash' => true,
    'slashType' => 2,
    'slashGuilds' => ['781105165754433537'],
    'run' => function (App $message) {
        $result = $message->database->get('messages', ['authorID' => $message->metadata->data->target_id]);
        $time = Carbon::now()->format("Y/m/d");
        $todayCount = 0;
        
        foreach ($result as $val) {
            $messageTime = Carbon::parse($val['timestamp'])->format("Y/m/d");
            if ($time == $messageTime) {
                $todayCount++;
            }
        }

        $member = $message->metadata->guild->members->get('id', $message->metadata->data->target_id);
        $graph = "` 6 `◼️◼️◼️◼️◼️\n> ` 4 `◼️🟦◼️◼️◼️\n> ` 2 `◼️🟦🟦◼️🟦\n> ` 0 `◼️🟦🟦🟦🟦\n> `   25 26 27 28 29`";

        $embed = [
            'color' => COLOR,
            'author' => [
                'name' => $member->username,
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
                    'value' => "✉️ - **Messages**\n> Total : ` " . count($result) . " messages `\n> Auj : ` " . $todayCount . " messages `",
                    'inline' => true,
                ],
                [
                    'name' => "\xE2\x80\x8C",
                    'value' => "📈 - **Graphiques**\n> " . $graph,
                    'inline' => true,
                ],
            ],
        ];
        $message->send('', ['embeds' => [$embed]]);
    }
]);
