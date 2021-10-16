<?php

use App\App;
use App\Command;
use App\Namespaces\DefaultEmbed;
use Discord\Builders\MessageBuilder;
use Discord\Parts\Channel\Message;
use Discord\Parts\User\Member;
use Discord\Parts\User\User;

new Command([
    'name' => 'userinfo',
    'description' => 'User Info Command',
    'run' => function (Message $message, string $rest, App $app) {
        $mention = $message->mentions->first();

        if ($mention instanceof User || $mention instanceof Member) {
            $message->guild->members->fetch($mention->id)->done(function (Member $member) use ($message, $app) {
                
    
                $message->channel->sendMessage(MessageBuilder::new()->setEmbeds([[
                    'color' => $app->color,
                    'author' => [
                        'name' => $member->username,
                        'icon_url' => $member->user->avatar
                    ],
                    'description' => "
                        **User information**
                        Username: ".$member->username."#".$member->discriminator."
                        ID: ||".$member->id."||
                        Mention: <@".$member->id.">
                        
                        **Member information**
                        Rejoind: ".$member->joined_at."
                    "
                ]]));
            });
        } else {
            $embed = DefaultEmbed::new()->create($message, $message->discord, [
                'description' => "
                    **User information**
                    Username: ".$message->author->username."#".$message->author->discriminator."
                    ID: ||".$message->author->id."||
                    Mention: <@".$message->author->id.">
                    
                    **Member information**
                    Rejoind: ".$message->author->joined_at."
                "
            ]);

            $message->channel->sendEmbed($embed);
        }
    }
]);
