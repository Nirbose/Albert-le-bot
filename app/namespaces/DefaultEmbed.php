<?php

namespace App\Namespaces;

use Discord\Parts\Channel\Message;
use Discord\Parts\Embed\Embed;
use Discord\Discord;

/**
 * DefaultEmbed Class
 */
class DefaultEmbed {

    /**
     * Content color Embed
     *
     * @var string
     */
    public $color = '6c4a49';

    /**
     * Content embed
     *
     * @var Embed
     */
    public static $embed;

    public static function new(): self 
    {
        return new static();
    }

    public function create(Message $message, Discord $discord, array $options = [])
    {

        if(isset($options['color']))
            $this->color = $options['color'];

        $content = [
            'color' => hexdec($this->color),
            'author' => [
                'name' => $message->author->user->username,
                'icon_url' => $message->author->user->avatar
            ],
        ];

        if(isset($options['description']))
            $content['description'] = $options['description'];

        if(isset($options['title']))
            $content['title'] = $options['title'];
        
        if(isset($options['fields']))
            $content['fields'] = $options['fields'];

        return self::$embed = new Embed($discord, $content, true);
    }

}
