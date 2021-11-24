<?php

use App\App;
use App\Command;
use App\Namespaces\Permissions;
use Discord\Builders\MessageBuilder;
use Discord\Parts\Channel\Message;
use Discord\Parts\User\Member;

use function Discord\mentioned;

$randomPhrases = ['{user} c\'est pris la foudre du modérateur {author} !', '{author} à bannis {user} !'];

new Command([
    'name' => 'ban',
    'description' => 'Ban Command',
    'usage' => $_ENV['PREFIX'].'ban [userID] [reason]',
    'run' => function (Message $message, string $rest, App $app) use ($randomPhrases) {
        if(Permissions::hasPermission($message->member, 'ban_members')) {

            $args = explode(" ", $rest);
            $user = $args[0];
            $reason = implode(" ", array_slice($args, 1));

            $verif = preg_match("/<@!\d+>/", $user, $match);

            if (!$verif) {
                return $message->channel->sendMessage("Vous devez mentionner un user pour exécuter cette commande.");
            }

            if (empty($reason)) {
                $reason = "Not Reason.";
            }

            $reply = $randomPhrases[rand(0, count($randomPhrases) - 1)];
            $reply = str_replace(['{user}', '{author}', '{reason}'], [$user, $message->author, $reason], $reply);
            
            $builder = MessageBuilder::new()->setEmbeds([[
                'color' => $app->color,
                'title' => 'Ban !',
                'description' => $reply
            ]]);

            $message->channel->sendMessage($builder);
            $message->guild->members->fetch($user)->done(function (Member $member) use ($reason) {
                $member->ban(null, $reason);
            });
        }
    }
]);