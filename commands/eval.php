<?php

use App\Namespaces\DefaultEmbed;

new \App\Command([
    'name' => 'eval',
    'description' => 'Eval Command',
    'aliases' => ['run', 'test'],
    'ownerOnly' => true,
    'run' => function($message, $rest) {
        try {
            $value = str_replace(['```php', '```'], '', $rest);
            $result = eval($value.';');
    
            if(is_null($result)){
                $message->channel->sendMessage("Done.");
            }else{
                $stringResult = strval($result);
                DefaultEmbed::create($message, $message->discord, [
                    'title' => 'Eval Command',
                    'description' => "**Enter** :\n ```php\n$value```\n **Result** :\n```php\n$stringResult\n```"
                ])->send();
            }
        }catch (ParseError $error){
            $errorMessage = $error->getMessage();
            $message->channel->sendMessage("```pbp\nParseError: $errorMessage\n```");
        }
    },
]);