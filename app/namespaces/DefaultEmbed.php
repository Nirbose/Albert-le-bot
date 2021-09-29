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

    /**
     * Content Discord message of user
     *
     * @var Message
     */
    public $message;

    public static function create(Message $message, Discord $discord, array $options = []) {
        self::$message = $message;

        if(isset($options['color']))
            self::$color = $options['color'];

        $content = [
            'color' => hexdec(self::$color),
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

        self::$embed = new Embed($discord, $content, true);

        return;
    }

    /**
     * Send Embed in the discord channel
     *
     * @return void
     */
    public function send() {
        return $this->message->channel->sendEmbed($this->embed);
    }

}
