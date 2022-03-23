<?php

use App\Commands\Command;
use App\Database\DB;
use Carbon\Carbon;
use Discord\Builders\MessageBuilder;
use Discord\Parts\Interactions\Interaction;

Command::add('profil', function (Interaction $interaction) {
    // Calcule stats
    $result = DB::table('messages')->where([
        'authorID' => $interaction->data->target_id,
    ])->get();

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

    $member = $interaction->guild->members->get('id', $interaction->data->target_id);

    // Envoie du message
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
                'value' => "**Information Utilisateur**\n\n> **ID :** " . $member->user->id . "\n> **Nom d'Utilisateur :** " . $member->user->username,
                'inline' => true,
            ],
            [
                'name' => "\xE2\x80\x8C",
                'value' => "**Information Membre**\n\n> **Nom :** " . $member->nickname . "\n> **Statut :** " . $member->status . "\n> **Rôle :** " . ($member->roles[0] ?? "`Membre`") . "\n> **Statut de Jeu :** " . ($member->game->name ?? "`Aucun`"),
                'inline' => true,
            ],

            // Message Statistiques Embed :
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

            // Voice Statistiques Embed :
            [
                'name' => "\xE2\x80\x8C",
                'value' => "```diff\n- Stats Voice```",
            ],
        ],
    ];

    $interaction->respondWithMessage(
        MessageBuilder::new()
        ->setEmbeds([$embed])
    );
});
